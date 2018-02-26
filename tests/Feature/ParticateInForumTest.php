<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use App\Thread;
use App\Reply;

class ParticateInForumTest extends TestCase
{
    /** @test */
    public function unauthticated_user_may_not_add_replies()
    {
        $this->expectException("Illuminate\Auth\AuthenticationException");
        $user = factory(User::class)->create();

        $thread = factory(Thread::class)->create();
        // When the user adds a reply to the thread
        $this->post($thread->path() . '/replies', []);
    }

    /** @test */
    public function an_authenticated_user_may_participate_a_forum_thread()
    {
        // Given we have an authticated user
        $user = factory(User::class)->create();
        $this->be($user);
        // And an existing thread
        $thread = factory(Thread::class)->create();

        $reply = factory(Reply::class)->make([
            'user_id' => null,
            'thread_id' => null
        ]);
        // When the user adds a reply to the thread
        $this->post($thread->path() . '/replies', $reply->toArray());
        // Then the reply should be visible on the page.
        $this->get($thread->path())
            ->assertsee($reply->body)
            ->assertsee($user->name);
    }
}
