<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Blogs</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* ===== Base ===== */

        body {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            background: #ffffff;
        }

        /* ===== Container ===== */

        .blog-wrapper {
            max-width: 820px;
        }

        /* ===== Header ===== */

        .page-title {
            font-weight: 600;
            letter-spacing: -0.5px;
        }

        /* ===== Blog Item (replaces card) ===== */

        .blog-item {
            padding: 36px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .blog-item:last-child {
            border-bottom: none;
        }

        /* subtle interaction */
        .blog-item:hover {
            background: rgba(0, 0, 0, 0.01);
        }

        /* ===== Title ===== */

        .blog-title {
            font-weight: 600;
            color: #0f172a;
            text-decoration: none;
            display: inline-block;
        }

        .blog-title:hover {
            color: var(--bs-primary);
        }

        /* ===== Meta ===== */

        .blog-meta {
            font-size: 0.9rem;
            color: #64748b;
        }

        /* ===== Read More ===== */

        .read-btn {
            font-weight: 500;
        }

        /* ===== Typography ===== */

        .blog-title {
            font-size: clamp(1.2rem, 3vw, 1.45rem);
        }

        .page-title {
            font-size: clamp(1.6rem, 4vw, 2.2rem);
        }

        /* ===== Mobile spacing ===== */

        @media (max-width: 576px) {
            .container {
                padding-left: 14px !important;
                padding-right: 14px !important;
            }

            .blog-item {
                padding: 26px 0;
            }
        }
    </style>


</head>

<body>

    <div class="container py-4 py-md-5 blog-wrapper">

        <!-- Page Header -->
        <div class="mb-4 mb-md-5">
            <h1 class="page-title text-primary">All Blogs</h1>
            <p class="text-muted mb-0">Latest published posts</p>
        </div>

        <!-- Blog List -->
        <div>

            @forelse($blogs as $blog)
                <div class="blog-item">

                    <!-- Title -->
                    <a href="{{ route('blog.show', $blog) }}" class="blog-title mb-2">
                        {{ $blog->title }}
                    </a>

                    <!-- Meta -->
                    <div class="blog-meta mb-3">
                        By <a href="{{ route('user.show', $blog->user) }}"
                            class="text-decoration-none text-primary fw-medium">
                            {{ $blog->user->username }}
                        </a>

                        • {{ $blog->published_at->format('M d, Y') }}
                    </div>

                    <!-- Preview -->
                    <p class="text-muted mb-3">
                        {{ Str::limit(strip_tags($blog->html_content), 160) }}
                    </p>

                    <!-- Read More -->
                    <a href="{{ route('blog.show', $blog) }}" class="btn btn-sm btn-primary read-btn">
                        Read More
                    </a>

                </div>

            @empty

                <div class="text-center text-muted py-5">
                    No blogs published yet.
                </div>
            @endforelse

        </div>


        <!-- Pagination -->
        <div class="mt-4">
            {{ $blogs->links() }}
        </div>

    </div>

</body>

</html>
