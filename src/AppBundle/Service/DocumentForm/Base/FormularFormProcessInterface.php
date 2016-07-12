<?php

namespace AppBundle\Service\DocumentForm\Base;

interface FormularFormProcessInterface
{

    public function processHandleForm($creditsUsage, $flow, &$formData);

    public function processEndHandleForm(&$formData);
}