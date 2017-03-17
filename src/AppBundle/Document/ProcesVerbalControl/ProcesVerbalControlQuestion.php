<?php

namespace AppBundle\Document\ProcesVerbalControl;

use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;

class ProcesVerbalControlQuestion
{
    /**
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     */
    private $question;

    /**
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     */
    private $answer;

    /**
     * @var type
     * @Type("string")
     */
    private $observations;

    public function getQuestion()
    {
        return $this->question;
    }

    public function getAnswer()
    {
        return $this->answer;
    }

    public function getObservations()
    {
        return $this->observations;
    }

    public function setQuestion($question)
    {
        $this->question = $question;
    }

    public function setAnswer($answer)
    {
        $this->answer = $answer;
    }

    public function setObservations($observations)
    {
        $this->observations = $observations;
    }

}