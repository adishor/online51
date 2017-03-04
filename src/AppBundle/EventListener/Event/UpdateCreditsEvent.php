<?php
/**
 * Created by PhpStorm.
 * User: adriancalugar
 * Date: 10/02/2017
 * Time: 18:00
 */

namespace AppBundle\EventListener\Event;

use Symfony\Component\EventDispatcher\Event;

class UpdateCreditsEvent extends Event
{
    private $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function getUserId()
    {
        return $this->userId;
    }
}