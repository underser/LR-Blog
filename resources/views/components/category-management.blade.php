<div class="my-2 d-flex flex-wrap">
    <h2 class="w-100 mb-3">Category Management</h2>
    @foreach($categories as $category)
        <div class="me-3 mb-3 position-relative btn btn-outline-secondary">
            @can('update', $category)
                <form action="{{ route('categories.update', $category) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <x-forms.input placeholder="Name" name="name" value="{{ old('name', $category->name) }}"/>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </form>
            @endcan

            @can('delete', $category)
                <small>Articles assigned: {{ $category->articles_count }}</small>
                <div class="position-absolute top-0 start-100 px-1 py-1 translate-middle bg-light rounded-pill rounded-circle">
                    <form action="{{ route('categories.destroy', $category) }}" method="POST" style="line-height: 0">
                        @csrf
                        @method('DELETE')
                        <x-forms.button-close/>
                    </form>
                </div>
            @endcan
        </div>
    @endforeach
    @can('create', \App\Models\Category::class)
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <x-forms.input placeholder="New Category" name="name" value="{{ old('name') }}"/>
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </form>
    @endcan
</div>
