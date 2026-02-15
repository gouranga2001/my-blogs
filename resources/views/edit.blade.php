<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Edit Blog</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            background: #ffffff;
        }

        .editor {
            min-height: 300px;
        }
    </style>
</head>

<body>

<div class="container py-4" style="max-width:800px">

    <h2 class="text-primary mb-4">Edit Blog</h2>

    <!-- IMPORTANT: update route -->
    <form method="POST" action="{{ route('blog.update', $blog) }}">
        @csrf
        @method('PATCH')

        <div class="mb-3">
            <label class="form-label">Title</label>

            <!-- pre-filled -->
            <input
                name="title"
                class="form-control"
                required
                maxlength="50"
                value="{{ old('title', $blog->title) }}"
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Markdown Content</label>

            <!-- pre-filled -->
            <textarea
                name="markdown_content"
                class="form-control editor"
                required
            >{{ old('markdown_content', $blog->markdown_content) }}</textarea>
        </div>

        <div class="d-flex gap-2">

            @if(!$blog->published_at)
            <button class="btn btn-primary" name="action" value="publish">
                Publish
            </button>
            @endif

            <button class="btn btn-outline-secondary">
                Update
            </button>

        </div>

    </form>

</div>

</body>
</html>
