<?php

namespace App\Http\Controllers;

use App\Http\Requests\Categories\Store;
use App\Http\Requests\Categories\Update;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;

class CategoryController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Store $request, Category $category): RedirectResponse
    {
        $this->authorize('create', $category);
        $category::factory()->create($request->validated());

        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Update $request, Category $category)
    {
        $this->authorize('update', $category);
        $category->update($request->validated());

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        $this->authorize('delete', $category);
        $category->delete();

        return back();
    }
}
