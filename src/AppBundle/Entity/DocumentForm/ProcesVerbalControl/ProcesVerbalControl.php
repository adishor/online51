<?php

namespace AppBundle\Entity\DocumentForm\ProcesVerbalControl;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\Type;
use AppBundle\Entity\DocumentForm\Common\Person;
use AppBundle\Entity\DocumentForm\ProcesVerbalControl\ProcesVerbalControlQuestion;

class ProcesVerbalControl
{
    /**
     * @var array
     * @Type("array")
     */
    static public $uniqueness = null;

    /**
     * @var type
     * @Type("boolean")
     */
    static public $oneStepFormConfig = TRUE;

    /**
     * @var type
     * @Type("DateTime")
     * @Assert\NotBlank()
     * @Assert\DateTime()
     */
    protected $controlDate;

    /**
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     */
    protected $controlBy;

    /**
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     */
    protected $company;

    /**
     * @var type
     * @Type("array<AppBundle\Entity\DocumentForm\Common\Person>")
     */
    protected $administrator;

    /**
     *
     * @var type
     * @Type("array<AppBundle\Entity\DocumentForm\Common\Person>")
     * @Assert\Count(min="1")
     */
    protected $participants;

    /**
     *
     * @var type
     * @Type("array<AppBundle\Entity\DocumentForm\ProcesVerbalControl\ProcesVerbalControlQuestion>")
     * @Assert\Count(min="1")
     */
    protected $organizationalQuestions;

    /**
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     */
    protected $findings;

    /**
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     */
    protected $proposedMeasures;

    public function __construct()
    {
        $this->organizationalQuestions = new ArrayCollection();
        $x1 = new ProcesVerbalControlQuestion();
        $x1->setQuestion('formular.proces-verbal-control.aspect1');
        $x1->setAnswer(NULL);
        $x1->setObservations('');
        $x2 = new ProcesVerbalControlQuestion();
        $x2->setQuestion('formular.proces-verbal-control.aspect2');
        $x2->setAnswer(NULL);
        $x2->setObservations('');
        $x3 = new ProcesVerbalControlQuestion();
        $x3->setQuestion('formular.proces-verbal-control.aspect3');
        $x3->setAnswer(NULL);
        $x3->setObservations('');
        $x4 = new ProcesVerbalControlQuestion();
        $x4->setQuestion('formular.proces-verbal-control.aspect4');
        $x4->setAnswer(NULL);
        $x4->setObservations('');
        $x5 = new ProcesVerbalControlQuestion();
        $x5->setQuestion('formular.proces-verbal-control.aspect5');
        $x5->setAnswer(NULL);
        $x5->setObservations('');
        $x6 = new ProcesVerbalControlQuestion();
        $x6->setQuestion('formular.proces-verbal-control.aspect6');
        $x6->setAnswer(NULL);
        $x6->setObservations('');
        $x7 = new ProcesVerbalControlQuestion();
        $x7->setQuestion('formular.proces-verbal-control.aspect7');
        $x7->setAnswer(NULL);
        $x7->setObservations('');
        $x8 = new ProcesVerbalControlQuestion();
        $x8->setQuestion('formular.proces-verbal-control.aspect8');
        $x8->setAnswer(NULL);
        $x8->setObservations('');
        $x9 = new ProcesVerbalControlQuestion();
        $x9->setQuestion('formular.proces-verbal-control.aspect9');
        $x9->setAnswer(NULL);
        $x9->setObservations('');
        $x10 = new ProcesVerbalControlQuestion();
        $x10->setQuestion('formular.proces-verbal-control.aspect10');
        $x10->setAnswer(NULL);
        $x10->setObservations('');
        $x11 = new ProcesVerbalControlQuestion();
        $x11->setQuestion('formular.proces-verbal-control.aspect11');
        $x11->setAnswer(NULL);
        $x11->setObservations('');
        $x12 = new ProcesVerbalControlQuestion();
        $x12->setQuestion('formular.proces-verbal-control.aspect12');
        $x12->setAnswer(NULL);
        $x12->setObservations('');
        $x13 = new ProcesVerbalControlQuestion();
        $x13->setQuestion('formular.proces-verbal-control.aspect3');
        $x13->setAnswer(NULL);
        $x13->setObservations('');
        $x14 = new ProcesVerbalControlQuestion();
        $x14->setQuestion('formular.proces-verbal-control.aspect14');
        $x14->setAnswer(NULL);
        $x14->setObservations('');

        $organizationalQuestionss = [$x1, $x2, $x3, $x4, $x5, $x6, $x7, $x8, $x9, $x10, $x11, $x12, $x13, $x14];
        $this->organizationalQuestions = $organizationalQuestionss;

        $y = new Person();
        $y->setGender('');
        $y->setName('');
        $y->setFunction('');

        $this->participants = new ArrayCollection();
        $participants = [$y];
        $this->participants = $participants;

        $this->administrator = new ArrayCollection();
        $administrator = [$y];
        $this->administrator = $administrator;
    }

    public function getControlDate()
    {
        return $this->controlDate;
    }

    public function getControlBy()
    {
        return $this->controlBy;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function getAdministrator()
    {
        return $this->administrator;
    }

    public function getParticipants()
    {
        return $this->participants;
    }

    public function getOrganizationalQuestions()
    {
        return $this->organizationalQuestions;
    }

    public function getFindings()
    {
        return $this->findings;
    }

    public function getProposedMeasures()
    {
        return $this->proposedMeasures;
    }

    public function setControlDate($controlDate)
    {
        $this->controlDate = $controlDate;
    }

    public function setControlBy($controlBy)
    {
        $this->controlBy = $controlBy;
    }

    public function setCompany($company)
    {
        $this->company = $company;
    }

    public function setAdministrator($administrator)
    {
        $this->administrator = $administrator;
    }

    public function setParticipants($participants)
    {
        $this->participants = $participants;
    }

    public function setOrganizationalQuestions($organizationalQuestions)
    {
        $this->organizationalQuestions = $organizationalQuestions;
    }

    public function setFindings($findings)
    {
        $this->findings = $findings;
    }

    public function setProposedMeasures($proposedMeasures)
    {
        $this->proposedMeasures = $proposedMeasures;
    }

}