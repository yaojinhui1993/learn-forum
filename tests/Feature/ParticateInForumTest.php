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
        $user = create(User::class);

        $thread = create(Thread::class);
        // When the user adds a reply to the thread
        $this->post($thread->path() . '/replies', []);
    }

    /** @test */
    public function an_authenticated_user_may_participate_a_forum_thread()
    {
        // Given we have an authticated user
        $this->signIn();
        // And an existing thread
        $thread = create(Thread::class);

        $reply = make(Reply::class, [
            'user_id' => null,
            'thread_id' => null
        ]);
        // When the user adds a reply to the thread
        $this->post($thread->path() . '/replies', $reply->toArray());
        // Then the reply should be visible on the page.
        $this->get($thread->path())
            ->assertsee($reply->body)
            ->assertsee(auth()->user()->name);
    }
}
