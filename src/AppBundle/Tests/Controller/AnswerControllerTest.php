<?php

/**
 * @author Damian Dlugosz <d.dlugosz@bestnetwork.it>
 */

namespace AppBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class AnswerControllerTest extends WebTestCase
{
    protected static $fixtures = array(
        '@AppBundle/DataFixtures/ORM/questions.yml',
        '@AppBundle/DataFixtures/ORM/answers.yml'
    );

    public function testIndex()
    {
        $this->loadFixtureFiles(static::$fixtures);

        $route =  $this->getUrl('answer_index');
        $this->client->request('GET', $route);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);

        $content = json_decode($response->getContent());
        $this->assertCount(100, $content);
    }

    public function testShow()
    {
        $this->loadFixtureFiles(static::$fixtures);

        $route =  $this->getUrl('answer_show', array('id' => 1));
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
            'question' => 1,
            'title' => 'Title',
            'content' => 'Answer content',
            'created_by' => 'Test User',
        );

        $route =  $this->getUrl('answer_create');
        $this->client->request('POST', $route, array(), array(), array(), $data);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 201);

        $content = json_decode($response->getContent());
        $this->assertEquals(101, $content->id);



        // Check that resource has been created correctly
        $route =  $this->getUrl('answer_show', array('id' => $content->id));
        $this->client->request('GET', $route);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);

        $content = json_decode($response->getContent());
        $this->assertEquals('Title', $content->title);
        $this->assertEquals('Answer content', $content->content);
        $this->assertEquals('Test User', $content->created_by);
    }

    public function testUpdate()
    {
        $this->loadFixtureFiles(static::$fixtures);

        $data = array(
            'content' => 'Content updated',
        );

        $route =  $this->getUrl('answer_update', array('id' => 1));
        $this->client->request('PUT', $route, array(), array(), array(), $data);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 204);



        // Check that resource has been updated correctly
        $route =  $this->getUrl('answer_show', array('id' => 1));
        $this->client->request('GET', $route);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);

        $content = json_decode($response->getContent());
        $this->assertEquals('Content updated', $content->content);
    }

    public function testUpload()
    {
        $this->loadFixtureFiles(static::$fixtures);

        $file_path = __DIR__.'/resources/test.file';
        $file_content = file_get_contents($file_path);
        $file = new UploadedFile($file_path, 'test.file', 'application/octet-stream', strlen($file_content));

        $route =  $this->getUrl('answer_upload', array('id' => 1));
        $this->client->request('POST', $route, array(), array($file));
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 201);

        $content = json_decode($response->getContent());
        $this->assertNotEmpty($content->url);



        // Check that file has been uploaded correctly
        $root_directory = $this->getContainer()->getParameter('kernel.root_dir').'/..';
        $uploaded_file_path = $root_directory.'/web'.$content->url;

        $this->assertEquals($file_content, file_get_contents($uploaded_file_path));



        // Clear file system from the uploaded test file
        unlink($uploaded_file_path);
    }

    public function testRemove()
    {
        $this->loadFixtureFiles(static::$fixtures);

        $route =  $this->getUrl('answer_remove', array('id' => 1));
        $this->client->request('DELETE', $route);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 410);



        // Return to the index and check that comment has been removed
        $route =  $this->getUrl('answer_index');
        $this->client->request('GET', $route);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);

        $content = json_decode($response->getContent());
        $this->assertCount(99, $content);
    }
}
