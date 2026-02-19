<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $blog->title }}</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.11.1/styles/default.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.11.1/highlight.min.js"></script>

    <!-- and it's easy to individually load additional languages -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.11.1/languages/go.min.js"></script>

    <script>
        hljs.highlightAll();
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll('.markdown pre code').forEach(el => {
                hljs.highlightElement(el);
            });
        });
    </script>

    <style>
        /* ===== Base Layout ===== */

        html,
        body {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            background-color: #ffffff;
            /* soft neutral white */
            color: #1e293b;
            /* slate-800 */
        }

        .blog-container {
            max-width: 760px;
            width: 100%;
            padding-left: 16px;
            padding-right: 16px;
        }


        /* ===== Title & Meta ===== */

        h1.display-5 {
            color: #0f172a;
            /* darker slate */
        }

        .text-muted {
            color: #64748b !important;
            /* slate-500 */
        }

        /* ===== Markdown Typography ===== */
        /* ===== Responsive Typography ===== */

        h1.display-5 {
            font-size: clamp(1.6rem, 4vw, 2.5rem);
        }

        .markdown h1 {
            font-size: clamp(1.4rem, 3.5vw, 2rem);
        }

        .markdown h2 {
            font-size: clamp(1.2rem, 3vw, 1.6rem);
        }

        .markdown h3 {
            font-size: clamp(1.1rem, 2.5vw, 1.3rem);
        }

        .markdown p {
            font-size: clamp(0.95rem, 2.5vw, 1.05rem);
        }

        .markdown ul,
        .markdown ol {
            margin: 1rem 0;
            padding-left: 1.6rem;
        }

        .markdown li {
            margin-bottom: 0.4rem;
        }

        /* ===== Links ===== */

        .markdown a {
            color: #2563eb;
            /* modern blue */
            text-decoration: none;
        }

        .markdown a:hover {
            text-decoration: underline;
            color: #1d4ed8;
        }

        /* ===== Code Styling ===== */

        /* Inline code */
        .markdown code {
            background: #eef2ff;
            /* soft indigo tint */
            color: #4338ca;
            padding: 0.2rem 0.4rem;
            border-radius: 6px;
            font-size: 0.9rem;
        }


        /* Code block */
        .markdown pre {
            background: #F9F9F9;
            color: #0f172a;
            padding: 1.2rem;
            border-radius: 12px;
            overflow-x: auto;
            margin: 1.5rem 0;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
            -webkit-overflow-scrolling: touch;
            font-size: 0.9rem;
        }



        /* Ensure highlight.js inherits properly */
        .markdown pre code {
            background: transparent;
            color: inherit;
            padding: 0;
        }

        /* ===== Blockquote ===== */

        .markdown blockquote {
            border-left: 4px solid #3b82f6;
            padding-left: 1rem;
            color: #475569;
            background: #ffffff;
            padding: 0.8rem 1rem;
            border-radius: 6px;
            margin: 1.5rem 0;
        }

        /* ===== Tables (optional improvement) ===== */

        .markdown table {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }


        .markdown th,
        .markdown td {
            border: 1px solid #e2e8f0;
            padding: 0.6rem;
        }

        .markdown th {
            background: #f1f5f9;
            font-weight: 600;
        }


        .markdown img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }


        @media (max-width: 576px) {

            .container {
                padding-left: 12px !important;
                padding-right: 12px !important;
            }

            .markdown pre {
                padding: 0.8rem;
                border-radius: 10px;
            }

            .markdown ul,
            .markdown ol {
                padding-left: 1.2rem;
            }

        }
    </style>


</head>

<body class="text-dark">
    <div class="container py-5 blog-container">

        @if ($blog->thumbnail_image)
            <div class="mb-4">
                <img src="{{ asset('storage/' . $blog->thumbnail_image) }}" class="w-100 rounded"
                    style="height:300px; object-fit:cover;">
            </div>
        @endif


        <h1 class="display-5 fw-semibold text-primary mb-2">
            {{ $blog->title }}
        </h1>

        <div class="text-muted mb-4 border-bottom pb-3">
            By <a href="{{ route('user.show', $blog->user) }}" class="text-decoration-none text-primary fw-medium">
                {{ $blog->user->username }}
            </a>
            • {{ $blog->published_at->format('M d, Y') }}
        </div>

        <article class="markdown">
            {!! $blog->html_content !!}
        </article>

        <hr class="my-5">

        <!-- COMMENTS SECTION -->
        <div class="mt-4">

            <h4 class="mb-4">Comments</h4>

            <!-- Comment Form -->
            <div class="card p-3 mb-4">

                <form method="POST" action="{{ route('comments.store', $blog) }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Your Name</label>
                        <input name="author_name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email (optional)</label>
                        <input name="author_email" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Comment</label>
                        <textarea name="text" class="form-control" required></textarea>
                    </div>

                    <button class="btn btn-primary">Post Comment</button>
                </form>

            </div>

            <!-- Comments List -->
            @forelse($blog->comments as $comment)
                <div class="border rounded p-3 mb-3">

                    <div class="fw-semibold">
                        {{ $comment->author_name }}
                    </div>

                    <div class="text-muted small mb-2">
                        {{ $comment->created_at->format('M d, Y') }}
                    </div>

                    <p class="mb-0">{{ $comment->text }}</p>

                </div>
            @empty
                <p class="text-muted">No comments yet.</p>
            @endforelse

        </div>


    </div>

</body>

</html>
