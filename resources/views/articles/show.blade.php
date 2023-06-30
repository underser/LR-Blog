<x-layout-article>
    <div class="d-flex flex-column flex-md-row p-4 gap-4 py-md-5 align-items-center justify-content-center">
        <article class="blog-post container-sm">
            <h2 class="display-5 link-body-emphasis mb-1">{{ $article->name }}</h2>
            <p class="blog-post-meta">{{ $article->created_at->format('M d Y') }} <small>{{ $article->user->name }}</small></p>
            <div class="my-2 d-flex">
                @foreach($article->tags as $tag)
                    <div class="position-relative me-3 @if(!auth()->user()?->can('delete', $tag)) btn btn-info @endif">
                        @can('update', $tag)
                            <form action="{{ route('tags.update', $tag) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <x-forms.input placeholder="Name" name="name" value="{{ old('name', $tag->name) }}"/>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </form>
                        @else
                            {{ $tag->name }}
                        @endcan

                        @can('delete', $tag)
                            <div class="position-absolute top-0 start-100 px-1 py-1 translate-middle bg-light rounded-pill rounded-circle">
                                <form action="{{ route('tags.destroy', $tag) }}" method="POST" style="line-height: 0">
                                    @csrf
                                    @method('DELETE')
                                    <x-forms.button-close/>
                                </form>
                            </div>
                        @endcan
                    </div>
                @endforeach
                @can('create', \App\Models\Tag::class)
                    <form action="{{ route('tags.store', ['article' => $article]) }}" method="POST">
                        @csrf
                        <x-forms.input placeholder="New Tag" name="name" value="{{ old('name') }}"/>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </form>
                @endcan
            </div>
            @if($article->image)
                <img src="{{ asset($article->getImageUrl()) }}" alt="{{ $article->name }}" class="img-fluid">
            @endif
            <article>
                {!! $article->full_text !!}
            </article>
        </article>
    </div>
    @auth
        <div class="d-flex flex-column flex-md-row p-4 gap-4 py-md-5 align-items-left justify-content-left">
            <div class="container-sm d-flex">
                <a href="{{ route('articles.edit', $article) }}" class="btn btn-primary rounded-pill px-3">Edit</a>
                <form action="{{ route('articles.destroy', $article) }}" class="mx-2" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-primary rounded-pill px-3">Delete</button>
                </form>
            </div>
        </div>
    @endauth
</x-layout-article>
