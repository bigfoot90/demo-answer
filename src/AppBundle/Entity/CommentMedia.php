<?php

/**
 * @author Damian Dlugosz <d.dlugosz@bestnetwork.it>
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class CommentMedia extends Media
{
    /**
     * @var Comment
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Comment", inversedBy="attachments")
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
