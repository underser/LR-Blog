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
    public function store(Store $request): RedirectResponse
    {
        Category::factory()->create($request->validated());

        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Update $request, Category $category)
    {
        $category->update($request->validated());

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return back();
    }
}
