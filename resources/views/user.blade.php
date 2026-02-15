<!DOCTYPE html>
<html>

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>{{ $user->username }}</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
    background: #f8fafc;
}

/* blog item separator */
.blog-item {
    padding: 18px 0;
    border-bottom: 1px solid #e5e7eb;
}

/* remove last border */
.blog-item:last-child {
    border-bottom: none;
}

/* blog hover like medium */
.blog-item a:hover {
    opacity: 0.7;
}

/* profile section divider */
.profile-section {
    border-left: 1px solid #e5e7eb;
}

/* responsive — remove divider on mobile */
@media (max-width: 992px) {
    .profile-section {
        border-left: none;
        border-top: 1px solid #e5e7eb;
        padding-top: 24px;
        margin-top: 24px;
    }
}
</style>

</head>

<body>

<div class="container py-4">

<div class="row g-5">

    <!-- LEFT → BLOGS -->
    <div class="col-lg-8">

        <h3 class="text-primary mb-4">
            {{ $user->username }}'s Blogs
        </h3>

        @forelse($blogs as $blog)

        <div class="blog-item">

            <h5 class="mb-1 fw-semibold">
                <a href="{{ route('blog.show', $blog) }}"
                   class="text-decoration-none text-dark">
                   {{ $blog->title }}
                </a>
            </h5>

            <small class="text-muted">
                {{ $blog->published_at->format('M d, Y') }}
            </small>

        </div>

        @empty
            <p class="text-muted">No published blogs yet.</p>
        @endforelse

    </div>


    <!-- RIGHT → PROFILE -->
    <div class="col-lg-4 profile-section">

        <div class="ps-lg-4">

            <h4 class="text-primary mb-2">
                {{ $user->name }}
            </h4>

            <p class="text-muted mb-3">
                @{{ $user->username }}
            </p>

            @if($user->bio)
            <p class="mb-3">
                {{ $user->bio }}
            </p>
            @endif

            @if($user->email)
            <p class="small text-muted mb-2">
                {{ $user->email }}
            </p>
            @endif

            <div class="mt-3">

                @if($user->github)
                <a href="{{ $user->github }}" target="_blank"
                   class="d-block text-decoration-none mb-2">
                   GitHub
                </a>
                @endif

                @if($user->linkedin)
                <a href="{{ $user->linkedin }}" target="_blank"
                   class="d-block text-decoration-none">
                   LinkedIn
                </a>
                @endif

            </div>

        </div>

    </div>

</div>


</div>

</body>
</html>
