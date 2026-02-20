<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Create Blog</title>

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

        <h2 class="text-primary mb-4">Create Blog</h2>

        <form method="POST" action="{{ route('blog.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">Title</label>
                <input name="title" class="form-control" required maxlength="50">
            </div>

            <div class="mb-3">
                <label class="form-label">Markdown Content</label>
                <textarea name="markdown_content" class="form-control editor" required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Thumbnail Image</label>
                <input type="file" name="thumbnail_image" class="form-control">
                <div class="form-text">Recommended size: 1200×400</div>
            </div>

            <div class="mb-3">
                <label class="form-label">Featured Images</label>
                <input type="file" name="featured_image[]" class="form-control" multiple>
                <div class="form-text">You can upload multiple images for markdown content.</div>
            </div>


            <div class="d-flex gap-2">

                <button class="btn btn-primary" name="publish">
                    Publish
                </button>

                <button class="btn btn-outline-secondary">
                    Save Draft
                </button>

            </div>

        </form>

    </div>

</body>

</html>
