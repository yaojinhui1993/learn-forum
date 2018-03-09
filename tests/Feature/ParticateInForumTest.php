<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use App\Thread;
use App\Reply;
use Symfony\Component\HttpFoundation\Response;

class ParticateInForumTest extends TestCase
{
    /** @test */
    public function unauthticated_user_may_not_add_replies()
    {
        $this->withExceptionHandling();
        $this->signIn();
        $thread = create(Thread::class);
        auth()->logout();

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

    /** @test */
    public function unauthorized_user_cannot_delete_replies()
    {
        $this->withExceptionHandling();
        $reply = create(Reply::class);

        $this->delete('/replies/' . $reply->id)
            ->assertRedirect('/login');

        $this->signIn();
        $this->delete('/replies/' . $reply->id)
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function authorized_user_can_delete_their_replies()
    {
        $this->signIn($user = create(User::class));

        $reply = create(Reply::class, ['user_id' => $user->id]);
        $this->assertDatabaseHas('replies', [
            'id' => $reply->id
        ]);

        $this->delete('/replies/' . $reply->id)
            ->assertStatus(Response::HTTP_FOUND);

        $this->assertDatabaseMissing('replies', [
            'id' => $reply->id
        ]);
    }

    /** @test */
    public function unauthorized_user_cannot_update_replies()
    {
        $this->withExceptionHandling();

        $reply = create(Reply::class);

        $this->patch('/replies/' . $reply->id, [])
            ->assertRedirect('/login');

        $this->signIn();
        $this->patch('/replies/' . $reply->id, [])
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function authorized_user_can_update_replies()
    {
        $this->signIn();

        $reply = create(Reply::class, ['user_id' => auth()->id()]);

        $updatedReply = 'You have been changed, fool!';
        $this->patch('/replies/' . $reply->id, [
            'body' => $updatedReply
        ]);

        $this->assertDatabaseHas('replies', [
            'id' => $reply->id,
            'body' => $updatedReply
        ]);
    }
}
