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
    public function store(Store $request, ?Article $article): RedirectResponse
    {
        $tag = Tag::factory()->create($request->validated());

        $article?->tags()->attach($tag);

        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Update $request, Tag $tag): RedirectResponse
    {
        $tag->update($request->validated());

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag): RedirectResponse
    {
        $tag->delete();

        return back();
    }
}
