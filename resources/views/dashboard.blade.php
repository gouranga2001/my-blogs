<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Admin Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            background: #ffffff;
        }

        .admin-wrapper {
            max-width: 900px;
        }

        .blog-card {
            border-radius: 14px;
            border: 1px solid #e2e8f0;
        }
    </style>
</head>

<body>

    <div class="container py-4 admin-wrapper">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-primary fw-semibold">Admin Dashboard</h2>

            <a href="{{ route('blog.create') }}" class="btn btn-primary">
                + New Blog
            </a>
        </div>

        @forelse($blogs as $blog)
            <div class="card blog-card p-3 mb-3">

                <h5 class="mb-1">{{ $blog->title }}</h5>

                <div class="text-muted mb-3">
                    {{ $blog->published_at ? 'Published' : 'Draft' }}
                </div>

                <div class="d-flex gap-2 flex-wrap">

                    <form method="POST" action="{{ route('blog.update', $blog) }}">
                        @csrf
                        @method('PATCH')

                        @if (!$blog->published_at)
                            <button type="submit" name="action" value="publish" class="btn btn-sm btn-primary">
                                Publish
                            </button>
                        @else
                            <button type="submit" name="action" value="unpublish"
                                class="btn btn-sm btn-outline-danger">
                                Unpublish
                            </button>
                        @endif
                    </form>


                    <a href="{{ route('blog.show', $blog) }}" class="btn btn-sm btn-outline-secondary">
                        View
                    </a>

                    <a href="{{ route('blog.edit', $blog) }}" class="btn btn-sm btn-outline-primary">
                            Edit
                    </a>


                </div>

            </div>

        @empty
            <p class="text-muted">No blogs yet.</p>
        @endforelse

        <div class="mt-4">
            {{ $blogs->links() }}
        </div>

    </div>

</body>

</html>
