<?php

/**
 * @author Damian Dlugosz <d.dlugosz@bestnetwork.it>
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class CommentAttachment extends Attachment
{
    /**
     * @var Comment
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Comment", inversedBy="attachments")
     *
     * @Assert\NotNull()
     */
    protected $comment;

    /**
     * @return Comment
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param Comment $comment
     */
    public function setComment(Comment $comment)
    {
        $this->comment = $comment;
    }
}
