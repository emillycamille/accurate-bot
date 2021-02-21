<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use OwowAgency\LaravelTestResponse\TestResponse;
use Tests\Concerns\CreatesApplication;
use Tests\Concerns\MatchesSnapshots;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, MatchesSnapshots;

    /**
     * Create the test response instance from the given response.
     *
     * @param  \Illuminate\Http\Response  $response
     * @return \Illuminate\Testing\TestResponse
     */
    protected function createTestResponse($response)
    {
        return TestResponse::fromBaseResponse($response);
    }

    /**
     * Assert that a HTTP request is sent and snapshot its method, url, data.
     */
    protected function assertRequestSent(bool $withHeaders = false): self
    {
        Http::assertSent(function (Request $request) use ($withHeaders) {
            $snapshot = $withHeaders
                ? ['headers' => $request->headers()]
                : [];

            $snapshot += [
                'method' => $request->method(),
                'url' => $request->url(),
                'data' => $request->data(),
            ];

            $this->assertMatchesJsonSnapshot($snapshot);

            return true;
        });

        return $this;
    }
}
