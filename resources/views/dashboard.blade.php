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
            background: #f8fafc;
        }

        .admin-wrapper {
            max-width: 950px;
        }

        .card-custom {
            border-radius: 16px;
            border: none;
            box-shadow: 0 8px 24px rgba(0,0,0,0.06);
        }

        .section-title {
            font-weight: 600;
            color: #1e293b;
        }

        .blog-card {
            border-radius: 14px;
            border: none;
            box-shadow: 0 4px 14px rgba(0,0,0,0.05);
        }

        .avatar-preview {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e2e8f0;
        }
    </style>
</head>

<body>

<div class="container py-5 admin-wrapper">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2 class="text-primary fw-semibold">Admin Dashboard</h2>

        <a href="{{ route('blog.create') }}" class="btn btn-primary px-4">
            + New Blog
        </a>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif


    {{-- PROFILE SECTION --}}
    <div class="card card-custom p-4 mb-5">
        <h4 class="section-title mb-4">Profile Settings</h4>

        <form method="POST" action="{{ route('admin.profile.update',auth()->user()) }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="row g-4">

                {{-- Avatar --}}
                <div class="col-12 d-flex align-items-center gap-3">
                    @if(auth()->user()->avatar_path)
                        <img src="{{ asset('storage/' . auth()->user()->avatar_path) }}"
                             class="avatar-preview">
                    @else
                        <div class="avatar-preview bg-light d-flex align-items-center justify-content-center">
                            <span class="text-muted small">No Image</span>
                        </div>
                    @endif

                    <div class="flex-grow-1">
                        <label class="form-label">Change Avatar</label>
                        <input type="file" name="avatar" class="form-control">
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Name</label>
                    <input type="text" name="name"
                           value="{{ auth()->user()->name }}"
                           class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Username</label>
                    <input type="text" name="username"
                           value="{{ auth()->user()->username }}"
                           class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email"
                           value="{{ auth()->user()->email }}"
                           class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">New Password</label>
                    <input type="password" name="password"
                           placeholder="Leave blank to keep current password"
                           class="form-control">
                </div>

                <div class="col-12">
                    <label class="form-label">Bio</label>
                    <textarea name="bio" rows="3"
                              class="form-control">{{ auth()->user()->bio }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">GitHub</label>
                    <input type="url" name="github"
                           value="{{ auth()->user()->github }}"
                           class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">LinkedIn</label>
                    <input type="url" name="linkedin"
                           value="{{ auth()->user()->linkedin }}"
                           class="form-control">
                </div>

            </div>

            <button class="btn btn-primary mt-4 px-4">
                Save Changes
            </button>
        </form>
    </div>



    {{-- BLOG SECTION --}}
    <h4 class="section-title mb-4">Your Blogs</h4>

    @forelse($blogs as $blog)
        <div class="card blog-card p-4 mb-3">

            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h5 class="mb-1">{{ $blog->title }}</h5>
                    <small class="text-muted">
                        {{ $blog->published_at ? 'Published' : 'Draft' }}
                    </small>
                </div>
            </div>

            <div class="mt-3 d-flex gap-2 flex-wrap">

                <form method="POST" action="{{ route('blog.update', $blog) }}">
                    @csrf
                    @method('PATCH')

                    @if (!$blog->published_at)
                        <button type="submit" name="action" value="publish"
                                class="btn btn-sm btn-primary">
                            Publish
                        </button>
                    @else
                        <button type="submit" name="action" value="unpublish"
                                class="btn btn-sm btn-outline-danger">
                            Unpublish
                        </button>
                    @endif
                </form>

                <a href="{{ route('blog.show', $blog) }}"
                   class="btn btn-sm btn-outline-secondary">
                    View
                </a>

                <a href="{{ route('blog.edit', $blog) }}"
                   class="btn btn-sm btn-outline-primary">
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
