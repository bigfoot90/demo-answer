<?php

/**
 * @author Damian Dlugosz <d.dlugosz@bestnetwork.it>
 */

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Attachment;
use AppBundle\Entity\CommentAttachment;

class CommentController extends RestController
{
    public function indexAction()
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:Comment');

        return $repo->findAll();
    }

    public function showAction(Comment $comment)
    {
        return $comment;
    }

    public function createAction(Request $request)
    {
        $comment = new Comment();
        $this->handleRequest($comment, $request);

        $violations = $this->validate($comment);

        if (count($violations) > 0) {
            return $this->resourceValidationFailedResponse($violations);
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($comment);
        $manager->flush();

        return $this->resourceCreatedResponse($comment->getId());
    }

    public function updateAction(Request $request, Comment $comment)
    {
        $this->handleRequest($comment, $request);

        $violations = $this->validate($comment);

        if (count($violations) > 0) {
            return $this->resourceValidationFailedResponse($violations);
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($comment);
        $manager->flush();

        return $this->resourceUpdatedResponse();
    }

    public function uploadAction(Request $request, Comment $comment)
    {
        $attachment = new CommentAttachment();
        $attachment->setComment($comment);

        $this->handleUploadRequest($attachment, $request);

        $violations = $this->validate($comment);

        if (count($violations) > 0) {
            return $this->resourceValidationFailedResponse($violations);
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($attachment);
        $manager->flush();

        return $this->resourceUploadedResponse($attachment);
    }

    public function removeAction(Comment $comment)
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($comment);
        $manager->flush();

        return $this->resourceRemovedResponse();
    }

    private function handleRequest(Comment $entity, Request $request)
    {
        $data = $request->getContent();

        if (isset($data['answer'])) $entity->setAnswer($this->findAnswer($data['answer']));
        if (isset($data['text'])) $entity->setText($data['text']);
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

    private function findAnswer($id)
    {
        return $this->getDoctrine()->getRepository('AppBundle:Answer')->find($id);
    }
}
