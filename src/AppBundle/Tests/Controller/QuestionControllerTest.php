<?php

/**
 * @author Damian Dlugosz <bigfootdd@gmail.com>
 */

namespace AppBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class QuestionControllerTest extends WebTestCase
{
    protected static $fixtures = array(
        '@AppBundle/DataFixtures/ORM/questions.yml'
    );

    public function testIndex()
    {
        $this->loadFixtureFiles(static::$fixtures);

        $route =  $this->getUrl('question_index');
        $this->client->request('GET', $route);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);

        $content = json_decode($response->getContent());
        $this->assertCount(10, $content);
    }

    public function testSearch()
    {
        $this->loadFixtureFiles(static::$fixtures);

        $route =  $this->getUrl('question_search', array('keywords' => 'et+es'));
        $this->client->request('GET', $route);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
    }

    public function testMostSearched()
    {
        $this->loadFixtureFiles(static::$fixtures);

        $route =  $this->getUrl('question_most_searched');
        $this->client->request('GET', $route);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
    }

    public function testMostViewed()
    {
        $this->loadFixtureFiles(static::$fixtures);

        $route =  $this->getUrl('question_most_viewed');
        $this->client->request('GET', $route);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
    }

    public function testNewest()
    {
        $this->loadFixtureFiles(static::$fixtures);

        $route =  $this->getUrl('question_newest');
        $this->client->request('GET', $route);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
    }

    public function testShow()
    {
        $this->loadFixtureFiles(static::$fixtures);

        $route =  $this->getUrl('question_show', array('id' => 1));
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
            'title' => 'Title',
            'content' => 'Question content',
            'created_by' => 'Test User',
        );

        $route =  $this->getUrl('question_create');
        $this->client->request('POST', $route, array(), array(), array(), $data);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 201);

        $content = json_decode($response->getContent());
        $this->assertEquals(11, $content->id);



        // Check that resource has been created correctly
        $route =  $this->getUrl('question_show', array('id' => $content->id));
        $this->client->request('GET', $route);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);

        $content = json_decode($response->getContent());
        $this->assertEquals('Title', $content->title);
        $this->assertEquals('Question content', $content->content);
        $this->assertEquals('Test User', $content->created_by);
    }

    public function testUpdate()
    {
        $this->loadFixtureFiles(static::$fixtures);

        $data = array(
            'content' => 'Content updated',
        );

        $route =  $this->getUrl('question_update', array('id' => 1));
        $this->client->request('PUT', $route, array(), array(), array(), $data);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 204);



        // Check that resource has been updated correctly
        $route =  $this->getUrl('question_show', array('id' => 1));
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

        $route =  $this->getUrl('question_upload', array('id' => 1));
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

        $route =  $this->getUrl('question_remove', array('id' => 1));
        $this->client->request('DELETE', $route);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 410);



        // Return to the index and check that resource has been removed
        $route =  $this->getUrl('question_index');
        $this->client->request('GET', $route);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);

        $content = json_decode($response->getContent());
        $this->assertCount(9, $content);
    }
}
