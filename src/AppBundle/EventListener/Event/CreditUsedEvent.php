<?php
/**
 * Created by PhpStorm.
 * User: adriancalugar
 * Date: 10/02/2017
 * Time: 18:00
 */

namespace AppBundle\EventListener\Event;

use Symfony\Component\EventDispatcher\Event;

class CreditUsedEvent extends Event
{
    private $credits;

    public function __construct($credits)
    {
        $this->credits = $credits;
    }
}