<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use App\Thread;

class CreateThreadsTest extends TestCase
{
    /** @test */
    public function guest_may_not_create_thread()
    {
        $this->withExceptionHandling();

        $this->get('/threads/create')
            ->assertRedirect('/login');

        $this->post('/threads', [])
            ->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_can_create_new_forum_threads()
    {
        // Given we have an authenticated user
        $this->signIn();
        // When we hit the endpoint to create a new thread

        $thread = create(Thread::class);
        $this->post('/threads', $thread->toArray());
        // Then, when we visit the thread page

        // We should see the new thread.
        $this->get($thread->path())
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
