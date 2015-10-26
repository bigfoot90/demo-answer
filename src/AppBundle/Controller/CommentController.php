<?php

/**
 * @author Damian Dlugosz <d.dlugosz@bestnetwork.it>
 */

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Comment;

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
        $this->handleRequest($comment, $request->getContent());

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
        $this->handleRequest($comment, $request->getContent());

        $violations = $this->validate($comment);

        if (count($violations) > 0) {
            return $this->resourceValidationFailedResponse($violations);
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($comment);
        $manager->flush();

        return $this->resourceUpdatedResponse();
    }

    public function removeAction(Comment $comment)
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($comment);
        $manager->flush();

        return $this->resourceRemovedResponse();
    }

    private function handleRequest(Comment $entity, array $data)
    {
        if (isset($data['answer'])) $entity->setAnswer($this->findAnswer($data['answer']));
        if (isset($data['text'])) $entity->setText($data['text']);
        if (isset($data['created_by'])) $entity->setCreatedBy($data['created_by']);
    }

    private function findAnswer($id)
    {
        return $this->getDoctrine()->getRepository('AppBundle:Answer')->find($id);
    }
}
