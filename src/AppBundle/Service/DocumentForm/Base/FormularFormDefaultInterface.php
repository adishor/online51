<?php

namespace AppBundle\Service\DocumentForm\Base;

interface FormularFormDefaultInterface
{

    public function applyDefaultFormData($creditsUsage, $formData, $user);
}