<x-layout-article>
    <div class="d-flex flex-column flex-md-row p-4 gap-4 py-md-5 align-items-center justify-content-center">
        <div class="container-sm">
            <div class="row g-5">
                <div class="col-md-10 col-lg-12">
                    <h4 class="mb-3">You're editing <b>{{ $article->name }}</b></h4>
                    <form class="needs-validation @if ($errors->any()) was-validated @endif"
                          novalidate
                          method="POST"
                          action="{{ route('articles.update', $article) }}"
                          enctype='multipart/form-data'
                    >
                        @csrf
                        @method('PATCH')
                        <div class="row g-3 mb-2">
                            <div class="col-sm-6">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" placeholder="Name" name="name" value="{{ old('name', $article->name) }}" required="">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row g-3 mb-2">
                            <div class="col-sm-6">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" class="form-control" id="image" name="image" value="{{ old('image', $article->image) }}">
                                @if($article->image)
                                    <img src="{{ asset($article->image) }}" alt="{{ $article->name }}" width="300" class="pb-2 pt-1">
                                @endif
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row g-3 mb-2">
                            <div class="col-md-6">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-select" id="category" required="" name="category_id">
                                    <option value="">Choose...</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                            @selected($article->category->id == old('category', $category->id))
                                        >
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row g-3 mb-2">
                            <div class="col-md-6">
                                <label for="tags" class="form-label">Tags</label>
                                <select class="form-select" id="tags" required="" multiple name="tags[]">
                                    <option value="">Choose...</option>
                                    @foreach($tags as $tag)
                                        <option value="{{ $tag->id }}"
                                            @selected(in_array($tag->id, $article->tags->pluck('id')->toArray() ?? []) )
                                        >
                                            {{ ucfirst($tag->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tags')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row g-3 mb-2">
                            <div class="col-sm-6">
                                <label for="full_text" class="form-label">Body</label>
                                <textarea class="form-control" id="full_text" required name="full_text">{!! old('full_text', $article->full_text) !!}</textarea>
                                @error('full_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row g-3 mb-2">
                            <div class="col-sm-6">
                                <hr class="my-4">
                                <button class="w-100 btn btn-primary btn-lg" type="submit">Update Article</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout-article>
