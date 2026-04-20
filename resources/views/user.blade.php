<!DOCTYPE html>
<html lang="en" prefix="og: https://ogp.me/ns#">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- ── Primary SEO ── --}}
    <title>{{ $user->name }} ({{ $user->username }}) — Amar Blog</title>
    <meta name="description"
        content="{{ $user->bio ? Str::limit($user->bio, 155) : $user->name . ' is a writer on Amar Blog. Read their latest articles and posts.' }}">
    <meta name="author" content="{{ $user->name }}">
    <link rel="canonical" href="{{ route('user.show', $user) }}">

    {{-- ── Open Graph ── --}}
    <meta property="og:type" content="profile">
    <meta property="og:title" content="{{ $user->name }} (@{{ $user->username }}) — Amar Blog">
    <meta property="og:description"
        content="{{ $user->bio ? Str::limit($user->bio, 155) : 'Read ' . $user->name . '\'s latest posts on Amar Blog.' }}">
    <meta property="og:url" content="{{ route('user.show', $user) }}">
    <meta property="og:site_name" content="Amar Blog">
    @if ($user->avatar_path)
        <meta property="og:image" content="{{ asset('storage/' . $user->avatar_path) }}">
        <meta property="og:image:alt" content="{{ $user->name }}'s profile photo">
    @endif
    <meta property="profile:username" content="{{ $user->username }}">

    {{-- ── Twitter Card ── --}}
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ $user->name }} (@{{ $user->username }}) — Amar Blog">
    <meta name="twitter:description"
        content="{{ $user->bio ? Str::limit($user->bio, 125) : 'Read ' . $user->name . '\'s posts on Amar Blog.' }}">
    @if ($user->avatar_path)
        <meta name="twitter:image" content="{{ asset('storage/' . $user->avatar_path) }}">
    @endif

    {{-- ── JSON-LD Structured Data ── --}}
   @php
$userJsonLd = [
    "@context" => "https://schema.org",
    "@type" => "Person",
    "name" => $user->name,
    "alternateName" => $user->username,
    "url" => route('user.show', $user),
    "description" => $user->bio 
        ? Str::limit($user->bio, 155) 
        : $user->name . ' is a writer on Amar Blog.',
    "image" => $user->avatar_path 
        ? asset('storage/' . $user->avatar_path) 
        : asset('default-avatar.png'), // fallback
    "mainEntityOfPage" => [
        "@type" => "WebPage",
        "@id" => route('user.show', $user),
    ],
];
@endphp

