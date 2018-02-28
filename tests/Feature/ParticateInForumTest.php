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
        $this->withExceptionHandling();

        $thread = create(Thread::class);
        $this->post($thread->path() . '/replies', [])
            ->assertRedirect('/login');
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

    /** @test */
    public function a_reply_requires_a_body()
    {
        $this->withExceptionHandling();
        $this->signIn();

        $thread = create(Thread::class);

        $reply = make(Reply::class, [
            'user_id' => null,
            'thread_id' => null,
            'body' => null
        ]);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }
}
