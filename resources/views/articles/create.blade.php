<x-layout-article>
    <div class="d-flex flex-column flex-md-row p-4 gap-4 py-md-5 align-items-center justify-content-center">
        <div class="container-sm">
            <div class="row g-5">
                <div class="col-md-10 col-lg-12">
                    <h4 class="mb-3">Create new article</h4>
                    <form class="needs-validation"
                          novalidate
                          method="post"
                          action="{{ route('articles.store') }}"
                          enctype='multipart/form-data'
                    >
                        @csrf
                        <div class="row g-3 mb-2">
                            <div class="col-sm-6">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" placeholder="Name" required="">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row g-3 mb-2">
                            <div class="col-sm-6">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" class="form-control" id="image" value="" required="">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row g-3 mb-2">
                            <div class="col-md-6">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-select" id="category" required="">
                                    <option value="">Choose...</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row g-3 mb-2">
                            <div class="col-md-6">
                                <label for="tags" class="form-label">Tags</label>
                                <select class="form-select" id="tags" required="" multiple>
                                    <option value="">Choose...</option>
                                    @foreach($tags as $tag)
                                        <option value="{{ $tag->id }}">
                                            {{ ucfirst($tag->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tag')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row g-3 mb-2">
                            <div class="col-sm-6">
                                <label for="full_text" class="form-label">Body</label>
                                <textarea class="form-control" id="full_text" name="full_text"></textarea>
                            </div>
                        </div>
                        <div class="row g-3 mb-2">
                            <div class="col-sm-6">
                                <hr class="my-4">
                                <button class="w-100 btn btn-primary btn-lg" type="submit">Publish Article</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout-article>
