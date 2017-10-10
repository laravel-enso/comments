<?php

use App\Owner;
use App\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Notification;
use LaravelEnso\CommentsManager\app\Models\Comment;
use LaravelEnso\CommentsManager\app\Notifications\CommentTagNotification;
use LaravelEnso\TestHelper\app\Classes\TestHelper;

class CommentTest extends TestHelper
{
    use DatabaseMigrations;

    private $user;
    private $owner;
    private $faker;

    protected function setUp()
    {
        parent::setUp();

        // $this->disableExceptionHandling();
        $this->user = User::first();
        $this->signIn($this->user);
        $this->faker = Factory::create();
        $this->owner = Owner::first();
        config(['comments.commentables' => ['owner' => 'App\Owner']]);
    }

    /** @test */
    public function create_comment()
    {
        $data     = $this->postParams();
        $response = $this->post('/core/comments', $data);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'body' => $data['body'],
            ]);
    }

    /** @test */
    public function get_comments()
    {
        $this->createComment();
        $this->call('GET', '/core/comments', $this->getParams())
            ->assertJsonFragment(['count' => 1]);
    }

    /** @test */
    public function edit_comment()
    {
        $comment       = $this->createComment();
        $comment->body = 'edited';

        $this->patch('/core/comments/' . $comment->id, $comment->toArray())
            ->assertStatus(200)
            ->assertJsonFragment([
                'body' => 'edited',
            ]);
    }

    /** @test */
    public function delete_comment()
    {
        $comment = $this->createComment();

        $this->delete('/core/comments/' . $comment->id)
            ->assertStatus(200);
    }

    /** @test */
    public function get_taggable_users_with_query()
    {
        $tagUser = factory('App\User')->create();

        $this->get('/core/comments/getTaggableUsers/' . $tagUser->fullName)
            ->assertStatus(200)
            ->assertJsonFragment([
                'fullName' => $tagUser->fullName,
            ]);
    }

    /** @test */
    public function tag_user()
    {
        Notification::fake();

        $data                   = $this->postParams();
        $data['taggedUserList'] = [
            ['id' => 1, 'fullName' => $this->user->fullName],
        ];

        $this->post('/core/comments', $data)
            ->assertStatus(200)
            ->assertJsonFragment(['taggedUserList' => $data['taggedUserList']]);

        Notification::assertSentTo([$this->user], CommentTagNotification::class);
    }

    private function postParams()
    {
        return [
            'id'             => $this->owner->id,
            'type'           => 'owner',
            'body'           => $this->faker->sentence,
            'taggedUserList' => [],
            'url'            => $this->faker->url,
        ];
    }

    private function createComment()
    {
        $comment = new Comment($this->postParams());
        $this->owner->comments()->save($comment);

        return $comment->fresh();
    }

    private function getParams()
    {
        return [
            'id'       => $this->owner->id,
            'type'     => 'owner',
            'offset'   => 0,
            'paginate' => 5,
        ];
    }
}
