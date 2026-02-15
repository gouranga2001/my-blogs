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

    public function index()
    {
        //get all published blog
        $blogs = Blog::whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->with('user')
            ->paginate(10);

        return view('index', compact('blogs'));
        // return response()->json($blogs);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('create');
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
            'user_id' => Auth::id(),
            // 'user_id' => 1,
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
        $blog->load('user', 'comments');

        return view('show', compact('blog'));
        // return response()->json($blog);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $blog)
    {
        return view('edit' ,compact('blog'));
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blog $blog)
    {


        if ($request->action === 'publish') {
            $blog->published_at = now();
            $blog->save();

            return back()->with('success', 'Blog published successfully.');
        }

        if ($request->action === 'unpublish') {
            $blog->published_at = null;
            $blog->save();

            return back()->with('success', 'Blog unpublished successfully.');
        }

        $request->validate([
            'title' => 'required|max:50',
            'markdown_content' => 'required',
        ]);

        if ($blog->title !== $request->title) {
            $slug = Str::slug($request->title);
            $originalSlug = $slug;
            $count = 1;

            while (
                Blog::where('slug', $slug)
                    ->where('id', '!=', $blog->id)
                    ->exists()
            ) {
                $slug = $originalSlug . '-' . $count++;
            }

            $blog->slug = $slug;
        }

        $blog->title = $request->title;
        $blog->markdown_content = $request->markdown_content;
        $blog->html_content = Str::markdown($request->markdown_content);

        $blog->save();

        return redirect()
            ->route('blog.show', $blog->slug)
            ->with('success', 'Blog updated successfully.');
    }

   

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        //delete a blog
        // $this->authorize('delete', $blog);

        $blog->update([
            'published_at' => null
        ]);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Blog unpublished successfully.');
    }

    public function admin()
    {    $blogs = Blog::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('dashboard', compact('blogs'));

    }


}