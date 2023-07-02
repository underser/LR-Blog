<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tags\Store;
use App\Http\Requests\Tags\Update;
use App\Models\Article;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;

class TagsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Store $request, Tag $tag, ?Article $article = null): RedirectResponse
    {
        $this->authorize('create', $tag);
        $tag = $tag::factory()->create($request->validated());

        $article?->tags()->attach($tag);

        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Update $request, Tag $tag): RedirectResponse
    {
        $this->authorize('update', $tag);
        $tag->update($request->validated());

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag): RedirectResponse
    {
        $this->authorize('delete', $tag);
        $tag->delete();

        return back();
    }
}
