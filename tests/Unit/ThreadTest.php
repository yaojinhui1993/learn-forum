<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Thread;
use Illuminate\Database\Eloquent\Collection;
use App\User;
use App\Channel;

class ThreadTest extends TestCase
{
    protected $thread;

    public function setUp()
    {
        parent::setUp();
        $this->signIn();
        $this->thread = create(Thread::class);
    }

    /** @test */
    public function a_thread_can_make_a_string_path()
    {
        $thread = create(Thread::class);
        $url = "/threads/{$thread->fresh()->channel->slug}/{$thread->id}";
        $this->assertEquals($url, $thread->path());
    }

    /** @test */
    public function a_thread_has_replies()
    {
        $this->assertInstanceOf(Collection::class, $this->thread->replies);
    }

    /** @test */
    public function a_thread_has_a_creator()
    {
        $this->assertInstanceOf(User::class, $this->thread->creator);
    }

    /** @test */
    public function a_thread_can_add_a_reply()
    {
        $this->assertCount(0, $this->thread->replies);

        $this->thread->addReply([
            'body' => 'reply body',
            'user_id' => 1
        ]);

        $this->assertCount(1, $this->thread->refresh()->replies);
    }

    /** @test */
    public function a_thread_belongs_to_a_channel()
    {
        $thread = create(Thread::class);

        $this->assertInstanceOf(Channel::class, $thread->channel);
    }
}
