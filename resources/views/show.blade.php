<!DOCTYPE html>
<html lang="en" prefix="og: https://ogp.me/ns#">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

   
    <title>{{ $blog->title }} — Amar Blog</title>
    <meta name="description"
        content="{{ Str::limit(strip_tags($blog->html_content), 155) }}">
    <meta name="author" content="{{ $blog->user->name }}">
    <link rel="canonical" href="{{ route('blog.show', $blog) }}">
    <meta name="robots" content="index, follow">

    {{-- ── Open Graph ── --}}
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $blog->title }}">
    <meta property="og:description" content="{{ Str::limit(strip_tags($blog->html_content), 155) }}">
    <meta property="og:url" content="{{ route('blog.show', $blog) }}">
    <meta property="og:site_name" content="Amar Blog">
    @if ($blog->thumbnail_image)
        <meta property="og:image" content="{{ asset('storage/' . $blog->thumbnail_image) }}">
        <meta property="og:image:alt" content="{{ $blog->title }}">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
    @endif
    <meta property="article:published_time" content="{{ $blog->published_at->toIso8601String() }}">
    <meta property="article:author" content="{{ route('user.show', $blog->user) }}">

    {{-- ── Twitter Card ── --}}
    <meta name="twitter:card" content="{{ $blog->thumbnail_image ? 'summary_large_image' : 'summary' }}">
    <meta name="twitter:title" content="{{ $blog->title }}">
    <meta name="twitter:description" content="{{ Str::limit(strip_tags($blog->html_content), 125) }}">
    @if ($blog->thumbnail_image)
        <meta name="twitter:image" content="{{ asset('storage/' . $blog->thumbnail_image) }}">
    @endif

    {{-- ── JSON-LD Structured Data ── --}}
    
@php
$jsonLd = [
    "@context" => "https://schema.org",
    "@type" => "BlogPosting",
    "headline" => $blog->title,
    "description" => Str::limit(strip_tags($blog->html_content), 155),
    "image" => $blog->thumbnail_image ? asset('storage/' . $blog->thumbnail_image) : null,
    "author" => [
        "@type" => "Person",
        "name" => $blog->user->name,
        "url" => route('user.show', $blog->user),
    ],
    "publisher" => [
        "@type" => "Organization",
        "name" => "Amar Blog",
        "logo" => [
            "@type" => "ImageObject",
            "url" => asset('logo.png'), // ⚠️ add a real logo
        ],
    ],
    "datePublished" => optional($blog->published_at)->toIso8601String(),
    "dateModified" => optional($blog->updated_at)->toIso8601String(),
    "mainEntityOfPage" => [
        "@type" => "WebPage",
        "@id" => route('blog.show', $blog),
    ],
    "wordCount" => str_word_count(strip_tags($blog->html_content)),
];
@endphp

