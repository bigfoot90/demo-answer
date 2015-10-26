<?php

/**
 * @author Damian Dlugosz <d.dlugosz@bestnetwork.it>
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as SER;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Question
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", options={"unsigned"=true})
     *
     * @SER\Type("integer")
     * @SER\ReadOnly()
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     *
     * @Assert\NotBlank()
     */
    protected $content;

    /**
     * @var ArrayCollection|QuestionMedia[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\QuestionMedia", mappedBy="question", cascade={"all"}, orphanRemoval=true)
     *
     * @SER\SerializedName("files")
     */
    protected $attachments;

    /**
     * @var ArrayCollection|Answer[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Answer", mappedBy="question", cascade={"persist"})
     *
     * @SER\Exclude()
     */
    protected $answers;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     */
    protected $createdBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @SER\ReadOnly()
     */
    protected $createdAt;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
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
     * @return ArrayCollection|CommentMedia[]
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @param QuestionMedia $media
     */
    public function addAttachment(QuestionMedia $media)
    {
        if (!$this->attachments->contains($media)) {
            $this->attachments->add($media);
            $media->setQuestion($this);
        }
    }

    /**
     * @param QuestionMedia $media
     */
    public function removeAttachment(QuestionMedia $media)
    {
        if ($this->attachments->contains($media)) {
            $this->attachments->removeElement($media);
        }
    }

    /**
     * @return ArrayCollection|Answer[]
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * @param Answer $answer
     */
    public function addAnswer(Answer $answer)
    {
        if (!$this->answers->contains($answer)) {
            $this->answers->add($answer);
            $answer->setQuestion($this);
        }
    }

    /**
     * @param Answer $answer
     */
    public function removeAnswer(Answer $answer)
    {
        if ($this->answers->contains($answer)) {
            $this->answers->removeElement($answer);
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
