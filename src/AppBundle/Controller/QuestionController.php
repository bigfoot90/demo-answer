<?php

/**
 * @author Damian Dlugosz <bigfootdd@gmail.com>
 */

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Question;
use AppBundle\Entity\Attachment;
use AppBundle\Entity\QuestionAttachment;

class QuestionController extends RestController
{
    public function indexAction()
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:Question');

        return $repo->findAll();
    }

    public function searchAction($keywords)
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:Question');

        return $repo->search($keywords);
    }

    public function mostSearchedAction()
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:Question');

        return $repo->mostSearched();
    }

    public function mostViewedAction()
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:Question');

        return $repo->mostViewed();
    }

    public function newestAction()
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:Question');

        return $repo->newest();
    }

    public function showAction(Question $question)
    {
        return $question;
    }

    public function createAction(Request $request)
    {
        $question = new Question();
        $this->handleRequest($question, $request);

        $violations = $this->validate($question);

        if (count($violations) > 0) {
            return $this->resourceValidationFailedResponse($violations);
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($question);
        $manager->flush();

        return $this->resourceCreatedResponse($question->getId());
    }

    public function updateAction(Request $request, Question $question)
    {
        $this->handleRequest($question, $request);

        $violations = $this->validate($question);

        if (count($violations) > 0) {
            return $this->resourceValidationFailedResponse($violations);
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($question);
        $manager->flush();

        return $this->resourceUpdatedResponse();
    }

    public function uploadAction(Request $request, Question $question)
    {
        $attachment = new QuestionAttachment();
        $attachment->setQuestion($question);

        $this->handleUploadRequest($attachment, $request);

        $violations = $this->validate($question);

        if (count($violations) > 0) {
            return $this->resourceValidationFailedResponse($violations);
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($attachment);
        $manager->flush();

        return $this->resourceUploadedResponse($attachment);
    }

    public function removeAction(Question $question)
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($question);
        $manager->flush();

        return $this->resourceRemovedResponse();
    }

    private function handleRequest(Question $entity, Request $request)
    {
        $data = $request->getContent();

        if (isset($data['title'])) $entity->setTitle($data['title']);
        if (isset($data['content'])) $entity->setContent($data['content']);
        if (isset($data['created_by'])) $entity->setCreatedBy($data['created_by']);
    }

    private function handleUploadRequest(Attachment $attachment, Request $request)
    {
        if (!$request->files->has(0)) {
            return;
        }

        /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
        $file = $request->files->get(0);

        $attachment->setBinaryContent($file);
        $attachment->setContext('attachment');
    }
}
