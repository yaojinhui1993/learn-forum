<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Thread;
use App\Activity;
use App\Reply;
use Carbon\Carbon;

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

    /** @test */
    public function it_fetches_a_feed_for_any_user()
    {
        $this->signIn();
        // Given we have a thread
        $thread = create(Thread::class, ['user_id' => auth()->id()], 2);
        // And another thread from a week ago
        auth()->user()->activities()->first()->update(['created_at' => Carbon::now()->subWeek()]);

        // When we fetch their feed
        $activity = Activity::feed(auth()->user());

        // Then, it should be returned in the proper format.
        $this->assertTrue(
            $activity->keys()->contains(
                Carbon::now()->format('Y-m-d')
            )
        );

        $this->assertTrue(
            $activity->keys()->contains(
                Carbon::now()->subWeek()->format('Y-m-d')
            )
        );
    }
}
