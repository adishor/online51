<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Entity\Formular;
use Symfony\Component\HttpFoundation\Request;

class FormularController extends Controller
{

    /**
     * @Route("/show/{formular}", name="formular_show")
     * @ParamConverter("formular", options={"mapping": {"formular": "slug"}})
     */
    public function showFormularAction(Formular $formular, Request $request)
    {
        $name = str_replace("_", "", $formular->getSlug());
        $entity = "AppBundle\\Entity\\DocumentForm\\" . $name;
        $type = "AppBundle\\Form\\Type\\DocumentForm\\" . $name . "Type";

        $formValue = new $entity();

        $form = $this->createForm(new $type(), $formValue);

        $form->handleRequest($request);

        return $this->render('document_form/' . strtolower($formular->getSlug()) . ".html.twig", array(
              'form' => $form->createView()
            )
        );
    }

}