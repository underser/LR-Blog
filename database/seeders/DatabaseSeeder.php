<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $tagsIds = Tag::factory(25)->create()->modelKeys();
        $categories = Category::factory(30)->create();

        foreach ($categories as $category) {
            $article = Article::factory()->create([
                'user_id' => User::factory()->create(),
                'category_id' => $category->id
            ]);
            $article->tags()->attach(array_rand($tagsIds, 2));
        }
    }
}
