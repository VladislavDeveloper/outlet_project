<?php

namespace App\tests\Functional\Controller;

use App\Repository\PostsRepository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class PostControllerTest extends WebTestCase
{
    public function testGetAllPosts(): void
    {
        $client = self::createClient();

        $client->request('GET', '/api/posts/test');

        self::assertResponseStatusCodeSame(200);
    }
}