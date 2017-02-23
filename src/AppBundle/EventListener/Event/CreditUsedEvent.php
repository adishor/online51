<?php
/**
 * Created by PhpStorm.
 * User: adriancalugar
 * Date: 10/02/2017
 * Time: 18:00
 */

namespace AppBundle\EventListener\Event;

use AppBundle\Entity\File;
use Symfony\Component\EventDispatcher\Event;

class CreditUsedEvent extends Event
{
    private $file;

    public function __construct(File $file)
    {
        $this->file = $file;
    }

    public function getFile()
    {
        return $this->file;
    }
}