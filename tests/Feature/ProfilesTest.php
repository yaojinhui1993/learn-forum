<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use App\Thread;

class ProfilesTest extends TestCase
{
    /** @test */
    public function a_user_has_a_profile()
    {
        // Given we have a user
        $user = create(User::class);

        // When we visit the user profile page
        $this->get("/profiles/{$user->name}")
            ->assertSee($user->name);
        // Then we should see the user name
    }

    /** @test **/
    public function profiles_display_all_threads_created_by_the_associated_user()
    {
        // Give we have a user
        $user = create(User::class);
        // And a thread created by the user
        $thread = create(Thread::class, ['user_id' => $user->id]);

        // When we visit the user profile page
        // Then we should see the thread title and body
        $this->get("/profiles/{$user->name}")
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
