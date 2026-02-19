<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        // get all published blog
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
        dd($request->all(), $request->file('thumbnail_image'));

        // create a new blog
        $request->validate([
            'title' => 'required|max:50',
            'markdown_content' => 'required',
            'featured_image' => 'nullable|image|max:4096',
            'thumbnail_image' => 'nullable|image|max:2048',
        ]);

        // Generate unique slug
        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $count = 1;

        while (Blog::where('slug', $slug)->exists()) {
            $slug = $originalSlug.'-'.$count++;
        }

        // Convert Markdown to HTML
        $htmlContent = \Illuminate\Support\Str::markdown($request->markdown_content);
        $featuredPath = null;
        $thumbnailPath = null;

        if ($request->hasFile('featured_image')) {
            $featuredPath = $request->file('featured_image')
                ->store('blogs/featured', 'public');
        }

        if ($request->hasFile('thumbnail_image')) {
            $thumbnailPath = $request->file('thumbnail_image')
                ->store('blogs/thumbnails', 'public');
        }

        $blog = Blog::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'slug' => $slug,
            'markdown_content' => $request->markdown_content,
            'html_content' => $htmlContent,
            'featured_image' => $featuredPath,
            'thumbnail_image' => $thumbnailPath,
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
        // show single blog
        // Prevent viewing unpublished posts unless owner
        if (! $blog->published_at || $blog->published_at > now()) {
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
        return view('edit', compact('blog'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blog $blog)
    {
        // ===== Publish / Unpublish =====
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

        // ===== Validation =====
        $request->validate([
            'title' => 'required|max:50',
            'markdown_content' => 'required',
            'featured_image.*' => 'nullable|image|max:4096', // multiple images
            'thumbnail_image' => 'nullable|image|max:2048',
        ]);

        // ===== Slug Update =====
        if ($blog->title !== $request->title) {
            $slug = Str::slug($request->title);
            $originalSlug = $slug;
            $count = 1;

            while (
                Blog::where('slug', $slug)
                    ->where('id', '!=', $blog->id)
                    ->exists()
            ) {
                $slug = $originalSlug.'-'.$count++;
            }

            $blog->slug = $slug;
        }

        // ===== Update Content =====
        $blog->title = $request->title;
        $blog->markdown_content = $request->markdown_content;
        $blog->html_content = Str::markdown($request->markdown_content);

        // ===== Handle Featured Images (MULTIPLE) =====
        if ($request->hasFile('featured_image')) {

            // delete old images if exist
            if ($blog->featured_image) {
                $oldImages = json_decode($blog->featured_image, true);

                if (is_array($oldImages)) {
                    foreach ($oldImages as $image) {
                        Storage::disk('public')->delete($image);
                    }
                }
            }

            // store new images
            $featuredPaths = [];

            foreach ($request->file('featured_image') as $file) {
                $featuredPaths[] = $file->store('blogs/featured', 'public');
            }

            $blog->featured_image = json_encode($featuredPaths);
        }

        // ===== Handle Thumbnail =====
        if ($request->hasFile('thumbnail_image')) {

            if ($blog->thumbnail_image) {
                Storage::disk('public')->delete($blog->thumbnail_image);
            }

            $blog->thumbnail_image = $request->file('thumbnail_image')
                ->store('blogs/thumbnails', 'public');
        }

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
        // delete a blog
        // $this->authorize('delete', $blog);

        $blog->update([
            'published_at' => null,
        ]);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Blog unpublished successfully.');
    }

    public function admin()
    {
        $blogs = Blog::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('dashboard', compact('blogs'));

    }
}
