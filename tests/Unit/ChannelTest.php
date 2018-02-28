<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Channel;
use App\Thread;

class ChannelTest extends TestCase
{
    /** @test */
    public function a_channel_consists_of_threads()
    {
        $channel = create(Channel::class);
        $thread = create(Thread::class, ['channel_id' => $channel->id]);

        $this->assertTrue($channel->threads->contains($thread));
    }
}
