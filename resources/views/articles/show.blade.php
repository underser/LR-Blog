<x-layout-article>
    <div class="d-flex flex-column flex-md-row p-4 gap-4 py-md-5 align-items-center justify-content-center">
        <article class="blog-post container-sm">
            <h2 class="display-5 link-body-emphasis mb-1">{{ $article->name }}</h2>
            <p class="blog-post-meta">{{ $article->created_at->format('M d Y') }} <small>{{ $article->user->name }}</small></p>
            @if($article->image)
                <img src="{{ asset($article->image) }}" alt="{{ $article->name }}" class="img-fluid">
            @endif
            <article>
                {!! $article->full_text !!}
            </article>
        </article>
    </div>
    @auth
        <div class="d-flex flex-column flex-md-row p-4 gap-4 py-md-5 align-items-left justify-content-left">
            <div class="container-sm">
                <a href="{{ route('articles.edit', $article) }}" class="btn btn-primary rounded-pill px-3">Edit</a>
                <form action="{{ route('articles.destroy', $article) }}" class="block" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-primary rounded-pill px-3">Delete</button>
                </form>
            </div>
        </div>
    @endauth
</x-layout-article>
