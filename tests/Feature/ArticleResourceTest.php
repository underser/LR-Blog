<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ArticleResourceTest extends TestCase
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

    /**
     * @test
     */
    public function index_view_shows_all_articles(): void
    {
        $response = $this->get('/articles');
        $response->assertStatus(200);

        $response->assertSee($this->articleOne->name);
        $response->assertSee($this->articleTwo->name);
    }

    /**
     * @test
     */
    public function index_view_shows_article_tags(): void
    {
        $response = $this->get('/articles');
        $response->assertStatus(200);

        // Not assigned tags.
        Tag::factory(5)->create();

        $response->assertSee($this->tagOne->name);
        $response->assertSee($this->tagTwo->name);
    }

    /**
     * @test
     */
    public function index_view_shows_article_category(): void
    {
        $response = $this->get('/articles');
        $response->assertStatus(200);

        // Not assigned categories.
        Category::factory(5)->create();

        $response->assertSee($this->categoryOne->name);
        $response->assertSee($this->categoryTwo->name);
    }

    /**
     * @test
     */
    public function index_view_shows_article_thumbnail_image(): void
    {
        $response = $this->get('/articles');
        $response->assertStatus(200);

        // Not assigned categories.
        Category::factory(5)->create();

        $response->assertSee($this->articleOne->getImageUrl('thumbnail'));
    }

    /** @test */
    public function can_visit_show_view_from_index_view()
    {
        $response = $this->get('/articles');

        $response->assertSee(route('articles.show', $this->articleOne));
    }

    /** @test */
    public function show_view_displays_article()
    {
        $response = $this->get(route('articles.show', $this->articleOne));

        $response->assertSee($this->articleOne->name)
            ->assertSee($this->tagOne->name)
            ->assertDontSee($this->tagTwo->name)
            ->assertSee($this->articleOne->created_at->format('M d Y'))
            ->assertSee($this->articleOne->full_text)
            ->assertSee($this->articleOne->getImageUrl('main'));
    }

    /** @test */
    public function admin_user_can_edit_article()
    {
        $this->actingAs($this->adminUser)->get(route('articles.edit', $this->articleOne))
            ->assertStatus(200);
    }

    /** @test */
    public function not_admin_user_cant_edit_article()
    {
        $this->get(route('articles.edit', $this->articleOne))
            ->assertStatus(403);
    }

    /** @test */
    public function admin_user_can_update_article()
    {
        $nameUpdated = 'New name';
        $imageUpdated = UploadedFile::fake()->image('photonew.jpg');
        $categoryUpdated = Category::factory()->create();
        $tagUpdated = Tag::factory()->create();
        $fullText = 'New body text';

        $oldImageUrl = $this->articleOne->getImageUrl();

        $response = $this->actingAs($this->adminUser)->put(
            route('articles.update', $this->articleOne),
            [
                'name' => $nameUpdated,
                'image' => $imageUpdated,
                'category_id' => $categoryUpdated->id,
                'tags' => [$tagUpdated->id],
                'full_text' => $fullText
            ]
        );

        $response->assertStatus(302);
        $response->assertLocation(route('articles.show', $this->articleOne));

        $this->assertFalse(Storage::disk('public')->exists($oldImageUrl));
        $this->assertDatabaseHas('articles', [
            'name' => $nameUpdated,
            'category_id' => $categoryUpdated->id,
            'full_text' => $fullText
        ]);
        $this->assertDatabaseHas('article_tag', [
            'article_id' => $this->articleOne->id,
            'tag_id' => $tagUpdated->id
        ]);
    }

    /** @test */
    public function not_admin_user_cant_update_article()
    {
        $response = $this->actingAs(User::factory()->create())->put(
            route('articles.update', $this->articleOne),
            [
                'name' => 'New name',
                'image' => UploadedFile::fake()->image('photonew.jpg'),
                'category_id' => Category::factory()->create()->id,
                'tags' => [Tag::factory()->create()->id],
                'full_text' => 'New body text'
            ]
        );

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_user_can_destroy_article()
    {
        $response = $this->actingAs($this->adminUser)->delete(route('articles.destroy', $this->articleTwo));

        $response->assertStatus(302);
        $response->assertLocation(route('articles.index'));

        $this->assertDatabaseMissing('articles', [
            'id' => $this->articleTwo->id,
        ]);
    }

    /** @test */
    public function not_admin_user_cant_destroy_article()
    {
        $response = $this->actingAs(User::factory()->create())->delete(route('articles.destroy', $this->articleTwo));

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_user_can_store_article()
    {
        $name = 'New name';
        $image = UploadedFile::fake()->image('photo.jpg');
        $category = Category::factory()->create();
        $tag = Tag::factory()->create();
        $fullText = 'New body text';

        $response = $this->actingAs($this->adminUser)->post(
            route('articles.store'),
            [
                'name' => $name,
                'image' => $image,
                'category_id' => $category->id,
                'user_id' => $this->adminUser->id,
                'tags' => [$tag->id],
                'full_text' => $fullText
            ]
        );

        $article = Article::query()->where('name', '=', $name)->first();

        $response->assertStatus(302);

        $this->assertTrue(Storage::disk('public')->exists($article->getImageUrl()));
        $this->assertDatabaseHas('articles', [
            'name' => $name,
            'category_id' => $category->id,
            'full_text' => $fullText
        ]);
        $this->assertDatabaseHas('article_tag', [
            'article_id' => $article->id,
            'tag_id' => $tag->id
        ]);
    }

    /** @test */
    public function not_admin_user_cant_store_article()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post(
            route('articles.store'),
            [
                'name' => 'New name',
                'image' => UploadedFile::fake()->image('photonew.jpg'),
                'category_id' => Category::factory()->create()->id,
                'user_id' => $user->id,
                'tags' => [Tag::factory()->create()->id],
                'full_text' => 'New body text'
            ]
        );

        $response->assertStatus(403);
    }
}
