<?php

namespace Tests\Feature;

use OwowAgency\LaravelTestResponse\TestResponse;
use Tests\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Assert response status code and snapshot its content.
     */
    public function assertResponse(TestResponse $response, int $status = 200)
    {
        $response->assertStatus($status);

        $this->assertMatchesJsonSnapshot($response->content());
    }
}
