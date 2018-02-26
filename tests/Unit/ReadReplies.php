<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Reply;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReadReplies extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_has_an_owner()
    {
        $reply = create(Reply::class);

        $this->assertInstanceOf(User::class, $reply->owner);
    }
}
