<?php

/**
 * @author Damian Dlugosz <d.dlugosz@bestnetwork.it>
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Answer
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
     * @var Question
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Question", inversedBy="answers")
     * @ORM\JoinColumn(onDelete="cascade")
     */
    protected $question;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $content;

    /**
     * @var ArrayCollection|AnswerMedia[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\AnswerMedia", mappedBy="answer", cascade={"all"}, orphanRemoval=true)
     */
    protected $attachments;

    /**
     * @var ArrayCollection|Comment[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Comment", mappedBy="answer", cascade={"persist"})
     */
    protected $comments;

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
        $this->comments = new ArrayCollection();
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
     * @return Question
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param Question $question
     */
    public function setQuestion(Question $question)
    {
        $this->question = $question;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return ArrayCollection|AnswerMedia[]
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @param AnswerMedia $attachment
     */
    public function addAttachment(AnswerMedia $attachment)
    {
        if (!$this->attachments->contains($attachment)) {
            $this->attachments->add($attachment);
            $attachment->setQuestion($this);
        }
    }

    /**
     * @param AnswerMedia $attachment
     */
    public function removeAttachment(AnswerMedia $attachment)
    {
        if ($this->attachments->contains($attachment)) {
            $this->attachments->removeElement($attachment);
        }
    }

    /**
     * @return ArrayCollection|Comment[]
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param Comment $comment
     */
    public function addComment(Comment $comment)
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setAnswer($this);
        }
    }

    /**
     * @param Comment $comment
     */
    public function removeComment(Comment $comment)
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
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
