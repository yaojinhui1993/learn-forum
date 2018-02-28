<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Thread;
use App\Reply;
use App\Channel;

class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    public function setUp()
    {
        parent::setUp();

        $this->thread = create(Thread::class);
    }

    /** @test */
    public function a_user_can_view_all_threads()
    {
        $this->get('/threads')
            ->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_read_a_single_thread()
    {
        $this->get($this->thread->path())
            ->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_read_replies_that_are_associated_with_a_thread()
    {
        // Given we have a thread
        // And that thread includes replies
        $reply = create(Reply::class, ['thread_id' => $this->thread->id]);
        // When we visit that thread page
        $this->get($this->thread->path())
            ->assertSee($reply->body);
        // Then we should see that replies.
    }

    /** @test */
    public function a_user_can_read_a_thread_according_to_a_channel()
    {
        $channel = create(Channel::class);
        $inChannelThread = create(Thread::class, ['channel_id' => $channel->id]);
        $notInChannelThread = create(Thread::class);

        $this->get('/threads/' . $channel->slug)
            ->assertSee($inChannelThread->title)
            ->assertDontSee($notInChannelThread->title);
    }
}
