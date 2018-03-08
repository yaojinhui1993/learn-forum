<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Thread;
use App\Activity;
use App\Reply;

class ActivityTest extends TestCase
{
    /** @test */
    public function it_records_activity_when_a_thread_was_created()
    {
        // Given we have an authenticated user
        $this->signIn();

        // When the user created a thread
        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        // Then the database should has the activity about the action
        $this->assertDatabaseHas('activities', [
            'user_id' => auth()->id(),
            'type' => 'created_thread',
            'subject_id' => $thread->id,
            'subject_type' => 'App\Thread'
        ]);

        $this->assertEquals(Activity::first()->subject->id, $thread->id);
    }

    /** @test */
    public function it_records_activity_when_a_reply_was_created()
    {
        $this->signIn();

        $reply = create(Reply::class, ['user_id' => auth()->id()]);

        $this->assertDatabaseHas('activities', [
            'user_id' => auth()->id(),
            'type' => 'created_reply',
            'subject_id' => $reply->id,
            'subject_type' => 'App\Reply'
        ]);

        $this->assertEquals(2, Activity::count());
        $this->assertEquals(Activity::where('subject_type', 'App\Reply')->first()->subject->id, $reply->id);
    }
}
