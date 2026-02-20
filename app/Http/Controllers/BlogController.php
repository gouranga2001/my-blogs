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
        $blogs = Blog::whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->with('user')
            ->paginate(10);

        return view('index', compact('blogs'));
    }

    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {
        // create a new blog
        $request->validate([
            'title' => 'required|max:50',
            'markdown_content' => 'required',
            'featured_image.*' => 'nullable|image|max:4096',
            'thumbnail_image' => 'nullable|image|max:2048',
        ]);

        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $count = 1;

        while (Blog::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $featuredPaths = [];
        $thumbnailPath = null;

        if ($request->hasFile('thumbnail_image')) {
            $thumbnailPath = $request->file('thumbnail_image')
                ->store('blogs/thumbnails', 'public');
        }

        $markdown = $request->markdown_content;

        // FEATURED IMAGE (MULTIPLE)
        if ($request->hasFile('featured_image')) {

            foreach ($request->file('featured_image') as $file) {

                $path = $file->store('blogs/featured', 'public');
                $featuredPaths[] = $path;

                $originalName = $file->getClientOriginalName();

                $markdown = str_replace(
                    $originalName,
                    "/storage/$path",
                    $markdown
                );
            }
        }

        $htmlContent = Str::markdown($markdown);

        $blog = Blog::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'slug' => $slug,
            'markdown_content' => $markdown,
            'html_content' => $htmlContent,
            'featured_image' => json_encode($featuredPaths), // ✅ fixed
            'thumbnail_image' => $thumbnailPath,
            'published_at' => $request->has('publish') ? now() : null,
        ]);

        return redirect()->route('blog.show', $blog->slug)
            ->with('success', 'Blog created successfully.');
    }

    public function show(Blog $blog)
    {
        if (!$blog->published_at || $blog->published_at > now()) {
            abort(404);
        }

        $blog->load('user', 'comments');

        return view('show', compact('blog'));
    }

    public function edit(Blog $blog)
    {
        return view('edit', compact('blog'));
    }

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
            'featured_image.*' => 'nullable|image|max:4096',
            'thumbnail_image' => 'nullable|image|max:2048',
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

        if ($request->hasFile('featured_image')) {

            if ($blog->featured_image) {
                $oldImages = json_decode($blog->featured_image, true);

                if (is_array($oldImages)) {
                    foreach ($oldImages as $image) {
                        Storage::disk('public')->delete($image);
                    }
                }
            }

            $featuredPaths = [];

            foreach ($request->file('featured_image') as $file) {
                $featuredPaths[] = $file->store('blogs/featured', 'public');
            }

            $blog->featured_image = json_encode($featuredPaths);
        }

        if ($request->hasFile('thumbnail_image')) {

            if ($blog->thumbnail_image) {
                Storage::disk('public')->delete($blog->thumbnail_image);
            }

            $blog->thumbnail_image = $request->file('thumbnail_image')
                ->store('blogs/thumbnails', 'public');
        }

        $blog->markdown_content = $request->markdown_content;
        $blog->html_content = Str::markdown($request->markdown_content);

        $blog->save();

        return redirect()
            ->route('blog.show', $blog->slug)
            ->with('success', 'Blog updated successfully.');
    }

    public function destroy(Blog $blog)
    {
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