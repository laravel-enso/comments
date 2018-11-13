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
        
        $this->createTestTable();

        $this->faker = Factory::create();

        $this->testModel = factory(Comment::class)->create([
            'commentable_id' => TestModel::create(['name' => 'commentable'])->id,
            'commentable_type' => TestModel::class,
        ]);
    }

    /** @test */
    public function can_create_comment()
    {
        $this->post(
            route('core.comments.store'),
            $this->postParams()->toArray() + [
                'taggedUsers' => [],
                'path' => $this->faker->url
        ]
        )->assertStatus(200)
        ->assertJsonStructure(['comment' => ['body']]);
    }

    /** @test */
    public function can_get_comments_index()
    {
        $this->get(route('core.comments.index', $this->testModel->toArray(), false))
        ->assertStatus(200)
        ->assertJsonStructure([['body']]);
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
            ->assertJsonFragment([
                'body' => 'edited',
            ]);

        $this->assertEquals($this->testModel->fresh()->body, 'edited');
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
        \Notification::fake();

        $taggedUser = factory(User::class)->create();
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
            )->assertStatus(200)
            ->assertJsonFragment(['taggedUsers' => $taggedUsers]);

        $commentId = collect($response->decodeResponseJson())
            ->pluck('id')
            ->first();

        $this->assertEquals(
            Comment::find($commentId)->taggedUsers()->first()->id,
            $taggedUser->id
        );
        \Notification::assertSentTo($taggedUser, CommentTagNotification::class);
    }

    /** @test */
    public function can_update_with_tagged_user()
    {
        \Notification::fake();

        $taggedUser = factory(User::class)->create();

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

        \Notification::assertSentTo($taggedUser, CommentTagNotification::class);
    }

    private function createTestTable()
    {
        Schema::create('test_models', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });
    }

    private function postParams()
    {
        return factory(Comment::class)->make([
            'commentable_id' => TestModel::create(['name' => 'commentable'])->id,
            'commentable_type' => TestModel::class,
        ]);
    }
}

class TestModel extends Model
{
    use Commentable;

    protected $fillable = ['name'];
}
