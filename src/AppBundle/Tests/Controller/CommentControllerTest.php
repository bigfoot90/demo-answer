<?php

/**
 * @author Damian Dlugosz <d.dlugosz@bestnetwork.it>
 */

namespace AppBundle\Tests\Controller;

class CommentControllerTest extends WebTestCase
{
    protected static $fixtures = array(
        '@AppBundle/DataFixtures/ORM/questions.yml',
        '@AppBundle/DataFixtures/ORM/answers.yml',
        '@AppBundle/DataFixtures/ORM/comments.yml'
    );

    public function testIndex()
    {
        $this->loadFixtureFiles(static::$fixtures);

        $route =  $this->getUrl('comment_index');
        $this->client->request('GET', $route);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);

        $content = json_decode($response->getContent());
        $this->assertCount(1000, $content);
    }

    public function testShow()
    {
        $this->loadFixtureFiles(static::$fixtures);

        $route =  $this->getUrl('comment_show', array('id' => 1));
        $this->client->request('GET', $route);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);

        $content = json_decode($response->getContent());
        $this->assertEquals(1, $content->id);
    }

    public function testCreate()
    {
        $this->loadFixtureFiles(static::$fixtures);

        $data = array(
            'answer' => 1,
            'text' => 'Comment text',
            'created_by' => 'Test User',
        );

        $route =  $this->getUrl('comment_create');
        $this->client->request('POST', $route, array(), array(), array(), $data);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 201);

        $content = json_decode($response->getContent());
        $this->assertEquals(1001, $content->id);



        // Check that resource has been created correctly
        $route =  $this->getUrl('comment_show', array('id' => $content->id));
        $this->client->request('GET', $route);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);

        $content = json_decode($response->getContent());
        $this->assertEquals('Comment text', $content->text);
        $this->assertEquals('Test User', $content->created_by);
    }

    public function testUpdate()
    {
        $this->loadFixtureFiles(static::$fixtures);

        $data = array(
            'text' => 'Content updated',
        );

        $route =  $this->getUrl('comment_update', array('id' => 1));
        $this->client->request('PUT', $route, array(), array(), array(), $data);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 204);



        // Check that resource has been updated correctly
        $route =  $this->getUrl('comment_show', array('id' => 1));
        $this->client->request('GET', $route);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);

        $content = json_decode($response->getContent());
        $this->assertEquals('Content updated', $content->text);
    }

    public function testRemove()
    {
        $this->loadFixtureFiles(static::$fixtures);

        $route =  $this->getUrl('comment_remove', array('id' => 1));
        $this->client->request('DELETE', $route);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 410);



        // Return to the index and check that comment has been removed
        $route =  $this->getUrl('comment_index');
        $this->client->request('GET', $route);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);

        $content = json_decode($response->getContent());
        $this->assertCount(999, $content);
    }
}
