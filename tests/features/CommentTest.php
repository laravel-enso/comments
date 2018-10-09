<?php

use Faker\Factory;
use Tests\TestCase;
use LaravelEnso\Core\app\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LaravelEnso\CommentsManager\app\Models\Comment;
use LaravelEnso\CommentsManager\app\Traits\Commentable;
use LaravelEnso\CommentsManager\app\Notifications\CommentTagNotification;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $faker;
    private $testModel;

    protected function setUp()
    {
        parent::setUp();

        // $this->withoutExceptionHandling();

        $this->seed()
            ->actingAs($this->user = User::first());

        $this->faker = Factory::create();

        $this->testModel = $this->model();
    }

    /** @test */
    public function can_create_comment()
    {
        $comment = factory(Comment::class)->make([
            'commentable_id' => $this->testModel->id,
            'commentable_type' => TestModel::class,
        ]);

        $this->post(
            route('core.comments.store'),
            $comment->toArray() + [
                'taggedUsers' => [],
                'path' => $this->faker->url
        ]
        )->assertStatus(200)
        ->assertJsonFragment([
            'body' => $comment['body'],
        ]);
    }

    /** @test */
    public function can_get_comments_index()
    {
        $comment = factory(Comment::class)->create([
            'commentable_id' => $this->testModel->id,
            'commentable_type' => TestModel::class,
        ]);

        $this->get(route('core.comments.index', [
            'commentable_id' => $this->testModel->id,
            'commentable_type' => $comment->commentable_type,
        ], false))
        ->assertStatus(200)
        ->assertJsonStructure([['body']]);
    }

    /** @test */
    public function can_update_comment()
    {
        $comment = factory(Comment::class)->create([
            'commentable_id' => $this->testModel->id,
            'commentable_type' => TestModel::class,
        ]);

        $comment->body = 'edited';

        $this->patch(
            route('core.comments.update', $comment->id, false),
            $comment->toArray() + [
                'taggedUsers' => [],
                'path' => $this->faker->url,
            ]
        )->assertStatus(200)
            ->assertJsonFragment([
                'body' => 'edited',
            ]);

        $this->assertEquals($comment->fresh()->body, 'edited');
    }

    /** @test */
    public function can_delete_comment()
    {
        $comment = factory(Comment::class)->create([
            'commentable_id' => $this->testModel->id,
            'commentable_type' => TestModel::class,
        ]);

        $this->delete(route('core.comments.destroy', $comment->id, false))
            ->assertStatus(200);
    }

    /** @test */
    public function can_store_with_tagged_user()
    {
        \Notification::fake();

        $comment = factory(Comment::class)->make([
            'commentable_id' => $this->testModel->id,
            'commentable_type' => TestModel::class,
        ]);

        $taggedUser = factory(User::class)->create();

        $taggedUsers = [[
            'id' => $taggedUser->id,
            'name' => $taggedUser->person->name,
        ]];

        $this->post(
            route('core.comments.store', [], false),
                $comment->toArray() + [
                    'taggedUsers' => $taggedUsers,
                    'path' => $this->faker->url,
                ]
            )->assertStatus(200)
            ->assertJsonFragment(['taggedUsers' => $taggedUsers]);

        $this->assertEquals(
            Comment::latest()->first()->taggedUsers()->first()->id,
            $taggedUser->id
        );

        \Notification::assertSentTo($taggedUser, CommentTagNotification::class);
    }

    /** @test */
    public function can_update_with_tagged_user()
    {
        \Notification::fake();

        $comment = factory(Comment::class)->create([
            'commentable_id' => $this->testModel->id,
            'commentable_type' => TestModel::class,
        ]);

        $taggedUser = factory(User::class)->create();

        $comment->body = 'edited';

        $taggedUsers = [[
            'id' => $taggedUser->id,
            'name' => $taggedUser->person->name,
        ]];

        $this->patch(
            route('core.comments.update', [$comment->id], false),
                $comment->toArray() + [
                    'taggedUsers' => $taggedUsers,
                    'path' => $this->faker->url,
                ]
            )->assertStatus(200)
            ->assertJsonFragment(['taggedUsers' => $taggedUsers]);

        $this->assertEquals(
            $comment->taggedUsers()->first()->id,
            $taggedUser->id
        );

        \Notification::assertSentTo($taggedUser, CommentTagNotification::class);
    }

    private function model()
    {
        $this->createTestTable();

        return TestModel::create(['name' => 'commentable']);
    }

    private function createTestTable()
    {
        Schema::create('test_models', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });
    }
}

class TestModel extends Model
{
    use Commentable;

    protected $fillable = ['name'];
}
