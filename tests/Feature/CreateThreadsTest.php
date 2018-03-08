<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use App\Thread;
use App\Channel;
use Symfony\Component\HttpFoundation\Response;
use App\Reply;

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

    /** @test */
    public function unauthorized_user_may_not_delete_threads()
    {
        $this->withExceptionHandling();

        $this->signIn();
        $thread = create(Thread::class);
        $this->json('DELETE', $thread->path())
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $thread = create(Thread::class);
        auth()->logout();
        $this->json('DELETE', $thread->path())
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function authorized_user_can_delete_their_thread()
    {
        // Given we have an authenticated user
        $this->signIn();
        // And a thread create by the user
        $thread = create(Thread::class, ['user_id' => auth()->id()]);
        $reply = create(Reply::class, ['thread_id' => $thread->id]);

        // When we visit the delete thread endpoint
        $response = $this->json('DELETE', $thread->path());
        $response->assertStatus(Response::HTTP_NO_CONTENT);
        // The thread should missing in the database
        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertDatabaseMissing('activities', [
            'subject_id' => $thread->id,
            'subject_type' => get_class($thread)
        ]);
        $this->assertDatabaseMissing('activities', [
            'subject_id' => $reply->id,
            'subject_type' => get_class($reply)
        ]);
    }

    public function publishThread($overides = [])
    {
        $this->withExceptionHandling();

        $this->signIn();

        $thread = make(Thread::class, $overides);

        return $this->post('/threads', $thread->toArray());
    }
}
