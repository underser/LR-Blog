<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (($adminName = config('app.admin.name')) &&
            ($adminEmail = config('app.admin.email')) &&
            ($adminPassword = config('app.admin.password'))
        ) {
            $adminUser = User::factory()->create([
                'name' => $adminName,
                'email' => $adminEmail,
                'password' => Hash::make($adminPassword)
            ]);
            $tagsIds = Tag::factory(25)->create()->modelKeys();
            $categories = Category::factory(30)->create();

            foreach ($categories as $category) {
                $article = Article::factory()->create([
                    'user_id' => $adminUser->id,
                    'category_id' => $category->id
                ]);
                $article->tags()->attach([
                    $tagsIds[array_rand($tagsIds)],
                    $tagsIds[array_rand($tagsIds)]
                ]);
            }
        }
    }
}
