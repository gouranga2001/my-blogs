<?php

namespace App\Http\Controllers;


use App\Models\Comment;
use App\Models\Blog;
use Illuminate\Http\Request;

class commentcontroller extends Controller
{
    //Get all comments for a blog
    public function index(Blog $blog)
    {
        return response()->json($blog->comments);
    }


    // Store new comment
    public function store(Request $request, Blog $blog)
    {
        $request->validate([
            'author_name' => 'required|max:50',
            'author_email' => 'nullable|email|max:50',
            'text' => 'required',
        ]);

        $comment = $blog->comments()->create($request->all());

        return response()->json($comment, 201);
    }

    // Update comment
    public function update(Request $request, Comment $comment)
    {
        $request->validate([
            'author_name' => 'required|max:50',
            'author_email' => 'nullable|email|max:50',
            'text' => 'required',
        ]);

        $comment->update($request->all());

        return response()->json($comment);
    }

    // Delete comment
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully'
        ]);
    }
}
