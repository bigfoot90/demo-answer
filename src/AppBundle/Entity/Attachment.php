<?php

/**
 * @author Damian Dlugosz <bigfootdd@gmail.com>
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Sonata\MediaBundle\Entity\BaseMedia;

/**
 * @ORM\Table()
 * @ORM\Entity()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"Q" = "QuestionAttachment", "A" = "AnswerAttachment", "C" = "CommentAttachment"})
 */
abstract class Attachment extends BaseMedia
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
     * @var string
     *
     * @Assert\NotNull()
     */
    protected $name;

    /**
     * @var string
     *
     * @Assert\NotNull()
     */
    protected $providerName;

    /**
     * @var \Symfony\Component\HttpFoundation\File\File
     *
     * @Assert\NotNull()
     */
    protected $binaryContent;

    public function __construct()
    {
        $this->context = 'default';
        $this->providerName = 'sonata.media.provider.file';
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
