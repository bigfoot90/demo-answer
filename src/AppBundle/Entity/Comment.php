<?php

/**
 * @author Damian Dlugosz <d.dlugosz@bestnetwork.it>
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 */
class Comment
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", options={"unsigned"=true})
     */
    protected $id;

    /**
     * @var Answer
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Answer", inversedBy="comments")
     * @ORM\JoinColumn(onDelete="cascade")
     */
    protected $answer;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $text;

    /**
     * @var ArrayCollection|CommentMedia[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CommentMedia", mappedBy="comment", cascade={"all"}, orphanRemoval=true)
     */
    protected $attachments;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $createdBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    public function __construct()
    {
        $this->attachments = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Answer
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * @param Answer $answer
     */
    public function setAnswer(Answer $answer)
    {
        $this->answer = $answer;
    }

    /**
     * @return ArrayCollection|CommentMedia[]
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @param CommentMedia $attachment
     */
    public function addAttachment(CommentMedia $attachment)
    {
        if (!$this->attachments->contains($attachment)) {
            $this->attachments->add($attachment);
            $attachment->setQuestion($this);
        }
    }

    /**
     * @param CommentMedia $attachment
     */
    public function removeAttachment(CommentMedia $attachment)
    {
        if ($this->attachments->contains($attachment)) {
            $this->attachments->removeElement($attachment);
        }
    }

    /**
     * @return string
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param string $createdBy
     * @return $this
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
