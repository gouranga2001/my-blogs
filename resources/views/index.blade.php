<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blogs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: Arial,sans-serif;
            background: #f8fafc;
            color: #1e293b;
        }

        .blog-wrapper {
            max-width: 820px;
        }

        /* ── Page header ── */
        .page-header {
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 1.25rem;
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: clamp(1.6rem, 4vw, 2.2rem);
            font-weight: 700;
            letter-spacing: -0.5px;
            color: var(--bs-primary);
        }

        /* ── Blog card ── */
        .blog-card {
            display: flex;
            gap: 1.25rem;
            align-items: flex-start;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: border-color .2s, box-shadow .2s;
            text-decoration: none;
            color: inherit;
        }

        .blog-card:hover {
            border-color: #93c5fd;
            box-shadow: 0 4px 20px rgba(59, 130, 246, .08);
        }

        /* ── Left: content ── */
        .blog-content {
            flex: 1;
            min-width: 0;
        }

        .blog-title {
            font-size: clamp(1.05rem, 2.5vw, 1.2rem);
            font-weight: 700;
            color: #0f172a;
            line-height: 1.4;
            margin-bottom: .35rem;
        }

        .blog-meta {
            font-size: 0.82rem;
            color: #94a3b8;
            margin-bottom: .65rem;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 6px;
        }

        .meta-dot {
            width: 3px;
            height: 3px;
            border-radius: 50%;
            background: #cbd5e1;
            display: inline-block;
            flex-shrink: 0;
        }

        .meta-author {
            color: var(--bs-primary);
            font-weight: 600;
            text-decoration: none;
        }

        .meta-author:hover {
            text-decoration: underline;
        }

        .blog-excerpt {
            font-size: 0.9rem;
            color: #64748b;
            line-height: 1.65;
            margin-bottom: .9rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .read-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--bs-primary);
            background: #eff6ff;
            border: none;
            border-radius: 8px;
            padding: 5px 13px;
            text-decoration: none;
            transition: background .15s;
        }

        .read-btn:hover {
            background: #dbeafe;
            color: var(--bs-primary);
        }

        /* ── Right: thumbnail ── */
        .thumb-box {
            width: 120px;
            height: 120px;
            border-radius: 12px;
            overflow: hidden;
            background: #f1f5f9;
            flex-shrink: 0;
        }

        .thumb-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* ── Empty state ── */
        .empty-state {
            background: #fff;
            border: 1px dashed #cbd5e1;
            border-radius: 16px;
            padding: 3rem;
            text-align: center;
            color: #94a3b8;
        }

        /* ── Mobile ── */
        @media (max-width: 576px) {
            .blog-card {
                flex-direction: column-reverse;
                padding: 1.1rem;
            }

            .thumb-box {
                width: 100%;
                height: 160px;
            }
        }
    </style>
</head>

<body>
    <div class="container py-4 py-md-5 blog-wrapper">

        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title mb-1">Amar Blog</h1>
            <p class="text-muted mb-0" style="font-size:.9rem;">Latest published posts</p>
        </div>

        <!-- Blog List -->
        <div>
            @forelse($blogs as $blog)
                <div class="blog-card">

                    <!-- Content -->
                    <div class="blog-content">

                        <div class="blog-title">
                            <a href="{{ route('blog.show', $blog) }}"
                               style="color:inherit; text-decoration:none;">
                                {{ $blog->title }}
                            </a>
                        </div>

                        <div class="blog-meta">
                            <a href="{{ route('user.show', $blog->user) }}" class="meta-author">
                                {{ $blog->user->username }}
                            </a>
                            <span class="meta-dot"></span>
                            <span>{{ $blog->published_at->format('M d, Y') }}</span>
                        </div>

                        <p class="blog-excerpt">
                            {{ Str::limit(strip_tags($blog->html_content), 160) }}
                        </p>

                        <a href="{{ route('blog.show', $blog) }}" class="read-btn">
                            Read More &rarr;
                        </a>
                    </div>

                    <!-- Thumbnail -->
                    <div class="thumb-box">
                        @if ($blog->thumbnail_image)
                            <img src="{{ asset('storage/' . $blog->thumbnail_image) }}"
                                 alt="{{ $blog->title }}">
                        @endif
                    </div>

                </div>
            @empty
                <div class="empty-state">
                    <p class="mb-0">No blogs published yet.</p>
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
