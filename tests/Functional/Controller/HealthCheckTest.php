<?php

namespace App\tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class HealthCheckTest extends WebTestCase
{
    public function test_request_response_successful_result(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/api/test');

        $this->assertResponseIsSuccessful();
    }
}