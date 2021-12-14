<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function getAdminUser(): User
    {
        return User::factory()->make([
            'is_admin' => true
        ]);
    }

    protected function getUser(): User
    {
        return User::factory()->make();
    }
}
