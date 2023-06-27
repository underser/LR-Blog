<x-layout-article>
    <div class="d-flex flex-column flex-md-row p-4 gap-4 py-md-5 align-items-center justify-content-center">
        <article class="blog-post container-sm">
            <h2 class="display-5 link-body-emphasis mb-1">{{ $article->name }}</h2>
            <p class="blog-post-meta">{{ $article->created_at->format('M d Y') }} <small>{{ $article->user->name }}</small></p>
            <article>
                {!! $article->full_text !!}
            </article>
        </article>
    </div>
    <div class="d-flex flex-column flex-md-row p-4 gap-4 py-md-5 align-items-left justify-content-left">
        <div class="container-sm">
            <a href="{{ route('articles.edit', $article) }}" class="btn btn-primary rounded-pill px-3">Edit</a>
            <a href="{{ route('articles.destroy', $article) }}" class="btn btn-primary rounded-pill px-3">Delete</a>
        </div>
    </div>
</x-layout-article>
