<div class="my-2 d-flex flex-wrap">
    <h2 class="w-100 mb-3">Category Management</h2>
    @foreach($categories as $category)
        <div class="btn btn-primary me-2 mb-2 position-relative">
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
                <span class="position-absolute top-0 start-100 translate-middle px-1 bg-danger border border-light rounded-circle">
                    <form action="{{ route('categories.destroy', $category) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <x-forms.button-close/>
                    </form>
                </span>
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
