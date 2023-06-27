<x-layout-article>
    <div class="d-flex flex-column flex-md-row p-4 gap-4 py-md-5 align-items-center justify-content-center">
        <div class="list-group">
            @foreach($articles as $article)
                <a href="{{ route('articles.show', $article->id) }}" class="list-group-item list-group-item-action d-flex gap-3 py-3" aria-current="true">
                    <img src="https://github.com/twbs.png" alt="twbs" width="32" height="32" class="rounded-circle flex-shrink-0">
                    <div class="d-flex gap-2 w-100 justify-content-between">
                        <div>
                            <h6 class="mb-0">{{ $article->name }}</h6>
                            <p class="mb-0 opacity-75">{{ \Illuminate\Support\Str::limit($article->full_text, ) }}</p>
                        </div>
                        <small class="opacity-50 text-nowrap">{{ $article->created_at->diffForHumans() }}</small>
                    </div>
                </a>
            @endforeach
            {{ $articles->links() }}
        </div>
    </div>
</x-layout-article>
