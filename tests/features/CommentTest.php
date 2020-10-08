<?php

use Faker\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Schema;
use LaravelEnso\Comments\Models\Comment;
use LaravelEnso\Comments\Notifications\CommentTagNotification;
use LaravelEnso\Comments\Traits\Commentable;
use LaravelEnso\Core\Models\User;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $faker;
    private $testModel;

    protected function setUp(): void
    {
        parent::setUp();

        // $this->withoutExceptionHandling();

        $this->seed()
            ->actingAs($this->user = User::first());

        $this->createTestTable();

        $this->faker = Factory::create();

        $this->testModel = Comment::factory()->create([
            'commentable_id' => CommentableTestModel::create(['name' => 'commentable'])->id,
            'commentable_type' => CommentableTestModel::class,
        ]);
    }

    /** @test */
    public function can_create_comment()
    {
        $this->post(
            route('core.comments.store'),
            $this->postParams()->toArray() + [
                'taggedUsers' => [],
                'path' => $this->faker->url,
            ]
        )->assertStatus(201)
            ->assertJsonStructure(['body']);
    }

    /** @test */
    public function can_get_comments_index()
    {
        $this->get(route('core.comments.index', $this->testModel->toArray(), false))
            ->assertStatus(200)
            ->assertJsonStructure(['data' => 'data']);
    }

    /** @test */
    public function can_update_comment()
    {
        $this->testModel->body = 'edited';

        $this->patch(
            route('core.comments.update', $this->testModel->id, false),
            $this->testModel->toArray() + [
                'taggedUsers' => [],
                'path' => $this->faker->url,
            ]
        )->assertStatus(200)
            ->assertJsonFragment(['body' => $this->testModel->body]);

        $this->assertEquals(
            $this->testModel->body,
            $this->testModel->fresh()->body
        );
    }

    /** @test */
    public function can_delete_comment()
    {
        $this->assertNotNull($this->testModel);

        $this->delete(route('core.comments.destroy', $this->testModel->id, false))
            ->assertStatus(200);

        $this->assertNull($this->testModel->fresh());
    }

    /** @test */
    public function can_store_with_tagged_user()
    {
        Notification::fake();

        $taggedUser = User::factory()->create();

        $taggedUsers = [[
            'id' => $taggedUser->id,
            'name' => $taggedUser->person->name,
        ]];

        $response = $this->post(
            route('core.comments.store', [], false),
            $this->postParams()->toArray() + [
                'taggedUsers' => $taggedUsers,
                'path' => $this->faker->url,
            ]
        )->assertStatus(201)
            ->assertJsonFragment(['taggedUsers' => $taggedUsers]);

        $commentId = $response->json()['id'];

        $this->assertEquals(
            Comment::find($commentId)->taggedUsers()->first()->id,
            $taggedUser->id
        );

        Notification::assertSentTo(
            User::find($taggedUser->id),
            CommentTagNotification::class
        );
    }

    /** @test */
    public function can_update_with_tagged_user()
    {
        Notification::fake();

        $taggedUser = User::factory()->create();

        $this->testModel->body = 'edited';

        $taggedUsers = [[
            'id' => $taggedUser->id,
            'name' => $taggedUser->person->name,
        ]];

        $this->patch(
            route('core.comments.update', [$this->testModel->id], false),
            $this->testModel->toArray() + [
                'taggedUsers' => $taggedUsers,
                'path' => $this->faker->url,
            ]
        )->assertStatus(200)
            ->assertJsonFragment(['taggedUsers' => $taggedUsers]);

        $this->assertEquals(
            $this->testModel->taggedUsers()->first()->id,
            $taggedUser->id
        );

        Notification::assertSentTo(
            User::find($taggedUser->id),
            CommentTagNotification::class
        );
    }

    private function createTestTable()
    {
        Schema::create('commentable_test_models', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });
    }

    private function postParams()
    {
        return Comment::factory()->make([
            'commentable_id' => CommentableTestModel::create(['name' => 'commentable'])->id,
            'commentable_type' => CommentableTestModel::class,
        ]);
    }
}

class CommentableTestModel extends Model
{
    use Commentable;

    protected $fillable = ['name'];
}
