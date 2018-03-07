<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Reply;

class FavoritesTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function a_guest_cannot_favorite_anything()
    {
        $this->withExceptionHandling();

        $this->post('replies/1/favorites')
            ->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticate_user_can_favorite_any_reply()
    {
        $this->signIn();
        // replies/id/favorites
        $reply = create(Reply::class);

        // If I post to a "favorite" endpoint.
        $this->post('replies/' . $reply->id . '/favorites');

        // It should be recorded in the database.
        $this->assertCount(1, $reply->refresh()->favorites);
    }

    /** @test */
    public function an_authenticate_user_may_only_favorite_a_reply_once()
    {
        $this->signIn();
        $reply = create(Reply::class);

        try {
            $this->post('replies/' . $reply->id . '/favorites');
            $this->post('replies/' . $reply->id . '/favorites');
        } catch (\Exception $e) {
            $this->fail('Did not except to insert the same record set twice.');
        }

        $this->assertCount(1, $reply->refresh()->favorites);
    }
}
