<?php

/**
 * @author Damian Dlugosz <d.dlugosz@bestnetwork.it>
 */

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Answer;

class AnswerController extends RestController
{
    public function indexAction()
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:Answer');

        return $repo->findAll();
    }

    public function showAction(Answer $answer)
    {
        return $answer;
    }

    public function createAction(Request $request)
    {
        $answer = new Answer();
        $this->handleRequest($answer, $request->getContent());

        $violations = $this->validate($answer);

        if (count($violations) > 0) {
            return $this->resourceValidationFailedResponse($violations);
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($answer);
        $manager->flush();

        return $this->resourceCreatedResponse($answer->getId());
    }

    public function updateAction(Request $request, Answer $answer)
    {
        $this->handleRequest($answer, $request->getContent());

        $violations = $this->validate($answer);

        if (count($violations) > 0) {
            return $this->resourceValidationFailedResponse($violations);
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($answer);
        $manager->flush();

        return $this->resourceUpdatedResponse();
    }

    public function removeAction(Answer $answer)
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($answer);
        $manager->flush();

        return $this->resourceRemovedResponse();
    }

    private function handleRequest(Answer $entity, array $data)
    {
        if (isset($data['question'])) $entity->setQuestion($this->findQuestion($data['question']));
        if (isset($data['title'])) $entity->setTitle($data['title']);
        if (isset($data['content'])) $entity->setContent($data['content']);
        if (isset($data['created_by'])) $entity->setCreatedBy($data['created_by']);
    }

    private function findQuestion($id)
    {
        return $this->getDoctrine()->getRepository('AppBundle:Question')->find($id);
    }
}
