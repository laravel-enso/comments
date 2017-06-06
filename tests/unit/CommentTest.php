<?php

use App\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use DatabaseMigrations;

    private $user;
    private $faker;

    protected function setUp()
    {
        parent::setUp();

        $this->user = User::first();
        $this->faker = Factory::create();
        $this->be($this->user);
    }

    /** @test */
    public function create_comment()
    {
        $data = $this->postParams();
        $response = $this->post('/core/comments', $data);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'body' => $data['body'],
            ]);
    }

    /** @test */
    public function tag_user()
    {
        $data = $this->postParams();
        $data['tagged_users_list'] = [['id' => 1, 'full_name' => $this->user->full_name]];
        $response = $this->post('/core/comments', $data);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'tagged_users_list' => [['id' => 1, 'full_name' => $this->user->full_name]],
            ]);
    }

    /** @test */
    public function get_comments()
    {
        $this->post('/core/comments', $this->postParams());

        $response = $this->get(route('core.comments.index', $this->getParams()));

        $response->assertJson(['count' => 1]);
    }

    /** @test */
    public function edit_comment()
    {
        $param = $this->postParams();
        $this->post('/core/comments', $param);
        $param['body'] = 'edited';

        $response = $this->patch('/core/comments/1', $param);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'body' => 'edited',
            ]);
    }

    /** @test */
    public function delete_comment()
    {
        $this->post('/core/comments', $this->postParams());

        $response = $this->delete('/core/comments/1');

        $response->assertStatus(200);
    }

    /** @test */
    public function get_taggable_users_with_query()
    {
        $response = $this->get('/core/comments/getTaggableUsers/'.$this->user->full_name);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'full_name' => $this->user->full_name,
            ]);
    }

    private function postParams()
    {
        return [
            'id'                => 1,
            'type'              => 'owner',
            'body'              => $this->faker->sentence,
            'tagged_users_list' => [],
            'url'               => $this->faker->url,
        ];
    }

    public function getParams()
    {
        return [
            'id'       => 1,
            'type'     => 'owner',
            'offset'   => 0,
            'paginate' => 5,
        ];
    }
}