<script type="application/ld+json">
{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>

<script type="application/ld+json">
{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Highlight.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.11.1/styles/github.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.11.1/highlight.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.11.1/languages/go.min.js"></script>

    <style>
        html, body {
            font-family: Arial,sans-serif;
            background: #f8fafc;
            color: #1e293b;
        }

        .blog-container { max-width: 760px; width: 100%; }

        .hero-thumb {
            width: 100%;
            height: 320px;
            object-fit: cover;
            border-radius: 16px;
            display: block;
            margin-bottom: 2rem;
        }

        .article-header {
            margin-bottom: 2rem;
            padding-bottom: 1.25rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .article-title {
            font-size: clamp(1.6rem, 4vw, 2.4rem);
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
            line-height: 1.25;
            margin-bottom: .75rem;
        }

        .article-meta {
            font-size: .87rem;
            color: #94a3b8;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }

        .meta-dot { width: 3px; height: 3px; border-radius: 50%; background: #cbd5e1; display: inline-block; }
        .meta-author { font-weight: 600; color: var(--bs-primary); text-decoration: none; }
        .meta-author:hover { text-decoration: underline; }

        /* Markdown */
        .markdown { font-size: clamp(.95rem, 2.5vw, 1.05rem); line-height: 1.8; color: #334155; }
        .markdown h1 { font-size: clamp(1.4rem,3.5vw,1.9rem); font-weight: 700; color: #0f172a; margin: 2rem 0 .75rem; }
        .markdown h2 { font-size: clamp(1.2rem,3vw,1.55rem); font-weight: 700; color: #0f172a; margin: 1.75rem 0 .65rem; }
        .markdown h3 { font-size: clamp(1.05rem,2.5vw,1.25rem); font-weight: 600; color: #0f172a; margin: 1.5rem 0 .5rem; }
        .markdown p { margin-bottom: 1.1rem; }
        .markdown a { color: #2563eb; text-decoration: none; }
        .markdown a:hover { text-decoration: underline; color: #1d4ed8; }
        .markdown ul, .markdown ol { margin: 1rem 0; padding-left: 1.6rem; }
        .markdown li { margin-bottom: .45rem; }
        .markdown code { background: #eef2ff; color: #4338ca; padding: .15rem .4rem; border-radius: 6px; font-size: .88em; font-family: "SF Mono","Fira Code",monospace; }
        .markdown pre { background: #f6f8fa; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.25rem 1.4rem; overflow-x: auto; margin: 1.5rem 0; -webkit-overflow-scrolling: touch; }
        .markdown pre code { background: transparent; color: inherit; padding: 0; font-size: .88rem; }
        .markdown blockquote { border-left: 3px solid var(--bs-primary); border-radius: 0 8px 8px 0; margin: 1.5rem 0; padding: .75rem 1.1rem; background: #eff6ff; color: #475569; font-style: italic; }
        .markdown table { display: block; overflow-x: auto; white-space: nowrap; border-collapse: collapse; }
        .markdown th, .markdown td { border: 1px solid #e2e8f0; padding: .55rem .8rem; }
        .markdown th { background: #f1f5f9; font-weight: 600; }
        .markdown img { max-width: 100%; height: auto; border-radius: 10px; }

        .section-divider { border: none; border-top: 1px solid #e2e8f0; margin: 3rem 0; }

        /* Comments */
        .comments-header { display: flex; align-items: center; gap: 10px; margin-bottom: 1.5rem; }
        .comments-title { font-size: 1.1rem; font-weight: 800; color: #0f172a; margin: 0; }
        .comments-count { font-size: .75rem; font-weight: 700; background: #eff6ff; color: #1d4ed8; border-radius: 20px; padding: 2px 10px; }

        /* Form */
        .comment-form-wrap {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 2rem;
        }

        .comment-form-label-group {
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 1rem;
        }

        .comment-form-wrap .form-label { font-size: .82rem; font-weight: 600; color: #475569; margin-bottom: .3rem; }

        .comment-form-wrap .form-control {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-size: .9rem;
            background: #f8fafc;
            color: #0f172a;
            transition: border-color .15s, box-shadow .15s, background .15s;
        }

        .comment-form-wrap .form-control:focus {
            border-color: #93c5fd;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(59,130,246,.1);
        }

        .comment-form-wrap textarea.form-control { resize: vertical; min-height: 100px; }

        .submit-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: .875rem;
            font-weight: 700;
            background: var(--bs-primary);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 9px 20px;
            cursor: pointer;
            transition: opacity .15s;
        }

        .submit-btn:hover { opacity: .88; }

        /* Comment bubble */
        .comment-item { display: flex; gap: 14px; margin-bottom: 14px; }

        .comment-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #eff6ff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .8rem;
            font-weight: 800;
            color: var(--bs-primary);
            flex-shrink: 0;
            text-transform: uppercase;
        }

        .comment-bubble {
            flex: 1;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 0 12px 12px 12px;
            padding: 12px 16px;
        }

        .comment-meta { display: flex; align-items: baseline; gap: 8px; margin-bottom: 6px; }
        .comment-author-name { font-size: .88rem; font-weight: 700; color: #0f172a; }
        .comment-date { font-size: .75rem; color: #94a3b8; }
        .comment-text { font-size: .875rem; color: #475569; line-height: 1.65; margin: 0; }

        .no-comments { background: #fff; border: 1px dashed #cbd5e1; border-radius: 12px; padding: 2rem; text-align: center; color: #94a3b8; font-size: .9rem; }

        @media (max-width: 576px) {
            .hero-thumb { height: 200px; border-radius: 12px; }
            .markdown pre { padding: .85rem 1rem; border-radius: 10px; }
            .comment-form-wrap { padding: 16px; }
        }
    </style>
</head>

<body>
    <div class="container py-5 blog-container">

        @if ($blog->thumbnail_image)
            <img src="{{ asset('storage/' . $blog->thumbnail_image) }}"
                 class="hero-thumb"
                 alt="{{ $blog->title }}">
        @endif

        <header class="article-header">
            <h1 class="article-title">{{ $blog->title }}</h1>
            <div class="article-meta">
                <a href="{{ route('user.show', $blog->user) }}" class="meta-author" rel="author">
                    {{ $blog->user->username }}
                </a>
                <span class="meta-dot"></span>
                <time datetime="{{ $blog->published_at->toDateString() }}">
                    {{ $blog->published_at->format('M d, Y') }}
                </time>
            </div>
        </header>

        <article class="markdown" itemprop="articleBody">
            {!! $blog->html_content !!}
        </article>

        <hr class="section-divider">

        <section aria-label="Comments">

            <div class="comments-header">
                <h2 class="comments-title">Comments</h2>
                <span class="comments-count">{{ $blog->comments->count() }}</span>
            </div>

            {{-- Comment form --}}
            <div class="comment-form-wrap">
                <p class="comment-form-label-group">Leave a comment</p>

                <form method="POST" action="{{ route('comments.store', $blog) }}">
                    @csrf

                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label class="form-label" for="author_name">Name</label>
                            <input id="author_name" name="author_name" class="form-control"
                                   placeholder="Your name" autocomplete="name" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="author_email">
                                Email <span style="color:#94a3b8;font-weight:400;">(optional)</span>
                            </label>
                            <input id="author_email" name="author_email" type="email"
                                   class="form-control" placeholder="you@example.com"
                                   autocomplete="email">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="comment_text">Comment</label>
                        <textarea id="comment_text" name="text" class="form-control"
                                  placeholder="Share your thoughts…" required></textarea>
                    </div>

                    <button type="submit" class="submit-btn">
                        <i class="bi bi-send-fill" style="font-size:13px;"></i>
                        Post Comment
                    </button>
                </form>
            </div>

            {{-- Comments list --}}
            @forelse($blog->comments as $comment)
                <div class="comment-item" itemscope itemtype="https://schema.org/Comment">
                    <div class="comment-avatar" aria-hidden="true">
                        {{ mb_substr($comment->author_name, 0, 2) }}
                    </div>
                    <div class="comment-bubble">
                        <div class="comment-meta">
                            <span class="comment-author-name" itemprop="author">{{ $comment->author_name }}</span>
                            <time class="comment-date"
                                  datetime="{{ $comment->created_at->toDateString() }}"
                                  itemprop="dateCreated">
                                {{ $comment->created_at->format('M d, Y') }}
                            </time>
                        </div>
                        <p class="comment-text" itemprop="text">{{ $comment->text }}</p>
                    </div>
                </div>
            @empty
                <div class="no-comments">No comments yet. Be the first to share your thoughts!</div>
            @endforelse

        </section>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll('.markdown pre code').forEach(el => {
                hljs.highlightElement(el);
            });
        });
    </script>
</body>

</html>
