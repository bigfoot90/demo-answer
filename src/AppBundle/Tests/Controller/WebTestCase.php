<?php

/**
 * @author Damian Dlugosz <d.dlugosz@bestnetwork.it>
 */

namespace AppBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Response;

class WebTestCase extends BaseWebTestCase
{
    /**
     * @var Client
     */
    protected $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    protected function assertJsonResponse(Response $response, $statusCode = 200)
    {
        $content = $response->getContent();

        $this->assertEquals(
            $statusCode, $response->getStatusCode(),
            $content
        );

        if ($statusCode != 204) {
            $this->assertTrue(
                $response->headers->contains('Content-Type', 'application/json'),
                $response->headers
            );
        } else {
            $this->assertEmpty($content, $content);
        }
    }
}
