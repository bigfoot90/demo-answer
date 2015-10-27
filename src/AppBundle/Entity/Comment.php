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
     *
     * @SER\Type("integer")
     * @SER\ReadOnly()
     */
    protected $id;

    /**
     * @var Answer
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Answer", inversedBy="comments")
     * @ORM\JoinColumn(onDelete="cascade")
     *
     * @Assert\NotNull()
     *
     * @SER\Exclude()
     */
    protected $answer;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     *
     * @Assert\NotBlank()
     */
    protected $text;

    /**
     * @var ArrayCollection|CommentAttachment[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CommentAttachment", mappedBy="comment", cascade={"all"}, orphanRemoval=true)
     *
     * @SER\SerializedName("files")
     */
    protected $attachments;

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
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return ArrayCollection|CommentAttachment[]
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @param QuestionAttachment $attachment
     */
    public function addAttachment(QuestionAttachment $attachment)
    {
        if (!$this->attachments->contains($attachment)) {
            $this->attachments->add($attachment);
            $attachment->setQuestion($this);
        }
    }

    /**
     * @param QuestionAttachment $attachment
     */
    public function removeAttachment(QuestionAttachment $attachment)
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
