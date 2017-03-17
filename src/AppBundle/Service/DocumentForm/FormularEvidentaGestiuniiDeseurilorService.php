<?php

namespace AppBundle\Service\DocumentForm;

use AppBundle\Document\EvidentaGestiuniiDeseurilor\EvidentaGestiuniiDeseurilor;
use AppBundle\Entity\CreditsUsage;
use AppBundle\Entity\EgdFormularConfig;
use AppBundle\Entity\EgdFormularCreditsUsage;
use AppBundle\Entity\Formular;
use AppBundle\Entity\FormularConfig;
use AppBundle\Entity\FormularCreditsUsage;
use AppBundle\Service\DocumentForm\Base\FormularGeneric;
use Doctrine\ORM\EntityManager;
use JMS\Serializer\Serializer;
use Symfony\Component\Yaml\Yaml;
use AppBundle\Service\DocumentForm\Base\FormularFormConfigTextInterface;
use AppBundle\Service\DocumentForm\Base\FormularFormConfigValueInterface;
use AppBundle\Service\DocumentForm\Base\FormularFormDefaultInterface;
use AppBundle\Service\DocumentForm\Base\FormularFormChangeStructureInterface;
use AppBundle\Service\DocumentForm\Base\FormularFormTemplateInterface;
use AppBundle\Service\DocumentForm\Base\FormularFormProcessInterface;
use AppBundle\Document\EvidentaGestiuniiDeseurilor\EGDCompany;

class FormularEvidentaGestiuniiDeseurilorService extends FormularGeneric
{

    public function __construct(EntityManager $entityManager, Serializer $jmsSerializer, $fileLocator)
    {
        parent::__construct($entityManager, $jmsSerializer, $fileLocator);

        $this->setUnique();
        $this->setHasController();
    }


    public function getUniquenessValues(Formular $formular)
    {
        $parameters = $this->getParameters();

        $uniqueValues = array(
            'tip_deseu' => $parameters['tip_deseu'],
            'an' => array(),
        );

        $currYear = date('Y');
        $currMonth = date('n');

        for ($i = EvidentaGestiuniiDeseurilor::$startYear; $i <= $currYear - 1; $i++) {
            $uniqueValues['an'][$i] = $i;
        }

        if ($currMonth >= EvidentaGestiuniiDeseurilor::$startMonth) {
            $uniqueValues['an'][$currYear] = $currYear;
        }

        return $uniqueValues;
    }


    public function getTextForFormConfig($formConfig, $short = false)
    {
        $formConfigD = json_decode($formConfig);
        $formConfigValue = $this->getValuesForFormConfig($formConfig);

        // && - special character separator for dropdown display for full configuration
        return array(
            'message' => (!$short) ? 'document-form.text.egd-full' : 'document-form.text.egd',
            'variables' => array(
                'waste-type' => ($short) ? $formConfigValue['tip_deseu_cod'] : $formConfigValue['tip_deseu_cod'] . "&&" . $formConfigValue['tip_deseu'],
                'operation' => isset($formConfigD->operatia) ? $formConfigValue['operatia'] : 'neselectat'
            )
        );
    }

    public function applyDefaultFormData(CreditsUsage $creditsUsage, $user)
    {
        $entityNamespace = $this->getEntity();
        $entity = new $entityNamespace();

        $formConfigValue = $this->getValuesForFormConfig($creditsUsage->getFormularConfig()->getFormConfig());

        $entity->setAgentEconomic($user->getProfile() ? $user->getProfile()->getCompany() : "");
        $entity->setAn($formConfigValue['an']);
        $entity->setTipDeseu($formConfigValue['tip_deseu']);
        $entity->setTipDeseuCod($formConfigValue['tip_deseu_cod']);

        $creditsUsage->getFormularConfig()->setFormData($this->jmsSerializer->serialize($entity, 'json'));

        $this->entityManager->persist($creditsUsage);
        $this->entityManager->flush();

        return $entity;
    }

    private function getParameters()
    {
        $resourcePath = $this->fileLocator->locate('@AppBundle/Resources/config/deseuri/evidenta_gestiunii_deseurilor.yml');
        $configValues = Yaml::parse(file_get_contents($resourcePath));

        return $configValues;
    }

