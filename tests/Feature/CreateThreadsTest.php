<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use App\Thread;
use App\Channel;

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
        $thread = make(Thread::class);

        $response = $this->post('/threads', $thread->toArray());
        // Then, when we visit the thread page

        // We should see the new thread.
        $this->get($response->headers->get('location'))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /** @test */
    public function a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_thread_requires_a_valid_channel()
    {
        // create(Channel::class, 2);
        factory(Channel::class, 2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
        ->assertSessionHasErrors('channel_id');
    }

    public function publishThread($overides = [])
    {
        $this->withExceptionHandling();

        $this->signIn();

        $thread = make(Thread::class, $overides);

        return $this->post('/threads', $thread->toArray());
    }
}
