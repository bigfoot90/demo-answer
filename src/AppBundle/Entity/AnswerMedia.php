<?php

/**
 * @author Damian Dlugosz <d.dlugosz@bestnetwork.it>
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class AnswerMedia extends Media
{
    /**
     * @var Answer
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Answer", inversedBy="attachments")
     */
    protected $answer;

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
}
