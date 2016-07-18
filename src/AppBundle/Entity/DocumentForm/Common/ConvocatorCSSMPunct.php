<?php

namespace AppBundle\Entity\DocumentForm\Common;

use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;

class ConvocatorCSSMPunct
{
    /**
     *
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     */
    private $meetingPoint;

    /**
     * @var type
     * @Type("string")
     */
    private $meetingPointSummary;

    public function getMeetingPoint()
    {
        return $this->meetingPoint;
    }

    public function getMeetingPointSummary()
    {
        return $this->meetingPointSummary;
    }

    public function setMeetingPoint($meetingPoint)
    {
        $this->meetingPoint = $meetingPoint;
    }

    public function setMeetingPointSummary($meetingPointSummary)
    {
        $this->meetingPointSummary = $meetingPointSummary;
    }

}