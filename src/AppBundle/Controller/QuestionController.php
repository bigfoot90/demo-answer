<?php

/**
 * @author Damian Dlugosz <d.dlugosz@bestnetwork.it>
 */

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Question;

class QuestionController extends RestController
{
    public function indexAction()
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:Question');

        return $repo->findAll();
    }

    public function showAction(Question $question)
    {
        return $question;
    }

    public function createAction(Request $request)
    {
        $question = new Question();
        $this->handleRequest($question, $request->getContent());

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
        $this->handleRequest($question, $request->getContent());

        $violations = $this->validate($question);

        if (count($violations) > 0) {
            return $this->resourceValidationFailedResponse($violations);
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($question);
        $manager->flush();

        return $this->resourceUpdatedResponse();
    }

    public function removeAction(Question $question)
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($question);
        $manager->flush();

        return $this->resourceRemovedResponse();
    }

    private function handleRequest(Question $entity, array $data)
    {
        if (isset($data['title'])) $entity->setTitle($data['title']);
        if (isset($data['content'])) $entity->setContent($data['content']);
        if (isset($data['created_by'])) $entity->setCreatedBy($data['created_by']);
    }
}
