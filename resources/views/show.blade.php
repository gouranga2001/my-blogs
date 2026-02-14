<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>{{ $blog->title }}</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-light.min.css"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll('markdown code ').forEach(el => {
        hljs.highlightElement(el);
    });
});
</script>

<style>

/* ===== Base Layout ===== */

html, body {
    font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
    background-color: #f8fafc; /* soft neutral white */
    color: #1e293b; /* slate-800 */
}

.blog-container {
    max-width: 760px;
}

/* ===== Title & Meta ===== */

h1.display-5 {
    color: #0f172a; /* darker slate */
}

.text-muted {
    color: #64748b !important; /* slate-500 */
}

/* ===== Markdown Typography ===== */

.markdown h1,
.markdown h2,
.markdown h3 {
    margin-top: 2.2rem;
    margin-bottom: 1rem;
    font-weight: 600;
    color: #0f172a;
}

.markdown h2 {
    border-bottom: 1px solid #e2e8f0;
    padding-bottom: 0.4rem;
}

.markdown p {
    margin-bottom: 1.1rem;
    line-height: 1.75;
    color: #334155; /* slate-700 */
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
    color: #2563eb; /* modern blue */
    text-decoration: none;
}

.markdown a:hover {
    text-decoration: underline;
    color: #1d4ed8;
}

/* ===== Code Styling ===== */

/* Inline code */
.markdown code {
    background: #eef2ff; /* soft indigo tint */
    color: #4338ca;
    padding: 0.2rem 0.4rem;
    border-radius: 6px;
    font-size: 0.9rem;
}

/* Code block */
.markdown pre {
    background: #0f172a; /* deep slate */
    color: #e2e8f0;
    padding: 1.2rem;
    border-radius: 12px;
    overflow-x: auto;
    margin: 1.5rem 0;
    box-shadow: 0 4px 16px rgba(0,0,0,0.05);
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
    background: #f1f5f9;
    padding: 0.8rem 1rem;
    border-radius: 6px;
    margin: 1.5rem 0;
}

/* ===== Tables (optional improvement) ===== */

.markdown table {
    width: 100%;
    border-collapse: collapse;
    margin: 1.5rem 0;
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

</style>


</head>

<body class="text-dark">

<div class="container py-5 blog-container">

    <h1 class="display-5 fw-semibold text-primary mb-2">
        {{ $blog->title }}
    </h1>

    <div class="text-muted mb-4 border-bottom pb-3">
        By {{ $blog->user->username }}
        • {{ $blog->published_at }}
    </div>

    <article class="markdown">
        {!! $blog->html_content !!}
    </article>

</div>

</body>
</html>
