<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //get all published blog
        $blogs = Blog::whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->with('user')
            ->paginate(10);

        return view('blog.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //create a new blog
        $request->validate([
            'title' => 'required|max:50',
            'markdown_content' => 'required',
        ]);

        // Generate unique slug
        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $count = 1;

        while (Blog::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        // Convert Markdown to HTML
        $htmlContent = \Illuminate\Support\Str::markdown($request->markdown_content);

        $blog = Blog::create([
            // 'user_id' => Auth::id(),
            'user_id' => 1,
            'title' => $request->title,
            'slug' => $slug,
            'markdown_content' => $request->markdown_content,
            'html_content' => $htmlContent,
            'published_at' => $request->has('publish') ? now() : null,
        ]);

        return redirect()->route('blog.show', $blog->slug)
            ->with('success', 'Blog created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
    {
        //show single blog
         // Prevent viewing unpublished posts unless owner
        if (!$blog->published_at || $blog->published_at > now()) {
            abort(404);
        }

        return view('blog.show', compact('blog'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $blog)
    {
        //show edit form
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blog $blog)
    {
        //update blog
        $this->authorize('update', $blog);

        $request->validate([
            'title' => 'required|max:50',
            'markdown_content' => 'required',
        ]);

        // Regenerate slug if title changed
        if ($blog->title !== $request->title) {
            $slug = Str::slug($request->title);
            $originalSlug = $slug;
            $count = 1;

            while (Blog::where('slug', $slug)
                    ->where('id', '!=', $blog->id)
                    ->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }

            $blog->slug = $slug;
        }

        $blog->title = $request->title;
        $blog->markdown_content = $request->markdown_content;
        $blog->html_content = \Illuminate\Support\Str::markdown($request->markdown_content);

        if ($request->has('publish') && !$blog->published_at) {
            $blog->published_at = now();
        }

        $blog->save();

        return redirect()->route('blog.show', $blog->slug)
            ->with('success', 'Blog updated successfully.');
    }   

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        //delete a blog
        $this->authorize('delete', $blog);

        $blog->delete();

        return redirect()->route('blog.index')
            ->with('success', 'Blog deleted successfully.');
    }

}