<?php

use App\User;
use Faker\Factory;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;
use LaravelEnso\TestHelper\app\Traits\SignIn;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LaravelEnso\CommentsManager\app\Models\Comment;
use LaravelEnso\CommentsManager\app\Traits\Commentable;
use LaravelEnso\CommentsManager\app\Notifications\CommentTagNotification;

class CommentTest extends TestCase
{
    use RefreshDatabase, SignIn;

    private $user;
    private $faker;

    protected function setUp()
    {
        parent::setUp();

        // $this->withoutExceptionHandling();

        $this->seed()
            ->signIn($this->user = User::first());

        $this->faker = Factory::create();

        $this->createTestModelsTable();

        $this->testModel = $this->createTestModel();

        config([
            'enso.comments.commentables' => ['testModel' => 'TestModel']
        ]);
    }

    /** @test */
    public function create_comment()
    {
        $data = $this->postParams();

        $response = $this->post(route('core.comments.store'), $data);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'body' => $data['body'],
            ]);
    }

    /** @test */
    public function get_comments()
    {
        $this->createComment();

        $this->get(route('core.comments.index', $this->getParams(), false))
            ->assertJsonFragment(['count' => 1]);
    }

    /** @test */
    public function edit_comment()
    {
        $comment = $this->createComment();

        $comment->body = 'edited';
        $comment->path = $this->faker->url;

        $this->patch(route('core.comments.update', $comment->id, false), $comment->toArray())
            ->assertStatus(200)
            ->assertJsonFragment([
                'body' => 'edited',
            ]);
    }

    /** @test */
    public function delete_comment()
    {
        $comment = $this->createComment();

        $this->delete(route('core.comments.destroy', $comment->id, false))
            ->assertStatus(200);
    }

    /** @test */
    public function get_taggable_users_with_query()
    {
        $tagUser = factory(User::class)->create();

        $this->get(route('core.comments.getTaggableUsers', $tagUser->fullName, false))
            ->assertStatus(200)
            ->assertJsonFragment([
                'fullName' => $tagUser->fullName,
            ]);
    }

    /** @test */
    public function tag_user()
    {
        Notification::fake();

        $data = $this->postParams();

        $data['taggedUserList'] = [
            ['id' => 1, 'fullName' => $this->user->fullName],
        ];

        $this->post(route('core.comments.store', [], false), $data)
            ->assertStatus(200)
            ->assertJsonFragment(['taggedUserList' => $data['taggedUserList']]);

        Notification::assertSentTo([$this->user], CommentTagNotification::class);
    }

    private function createComment()
    {
        $comment = new Comment($this->postParams());

        $this->testModel->comments()->save($comment);

        return $comment->fresh();
    }

    private function postParams()
    {
        return [
            'commentable_id' => $this->testModel->id,
            'commentable_type' => 'testModel',
            'body' => $this->faker->sentence,
            'taggedUserList' => [],
            'path' => $this->faker->url,
        ];
    }

    private function getParams()
    {
        return [
            'commentable_id' => $this->testModel->id,
            'commentable_type' => 'testModel',
            'offset' => 0,
            'paginate' => 5,
        ];
    }

    private function createTestModelsTable()
    {
        Schema::create('test_models', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });
    }

    private function createTestModel()
    {
        return TestModel::create(['name' => 'commentable']);
    }
}

class TestModel extends Model
{
    use Commentable;

    protected $fillable = ['name'];
}
