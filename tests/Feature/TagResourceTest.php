<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TagResourceTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;
    private Article $articleOne;
    private Category $categoryOne;
    private Category $categoryTwo;
    private Tag $tagOne;
    private Tag $tagTwo;
    private Article $articleTwo;

    protected function setUp(): void
    {
        parent::setUp();

        putenv('APP_ADMIN_USERNAME=admin');
        putenv('APP_ADMIN_EMAIL=domain@ex.com');
        putenv('APP_ADMIN_PASSWORD=BGxNWZ4wyF8Rs.vQmu7j');

        $this->adminUser = User::factory()->create([
            'name' => config('app.admin.name'),
            'email' => config('app.admin.email'),
            'password' => Hash::make(config('app.admin.password'))
        ]);

        $this->tagOne = Tag::factory()->create(['name' => 'Tag 1']);
        $this->tagTwo = Tag::factory()->create(['name' => 'Tag 2']);

        $this->categoryOne = Category::factory()->create();
        $this->categoryTwo = Category::factory()->create();

        $this->articleOne = Article::factory()->create([
            'user_id' => $this->adminUser->id,
            'category_id' => $this->categoryOne->id
        ]);

        $this->articleOne->storeImage(UploadedFile::fake()->image('photo.jpg'));
        $this->articleOne->save();

        $this->articleTwo = Article::factory()->create([
            'user_id' => $this->adminUser->id,
            'category_id' => $this->categoryTwo->id
        ]);

        $this->articleTwo->storeImage(UploadedFile::fake()->image('photo.jpg'));
        $this->articleTwo->save();

        $this->articleOne->tags()->attach($this->tagOne);
        $this->articleTwo->tags()->attach($this->tagTwo);
    }

    /** @test */
    public function admin_user_can_update_tag()
    {
        $nameUpdated = 'New name';

        $response = $this->actingAs($this->adminUser)->put(
            route('tags.update', $this->tagOne),
            [
                'name' => $nameUpdated
            ]
        );

        $response->assertStatus(302);

        $this->assertDatabaseHas('tags', [
            'name' => $nameUpdated,
        ]);
    }

    /** @test */
    public function not_admin_user_cant_update_tag()
    {
        $response = $this->actingAs(User::factory()->create())->put(
            route('tags.update', $this->tagOne),
            [
                'name' => 'New name'
            ]
        );

        $response->assertStatus(403);

        $this->assertDatabaseHas('tags', [
            'name' => $this->tagOne->name
        ]);
    }

    /** @test */
    public function admin_user_can_destroy_tag()
    {
        $response = $this->actingAs($this->adminUser)->delete(route('tags.destroy', $this->tagTwo));

        $response->assertStatus(302);

        $this->assertDatabaseMissing('tags', [
            'id' => $this->tagTwo->id
        ]);
    }

    /** @test */
    public function not_admin_user_cant_destroy_tag()
    {
        $response = $this->actingAs(User::factory()->create())->delete(route('tags.destroy', $this->tagTwo));

        $response->assertStatus(403);

        $this->assertDatabaseHas('tags', [
            'id' => $this->tagTwo->id
        ]);
    }

    /** @test */
    public function admin_user_can_store_tag()
    {
        $name = 'New name';

        $response = $this->actingAs($this->adminUser)->post(
            route('tags.store'),
            [
                'name' => $name
            ]
        );

        $tag = Tag::query()->where('name', '=', $name)->first();

        $response->assertStatus(302);

        $this->assertDatabaseHas('tags', [
            'id' => $tag->id,
            'name' => $name,
        ]);
    }

    /** @test */
    public function not_admin_user_cant_store_tag()
    {
        $response = $this->actingAs(User::factory()->create())->post(
            route('tags.store'),
            [
                'name' => 'New name'
            ]
        );

        $response->assertStatus(403);

        $this->assertDatabaseCount('tags', 2);
    }

    /** @test */
    public function tag_get_assigned_to_article_during_store()
    {
        $name = 'New name';

        $response = $this->actingAs($this->adminUser)->post(
            route('tags.store', ['article' => $this->articleOne]),
            [
                'name' => $name
            ]
        );

        $tag = Tag::query()->where('name', '=', $name)->first();

        $response->assertStatus(302);

        $this->assertDatabaseHas('tags', [
            'id' => $tag->id,
            'name' => $name,
        ]);

        $this->assertDatabaseHas('article_tag', [
            'article_id' => $this->articleOne->id,
            'tag_id' => $tag->id
        ]);
    }
}
