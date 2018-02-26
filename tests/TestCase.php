<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations;

    protected function signIn($user = null)
    {
        $user = $user ?: create(\App\User::class);
        $this->actingAs($user);

        return $user;
    }
}
