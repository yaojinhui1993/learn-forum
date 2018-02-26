<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use App\Thread;
use Illuminate\Http\Response;

class CreateThreadsTest extends TestCase
{
    /** @test */
    public function guest_may_not_create_thread()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $this->post('/threads', [])
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function an_authenticated_user_can_create_new_forum_threads()
    {
        // Given we have an authenticated user
        $this->actingAs(factory(User::class)->create());
        // When we hit the endpoint to create a new thread

        $thread = factory(Thread::class)->make();
        $this->post('/threads', $thread->toArray());
        // Then, when we visit the thread page

        // We should see the new thread.
        $this->get($thread->path())
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
