<?php

namespace AppBundle\Service\DocumentForm\Base;

interface FormularFormConfigValueInterface
{

    public function getParameters();

    public function getValuesForFormConfig($formConfig);

}