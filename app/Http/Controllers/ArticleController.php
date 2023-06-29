<?php

namespace App\Http\Controllers;

use App\Http\Requests\Articles\Store;
use App\Http\Requests\Articles\Update;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Article::class, request: 'articles');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('articles.index', [
            'articles' => Article::query()->with(['user', 'category', 'tags'])->cursorPaginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('articles.create', [
            'categories' => Category::all(),
            'tags' => Tag::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Store $request): RedirectResponse
    {
        /** @var Article $article */
        $article = Article::factory()->create($request->safe()->except(['tags', 'image']));
        $article->tags()->attach($request->validated('tags'));
        if ($image = $request->file('image')) {
            $article->storeImage($image);
            $article->save();
        }

        return to_route('articles.show', [
            'article' => $article
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        return view('articles.show', [
            'article' => $article->load(['user', 'category', 'tags'])
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        return view('articles.edit', [
            'article' => $article->load(['user', 'category', 'tags']),
            'categories' => Category::all(),
            'tags' => Tag::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Update $request, Article $article)
    {
        $article->update($request->safe()->except('tags', 'image'));
        $article->tags()->sync($request->validated('tags'));

        if ($image = $request->file('image')) {
            $article->storeImage($image);
            $article->save();
        }

        return to_route('articles.show', $article);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article): RedirectResponse
    {
        $article->delete();

        return to_route('articles.index');
    }
}