<script type="application/ld+json">
{!! json_encode($userJsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        /* ── Base ── */
        body {
            font-family: Arial,sans-serif;
            background: #f8fafc;
            color: #1e293b;
        }

        .page-wrapper { max-width: 980px; }

        /* ── Section label ── */
        .section-label {
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: .07em;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 1rem;
        }

        /* ── Blog list card ── */
        .blog-card {
            display: flex;
            align-items: center;
            gap: 16px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 16px 18px;
            margin-bottom: 10px;
            transition: border-color .2s, box-shadow .2s;
            text-decoration: none;
            color: inherit;
        }

        .blog-card:hover {
            border-color: #93c5fd;
            box-shadow: 0 2px 14px rgba(59,130,246,.07);
            color: inherit;
        }

        .blog-card-info { flex: 1; min-width: 0; }

        .blog-card-title {
            font-size: .97rem;
            font-weight: 700;
            color: #0f172a;
            display: block;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .blog-card:hover .blog-card-title { color: var(--bs-primary); }

        .blog-card-date { font-size: .78rem; color: #94a3b8; }

        .blog-thumb {
            width: 72px;
            height: 56px;
            border-radius: 8px;
            overflow: hidden;
            background: #f1f5f9;
            flex-shrink: 0;
        }

        .blog-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* ── Profile sidebar card ── */
        .profile-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 28px 22px;
        }

        @media (min-width: 992px) {
            .profile-card { position: sticky; top: 24px; }
        }

        /* Avatar */
        .avatar-wrap { margin-bottom: 14px; }

        .avatar-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #e0e7ff;
        }

        .avatar-initials {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #eff6ff;
            border: 3px solid #e0e7ff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--bs-primary);
            text-transform: uppercase;
        }

        .profile-name {
            font-size: 1.1rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 2px;
        }

        .profile-handle {
            font-size: .85rem;
            color: var(--bs-primary);
            font-weight: 600;
            margin-bottom: 12px;
        }

        /* Stats row */
        .stats-row { display: flex; gap: 8px; margin-bottom: 14px; }

        .stat-pill {
            flex: 1;
            background: #f8fafc;
            border-radius: 10px;
            padding: 9px 10px;
            text-align: center;
        }

        .stat-num {
            font-size: 1rem;
            font-weight: 800;
            color: #0f172a;
            display: block;
        }

        .stat-label {
            font-size: .7rem;
            color: #94a3b8;
            margin-top: 1px;
            display: block;
        }

        .profile-bio {
            font-size: .875rem;
            color: #475569;
            line-height: 1.65;
            margin-bottom: 0;
        }

        .profile-divider {
            border: none;
            border-top: 1px solid #e2e8f0;
            margin: 16px 0;
        }

        .profile-email {
            font-size: .82rem;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 12px;
        }

        .profile-email svg { flex-shrink: 0; }

        /* Social link button */
        .social-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: .85rem;
            font-weight: 600;
            color: #0f172a;
            text-decoration: none;
            padding: 9px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            margin-bottom: 8px;
            transition: border-color .15s, background .15s;
        }

        .social-btn:hover {
            border-color: #93c5fd;
            background: #f8fafc;
            color: #0f172a;
        }

        .social-btn svg { flex-shrink: 0; }
        .social-btn-arrow { margin-left: auto; color: #94a3b8; font-size: .8rem; }

        /* ── Empty state ── */
        .empty-state {
            background: #fff;
            border: 1px dashed #cbd5e1;
            border-radius: 14px;
            padding: 2.5rem;
            text-align: center;
            color: #94a3b8;
            font-size: .9rem;
        }

        /* ── Mobile ── */
        @media (max-width: 991px) {
            .profile-card { margin-top: 2rem; }
        }

        @media (max-width: 576px) {
            .blog-thumb { display: none; }
        }
    </style>
</head>

<body>
    <div class="container py-4 py-md-5 page-wrapper">
        <div class="row g-4 g-lg-5">

            {{-- ── LEFT: Blog list ── --}}
            <div class="col-lg-8">

                <p class="section-label">{{ $user->username }}'s posts</p>

                @forelse($blogs as $blog)
                    <a href="{{ route('blog.show', $blog) }}" class="blog-card" aria-label="{{ $blog->title }}">

                        <div class="blog-card-info">
                            <span class="blog-card-title">{{ $blog->title }}</span>
                            <span class="blog-card-date">{{ $blog->published_at->format('M d, Y') }}</span>
                        </div>

                        <div class="blog-thumb">
                            @if ($blog->thumbnail_image)
                                <img src="{{ asset('storage/' . $blog->thumbnail_image) }}"
                                     alt="{{ $blog->title }}">
                            @endif
                        </div>

                    </a>
                @empty
                    <div class="empty-state">No published posts yet.</div>
                @endforelse

            </div>

            {{-- ── RIGHT: Profile sidebar ── --}}
            <div class="col-lg-4">
                <div class="profile-card">

                    {{-- Avatar --}}
                    <div class="avatar-wrap">
                        @if ($user->avatar_path)
                            <img src="{{ asset('storage/' . $user->avatar_path) }}"
                                 alt="{{ $user->name }}"
                                 class="avatar-img">
                        @else
                            <div class="avatar-initials" aria-hidden="true">
                                {{ mb_substr($user->name, 0, 2) }}
                            </div>
                        @endif
                    </div>

                    <div class="profile-name">{{ $user->name }}</div>
                    <div class="profile-handle">{{ $user->username }}</div>

                    {{-- Stats --}}
                    <div class="stats-row">
                        <div class="stat-pill">
                            <span class="stat-num">{{ $blogs->count() }}</span>
                            <span class="stat-label">Posts</span>
                        </div>
                        <div class="stat-pill">
                            <span class="stat-num">{{ $user->created_at->format("M 'y") }}</span>
                            <span class="stat-label">Joined</span>
                        </div>
                    </div>

                    {{-- Bio --}}
                    @if ($user->bio)
                        <p class="profile-bio">{{ $user->bio }}</p>
                    @endif

                    <hr class="profile-divider">

                    {{-- Email --}}
                    @if ($user->email)
                        <div class="profile-email">
                            <i class="bi bi-envelope" style="font-size:14px; color:#94a3b8;"></i>
                            {{ $user->email }}
                        </div>
                    @endif

                    {{-- Social links --}}
                    @if ($user->github)
                        <a href="{{ $user->github }}" target="_blank" rel="noopener noreferrer"
                           class="social-btn">
                            <i class="bi bi-github" style="font-size:18px;"></i>
                            GitHub
                            <span class="social-btn-arrow">→</span>
                        </a>
                    @endif

                    @if ($user->linkedin)
                        <a href="{{ $user->linkedin }}" target="_blank" rel="noopener noreferrer"
                           class="social-btn">
                            <i class="bi bi-linkedin" style="font-size:18px;"></i>
                            LinkedIn
                            <span class="social-btn-arrow">→</span>
                        </a>
                    @endif

                </div>
            </div>

        </div>
    </div>
</body>

</html>
