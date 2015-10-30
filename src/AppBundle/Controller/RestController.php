<?php

/**
 * @author Damian Dlugosz <d.dlugosz@bestnetwork.it>
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\ConstraintViolationListInterface;

use AppBundle\Entity\Attachment;

abstract class RestController extends Controller
{
    /**
     * @param $resourceId
     * @return JsonResponse
     */
    protected function resourceCreatedResponse($resourceId)
    {
        return new JsonResponse(array('id' => $resourceId), JsonResponse::HTTP_CREATED);
    }

    /**
     * @return JsonResponse
     */
    protected function resourceUpdatedResponse()
    {
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * @param Attachment $attachment
     * @return JsonResponse
     */
    protected function resourceUploadedResponse(Attachment $attachment)
    {
        return new JsonResponse(array('url' => $this->getAttachmentPublicUrl($attachment, 'reference')), JsonResponse::HTTP_CREATED);
    }

    /**
     * @param ConstraintViolationListInterface $violationList
     * @return JsonResponse
     */
    protected function resourceValidationFailedResponse(ConstraintViolationListInterface $violationList)
    {
        $errors = array();

        foreach ($violationList as $violation) {
            $errors[] = array(
                'property' => $violation->getPropertyPath(),
                'message' => $violation->getMessage(),
                'value' => $violation->getInvalidValue(),
            );
        }

        return new JsonResponse($errors, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @return JsonResponse
     */
    protected function resourceRemovedResponse()
    {
        return new JsonResponse(null, JsonResponse::HTTP_GONE);
    }

    /**
     * @param object $entity
     * @return ConstraintViolationListInterface
     */
    protected function validate($entity)
    {
        return $this->get('validator')->validate($entity);
    }

    protected function getAttachmentPublicUrl(Attachment $attachment, $format = null)
    {
        $provider = $this->container->get($attachment->getProviderName());

        return $provider->generatePublicUrl($attachment, $format);
    }
}