    public function getValuesForFormConfig($formConfig)
    {
        $formConfigValue = [];

        $formConfigD = json_decode($formConfig);
        if (count($formConfig)) {
            $parameters = $this->getParameters();

            foreach ($formConfigD as $key => $config) {
                switch ($key) {
                    case 'an':
                        $formConfigValue[$key] = $config;
                        break;
                    case 'tip_deseu':
                        $deseuCodes = explode(" ", $config);
                        $tipDeseuArray = $parameters['tip_deseu'];
                        $formConfigValue[$key] = $tipDeseuArray[$deseuCodes[0]]['name'] . "|" .
                          $tipDeseuArray[$deseuCodes[0]]['values'][$deseuCodes[1]]['name'] . "||" .
                          $tipDeseuArray[$deseuCodes[0]]['values'][$deseuCodes[1]]['values'][$deseuCodes[2]];
                        $formConfigValue[$key . "_cod"] = $config;
                        break;
                    case 'operatia':
                        $formConfigValue[$key] = $parameters['operatia'][$config];
                        break;
                    case 'currentStepNumber':
                        $formConfigValue[$key] = $config;
                        break;
                }
            }
        }

        return $formConfigValue;
    }




    public function calculateExtraTemplateData($formData)
    {
        $formTemplateData = array();

        $EGDTotals = array();
        $EGD1 = $formData->getEGD1GenerareDeseuri();
        $EGD2 = $formData->getEGD2StocareTratareTransportDeseuri();
        $EGD3 = $formData->getEGD3ValorificareDeseuri();
        $EGD4 = $formData->getEGD4EliminareDeseuri();
        $lastYearInStock = $formData->getLastYearInStock();
        $EGD1CantitateDeseuGenerateTotal = 0;
        $EGD1CantitateDeseuValorificataTotal = 0;
        $EGD1CantitateDeseuEliminataTotal = 0;
        $EGD1CantitateDeseuInStocTotal = 0;
        $EGD1CantitateDeseuInStocValorificata = [];
        $EGD1CantitateDeseuInStocEliminata = [];
        $EGD2StocareCantitateTotal = 0;
        $EGD2TratareCantitateTotal = 0;
        $EGD3CantitateDeseuValorificataTotal = 0;
        $EGD4CantitateDeseuEliminataTotal = 0;
        foreach ($formData->luni as $key => $luna) {
            $valueLastYearInStock = ($key == 0) ? $lastYearInStock : 0;
            $EGD1CantitateDeseuGenerateTotal += $EGD1[$key]->getCantitateDeseuGenerate();
            $EGD1CantitateDeseuValorificataTotal += $EGD1[$key]->getCantitateDeseuValorificata();
            $EGD1CantitateDeseuEliminataTotal += $EGD1[$key]->getCantitateDeseuEliminata();
            $EGD2TratareCantitateTotal += $EGD2[$key]->getTratareCantitate();
            $EGD3CantitateDeseuValorificataTotal += $EGD3[$key]->getCantitateDeseuValorificata();
            $EGD4CantitateDeseuEliminataTotal += $EGD4[$key]->getCantitateDeseuEliminata();
            $EGD1CantitateDeseuInStocValorificata[$key] = round($EGD1[$key]->getCantitateDeseuGenerate() - $EGD1[$key]->getCantitateDeseuValorificata() + $valueLastYearInStock + (($key > 0) ? $EGD1CantitateDeseuInStocValorificata[$key - 1] : 0), 2);
            $EGD1CantitateDeseuInStocEliminata[$key] = round($EGD1[$key]->getCantitateDeseuGenerate() - $EGD1[$key]->getCantitateDeseuEliminata() + $valueLastYearInStock + (($key > 0) ? $EGD1CantitateDeseuInStocEliminata[$key - 1] : 0), 2);
        }
        if ($formData->getOperatia() == 3) {
            $EGD1CantitateDeseuInStocTotal = $EGD1CantitateDeseuInStocValorificata[count($formData->luni) - 1];
        }
        if ($formData->getOperatia() == 4) {
            $EGD1CantitateDeseuInStocTotal = $EGD1CantitateDeseuInStocEliminata[count($formData->luni) - 1];
        }
        $EGD2StocareCantitateTotal = $EGD1CantitateDeseuInStocTotal;
        $EGDTotals['EGD1CantitateDeseuGenerateTotal'] = $EGD1CantitateDeseuGenerateTotal;
        $EGDTotals['EGD1CantitateDeseuValorificataTotal'] = $EGD1CantitateDeseuValorificataTotal;
        $EGDTotals['EGD1CantitateDeseuEliminataTotal'] = $EGD1CantitateDeseuEliminataTotal;
        $EGDTotals['EGD1CantitateDeseuInStocTotal'] = $EGD1CantitateDeseuInStocTotal;
        $EGDTotals['EGD1CantitateDeseuInStocValorificata'] = $EGD1CantitateDeseuInStocValorificata;
        $EGDTotals['EGD1CantitateDeseuInStocEliminata'] = $EGD1CantitateDeseuInStocEliminata;
        $EGDTotals['EGD2StocareCantitateTotal'] = $EGD2StocareCantitateTotal;
        $EGDTotals['EGD2TratareCantitateTotal'] = $EGD2TratareCantitateTotal;
        $EGDTotals['EGD3CantitateDeseuValorificataTotal'] = $EGD3CantitateDeseuValorificataTotal;
        $EGDTotals['EGD4CantitateDeseuEliminataTotal'] = $EGD4CantitateDeseuEliminataTotal;
        $formTemplateData['EGDTotals'] = $EGDTotals;

        return $formTemplateData;
    }
//
//    public function processHandleForm($creditsUsage, $flow, &$modelData)
//    {
//
//        if ($modelData->getCurrentStep() < $flow->getCurrentStep()) {
//            $modelData->setCurrentStep($flow->getCurrentStep());
//        }
//
//
//        if ($flow->getCurrentStep() == 1) {
//            $formConfig = json_decode($creditsUsage->getFormularConfig()->getFormConfig());
//            if (isset($formConfig->operatia)) {
//                if ($formConfig->operatia === '3') {
//                    $form->remove('operatiaDeEliminare');
//                }
//                if ($formConfig->operatia === '4') {
//                    $form->remove('operatiaDeValorificare');
//                }
//            }
//        }
//
//        if ($flow->getCurrentStep() == 1 && $creditsUsage->getFormularConfig()->getIsFormConfigFinished()) {
//            $formConfig = $this->getValuesForFormConfig($creditsUsage->getFormularConfig()->getFormConfig());
//            $formConfig['tip_deseu'] = $formConfig['tip_deseu_cod'];
//            unset($formConfig['tip_deseu_cod']);
//            $formConfig['operatia'] = $modelData->getOperatia();
//            $creditsUsage->getFormularConfig()->setFormConfig(json_encode($formConfig));
//        }
//
//        if ($flow->getCurrentStep() == ($creditsUsage->getFormularConfig()->getIsFormConfigFinished() ? 2 : 1)) {
//
//            $companies = [];
//            foreach ($modelData->getEGDCompany() as $company) {
//                $companies[] = $company;
//            }
//            $modelData->setEGDCompany($companies);
//
//            foreach ($modelData->getEGD1GenerareDeseuri() as $key => $item) {
//
//                $agents = [];
//                if (count($modelData->getEGDCompany()) == 1) {
//                    $company = $modelData->getEGDCompany();
//                    $company = reset($company);
//                    if ($modelData->getOperatia() == 3) {
//                        $company->setCantitateDeseu($modelData->getEGD3ValorificareDeseuri()[$key]->getCantitateDeseuValorificata());
//                    }
//                    if ($modelData->getOperatia() == 4) {
//                        $company->setCantitateDeseu($modelData->getEGD4EliminareDeseuri()[$key]->getCantitateDeseuEliminata());
//                    }
//                    $agents[] = $company;
//                } else {
//                    foreach ($modelData->getEGDCompany() as $company) {
//                        $found = 0;
//                        if ($modelData->getOperatia() == 3 && $modelData->getEGD3ValorificareDeseuri()[$key]->getAgentEconomicValorificare()) {
//                            foreach ($modelData->getEGD3ValorificareDeseuri()[$key]->getAgentEconomicValorificare() as $agent) {
//                                if ($agent->getName() == $company->getName()) {
//                                    $agents[] = $agent;
//                                    $found = 1;
//                                    break;
//                                }
//                            }
//                        }
//                        if ($modelData->getOperatia() == 4 && $modelData->getEGD4EliminareDeseuri()[$key]->getAgentEconomicEliminare()) {
//                            foreach ($modelData->getEGD4EliminareDeseuri()[$key]->getAgentEconomicEliminare() as $agent) {
//                                if ($agent->getName() == $company->getName()) {
//                                    $agents[] = $agent;
//                                    $found = 1;
//                                    break;
//                                }
//                            }
//                        }
//
//                        if (!$found) {
//                            $company->setCantitateDeseu(0);
//                            $agents[] = $company;
//                        }
//                    }
//                }
//
//                $modelData->getEGD1GenerareDeseuri()[$key]->setAgentEconomic($agents);
//            }
//        }
//
//        if ($flow->getCurrentStep() == ($creditsUsage->getFormularConfig()->getIsFormConfigFinished() ? 3 : 2)) {
//
//            $companies = [];
//            foreach ($modelData->getEGDCompany() as $company) {
//                $companies[] = $company;
//            }
//            $modelData->setEGDCompany($companies);
//
////            foreach ($formData->getEGD2StocareTratareTransportDeseuri() as $key => $item) {
////                $item->setTratareScop(str_replace(array(3, 4), array('V', 'E'), $formData->getOperatia()));
////                $formData->getEGD2StocareTratareTransportDeseuri()[$key] = $item;
////            }
//
//            foreach ($modelData->getEGD1GenerareDeseuri() as $key => $item) {
//                $valueLastYearInStock = ($key == 0) ? $modelData->getlastYearInStock() : 0;
//                if ($modelData->getOperatia() == 3) {
//                    $modelData->getEGD1GenerareDeseuri()[$key]->setCantitateDeseuInStoc($item->getCantitateDeseuGenerate() - $item->getCantitateDeseuValorificata() + $valueLastYearInStock + (($key > 0) ? $modelData->getEGD1GenerareDeseuri()[$key - 1]->getCantitateDeseuInStoc() : 0));
//                }
//                if ($modelData->getOperatia() == 4) {
//                    $modelData->getEGD1GenerareDeseuri()[$key]->setCantitateDeseuInStoc($item->getCantitateDeseuGenerate() - $item->getCantitateDeseuEliminata() + $valueLastYearInStock + (($key > 0) ? $modelData->getEGD1GenerareDeseuri()[$key - 1]->getCantitateDeseuInStoc() : 0));
//                }
//            }
//
//            foreach ($modelData->getEGD2StocareTratareTransportDeseuri() as $key => $item) {
//                $item->setStocareCantitate(round($modelData->getEGD1GenerareDeseuri()[$key]->getCantitateDeseuInStoc(), 2));
//                $item->setStocareTip(NULL);
//                if ($modelData->getEGD1GenerareDeseuri()[$key]->getCantitateDeseuInStoc() > 0) {
//                    $item->setStocareTip($modelData->getStocareTip());
//                }
//                $item->setTransportMijloc(NULL);
//                $item->setTransportDestinatie(NULL);
//                if ($modelData->getEGD1GenerareDeseuri()[$key]->getCantitateDeseuValorificata() > 0 ||
//                  $modelData->getEGD1GenerareDeseuri()[$key]->getCantitateDeseuEliminata() > 0) {
//                    $item->setTransportMijloc($modelData->getTransportMijloc());
//                    $item->setTransportDestinatie($modelData->getTransportDestinatie());
//                }
//                $modelData->getEGD2StocareTratareTransportDeseuri()[$key] = $item;
//            }
//
//            if ($modelData->getOperatia() == 3) {
//                foreach ($modelData->getEGD1GenerareDeseuri() as $key => $item) {
//                    $modelData->getEGD3ValorificareDeseuri()[$key]->setCantitateDeseuValorificata(0);
//                    $modelData->getEGD3ValorificareDeseuri()[$key]->setOperatiaDeValorificare(NULL);
//                    $modelData->getEGD3ValorificareDeseuri()[$key]->setAgentEconomicValorificare(NULL);
//                    if ($item->getCantitateDeseuValorificata() > 0) {
//                        $modelData->getEGD3ValorificareDeseuri()[$key]->setCantitateDeseuValorificata($item->getCantitateDeseuValorificata());
//                        $modelData->getEGD3ValorificareDeseuri()[$key]->setOperatiaDeValorificare($modelData->getOperatiaDeValorificare());
//                        $modelData->getEGD3ValorificareDeseuri()[$key]->setAgentEconomicValorificare($item->getAgentEconomic());
//                    }
//                }
//            }
//
//            if ($modelData->getOperatia() == 4) {
//                foreach ($modelData->getEGD1GenerareDeseuri() as $key => $item) {
//                    $modelData->getEGD4EliminareDeseuri()[$key]->setCantitateDeseuEliminata(0);
//                    $modelData->getEGD4EliminareDeseuri()[$key]->setOperatiaDeEliminare(NULL);
//                    $modelData->getEGD4EliminareDeseuri()[$key]->setAgentEconomicEliminare(NULL);
//                    if ($item->getCantitateDeseuEliminata() > 0) {
//                        $modelData->getEGD4EliminareDeseuri()[$key]->setCantitateDeseuEliminata($item->getCantitateDeseuEliminata());
//                        $modelData->getEGD4EliminareDeseuri()[$key]->setOperatiaDeEliminare($modelData->getOperatiaDeEliminare());
//                        $modelData->getEGD4EliminareDeseuri()[$key]->setAgentEconomicEliminare($item->getAgentEconomic());
//                    }
//                }
//            }
//        }
//    }
//
    /**
     * @param FormularConfig $formularConfig
     * @param $modelData
     * @param $currentStep
     * @param $nextStep
     * @return
     */
    public function processHandleForm(EgdFormularConfig $formularConfig, EvidentaGestiuniiDeseurilor $modelData, $currentStep, $nextStep)
    {
        $modelData->setCurrentStep($currentStep);

        if ($currentStep == 1) {
            $formConfig = $this->getValuesForFormConfig($formularConfig->getFormConfig());
            $formConfig['tip_deseu'] = $formConfig['tip_deseu_cod'];
            unset($formConfig['tip_deseu_cod']);
            $formConfig['operatia'] = $modelData->getOperatia();
            $formularConfig->setFormConfig(json_encode($formConfig));
        }

        if ($currentStep == 2) {
            $companies = [];
            foreach ($modelData->getEGDCompany() as $company) {
                $companies[] = $company;
            }
            $modelData->setEGDCompany($companies);

            foreach ($modelData->getEGD1GenerareDeseuri() as $key => $item) {

                $agents = [];
                if (count($modelData->getEGDCompany()) == 1) {
                    $company = $modelData->getEGDCompany();
                    $company = reset($company);
                    if ($modelData->getOperatia() == 3) {
                        $company->setCantitateDeseu($modelData->getEGD3ValorificareDeseuri()[$key]->getCantitateDeseuValorificata());
                    }
                    if ($modelData->getOperatia() == 4) {
                        $company->setCantitateDeseu($modelData->getEGD4EliminareDeseuri()[$key]->getCantitateDeseuEliminata());
                    }
                    $agents[] = $company;
                } else {
                    foreach ($modelData->getEGDCompany() as $company) {
                        $found = 0;
                        if ($modelData->getOperatia() == 3 && $modelData->getEGD3ValorificareDeseuri()[$key]->getAgentEconomicValorificare()) {
                            foreach ($modelData->getEGD3ValorificareDeseuri()[$key]->getAgentEconomicValorificare() as $agent) {
                                if ($agent->getName() == $company->getName()) {
                                    $agents[] = $agent;
                                    $found = 1;
                                    break;
                                }
                            }
                        }
                        if ($modelData->getOperatia() == 4 && $modelData->getEGD4EliminareDeseuri()[$key]->getAgentEconomicEliminare()) {
                            foreach ($modelData->getEGD4EliminareDeseuri()[$key]->getAgentEconomicEliminare() as $agent) {
                                if ($agent->getName() == $company->getName()) {
                                    $agents[] = $agent;
                                    $found = 1;
                                    break;
                                }
                            }
                        }

                        if (!$found) {
                            $company->setCantitateDeseu(0);
                            $agents[] = $company;
                        }
                    }
                }

                $modelData->getEGD1GenerareDeseuri()[$key]->setAgentEconomic($agents);
            }
        }

        if ($currentStep == 3) {

            $companies = [];
            foreach ($modelData->getEGDCompany() as $company) {
                $companies[] = $company;
            }
            $modelData->setEGDCompany($companies);

//            foreach ($formData->getEGD2StocareTratareTransportDeseuri() as $key => $item) {
//                $item->setTratareScop(str_replace(array(3, 4), array('V', 'E'), $formData->getOperatia()));
//                $formData->getEGD2StocareTratareTransportDeseuri()[$key] = $item;
//            }

            foreach ($modelData->getEGD1GenerareDeseuri() as $key => $item) {
                $valueLastYearInStock = ($key == 0) ? $modelData->getlastYearInStock() : 0;
                if ($modelData->getOperatia() == 3) {
                    $modelData->getEGD1GenerareDeseuri()[$key]->setCantitateDeseuInStoc($item->getCantitateDeseuGenerate() - $item->getCantitateDeseuValorificata() + $valueLastYearInStock + (($key > 0) ? $modelData->getEGD1GenerareDeseuri()[$key - 1]->getCantitateDeseuInStoc() : 0));
                }
                if ($modelData->getOperatia() == 4) {
                    $modelData->getEGD1GenerareDeseuri()[$key]->setCantitateDeseuInStoc($item->getCantitateDeseuGenerate() - $item->getCantitateDeseuEliminata() + $valueLastYearInStock + (($key > 0) ? $modelData->getEGD1GenerareDeseuri()[$key - 1]->getCantitateDeseuInStoc() : 0));
                }
            }

            foreach ($modelData->getEGD2StocareTratareTransportDeseuri() as $key => $item) {
                $item->setStocareCantitate(round($modelData->getEGD1GenerareDeseuri()[$key]->getCantitateDeseuInStoc(), 2));
                $item->setStocareTip(NULL);
                if ($modelData->getEGD1GenerareDeseuri()[$key]->getCantitateDeseuInStoc() > 0) {
                    $item->setStocareTip($modelData->getStocareTip());
                }
                $item->setTransportMijloc(NULL);
                $item->setTransportDestinatie(NULL);
                if ($modelData->getEGD1GenerareDeseuri()[$key]->getCantitateDeseuValorificata() > 0 ||
                  $modelData->getEGD1GenerareDeseuri()[$key]->getCantitateDeseuEliminata() > 0) {
                    $item->setTransportMijloc($modelData->getTransportMijloc());
                    $item->setTransportDestinatie($modelData->getTransportDestinatie());
                }
                $modelData->getEGD2StocareTratareTransportDeseuri()[$key] = $item;
            }

            if ($modelData->getOperatia() == 3) {
                foreach ($modelData->getEGD1GenerareDeseuri() as $key => $item) {
                    $modelData->getEGD3ValorificareDeseuri()[$key]->setCantitateDeseuValorificata(0);
                    $modelData->getEGD3ValorificareDeseuri()[$key]->setOperatiaDeValorificare(NULL);
                    $modelData->getEGD3ValorificareDeseuri()[$key]->setAgentEconomicValorificare(NULL);
                    if ($item->getCantitateDeseuValorificata() > 0) {
                        $modelData->getEGD3ValorificareDeseuri()[$key]->setCantitateDeseuValorificata($item->getCantitateDeseuValorificata());
                        $modelData->getEGD3ValorificareDeseuri()[$key]->setOperatiaDeValorificare($modelData->getOperatiaDeValorificare());
                        $modelData->getEGD3ValorificareDeseuri()[$key]->setAgentEconomicValorificare($item->getAgentEconomic());
                    }
                }
            }

            if ($modelData->getOperatia() == 4) {
                foreach ($modelData->getEGD1GenerareDeseuri() as $key => $item) {
                    $modelData->getEGD4EliminareDeseuri()[$key]->setCantitateDeseuEliminata(0);
                    $modelData->getEGD4EliminareDeseuri()[$key]->setOperatiaDeEliminare(NULL);
                    $modelData->getEGD4EliminareDeseuri()[$key]->setAgentEconomicEliminare(NULL);
                    if ($item->getCantitateDeseuEliminata() > 0) {
                        $modelData->getEGD4EliminareDeseuri()[$key]->setCantitateDeseuEliminata($item->getCantitateDeseuEliminata());
                        $modelData->getEGD4EliminareDeseuri()[$key]->setOperatiaDeEliminare($modelData->getOperatiaDeEliminare());
                        $modelData->getEGD4EliminareDeseuri()[$key]->setAgentEconomicEliminare($item->getAgentEconomic());
                    }
                }
            }
        }

        if (!$nextStep) {
            if ($modelData->getOperatia() == 3) {
                foreach ($modelData->getEGD1GenerareDeseuri() as $key => $item) {
                    $item->setCantitateDeseuEliminata(0);
                    $modelData->getEGD1GenerareDeseuri()[$key] = $item;
                }
                $modelData->setOperatiaDeEliminare(null);
            }
            if ($modelData->getOperatia() == 4) {
                foreach ($modelData->getEGD1GenerareDeseuri() as $key => $item) {
                    $item->setCantitateDeseuValorificata(0);
                    $modelData->getEGD1GenerareDeseuri()[$key] = $item;
                }
                $modelData->setOperatiaDeValorificare(null);
            }
        }

        return $modelData;

    }



    function getEntity()
    {
        return 'AppBundle\Document\EvidentaGestiuniiDeseurilor\EvidentaGestiuniiDeseurilor';
    }
}