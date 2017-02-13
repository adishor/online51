<?php

namespace AppBundle\Service\DocumentForm;

use AppBundle\Document\EvidentaGestiuniiDeseurilor\EvidentaGestiuniiDeseurilor;
use AppBundle\Entity\CreditsUsage;
use AppBundle\Entity\Formular;
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
    }

    private function getParameters()
    {
        $resourcePath = $this->fileLocator->locate('@AppBundle/Resources/config/deseuri/evidenta_gestiunii_deseurilor.yml');
        $configValues = Yaml::parse(file_get_contents($resourcePath));

        return $configValues;
    }

    public function getUniquenessValues(Formular $formular)
    {
        $parameters = $this->getParameters();
        $uniqueValues['an'] = [];
        $currYear = date('Y');

        if (null !== $formular->getValabilityMonth()) {
            $currMonth = date('n');
            $uniqueValues['an'][$currYear] = $currYear;
            if ($currMonth <= $formular->getValabilityMonth()) {
                $uniqueValues['an'][$currYear - 1] = $currYear - 1;
            }
        } else {
            for ($i = EvidentaGestiuniiDeseurilor::$startYear; $i <= $currYear; $i++) {
                $uniqueValues['an'][$i] = $i;
            }
        }

        $uniqueValues['tip_deseu'] = $parameters['tip_deseu'];

        return $uniqueValues;
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

    public function getTextForFormConfig($formConfig, $short = false)
    {
        $formConfigD = json_decode($formConfig);
        $formConfigValue = $this->getValuesForFormConfig($formConfig);

        // && - special character separator for dropdown display for full configuration
        return array(
            'message' => (!$short) ? 'document-form.text.egd-full' : 'document-form.text.egd',
            'variables' => array(
                'waste-type' => ($short) ? $formConfigValue['tip_deseu_cod'] : $formConfigValue['tip_deseu_cod'] . "&&" . $formConfigValue['tip_deseu'],
                'year' => $formConfigValue['an'],
                'operation' => isset($formConfigD->operatia) ? $formConfigValue['operatia'] : 'neselectat'
            )
        );
    }

    public function applyDefaultFormData(CreditsUsage $creditsUsage, $user)
    {
        $entityNamespace = $this->getEntity();
        $entity = new $entityNamespace();

        $formConfigValue = $this->getValuesForFormConfig($creditsUsage->getFormConfig());

        $entity->setAgentEconomic($user->getProfile() ? $user->getProfile()->getCompany() : "");
        $entity->setAn($formConfigValue['an']);
        $entity->setTipDeseu($formConfigValue['tip_deseu']);
        $entity->setTipDeseuCod($formConfigValue['tip_deseu_cod']);
        $creditsUsage->setFormData($this->jmsSerializer->serialize($entity, 'json'));

        $this->entityManager->persist($creditsUsage);
        $this->entityManager->flush();

        return $entity;
    }

    public function applyFormCustomization($flow, $form, $creditsUsage)
    {
        if ($flow->getCurrentStep() == 1) {
            $formConfig = json_decode($creditsUsage->getFormConfig());
            if (isset($formConfig->operatia)) {
                if ($formConfig->operatia === '3') {
                    $form->remove('operatiaDeEliminare');
                }
                if ($formConfig->operatia === '4') {
                    $form->remove('operatiaDeValorificare');
                }
            }
        }
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

    public function processHandleForm($creditsUsage, $flow, &$formData)
    {
        if ($formData->getCurrentStep() < $flow->getCurrentStep()) {
            $formData->setCurrentStep($flow->getCurrentStep());
        }

        if ($flow->getCurrentStep() == 1 && $creditsUsage->getIsFormConfigFinished()) {
            $formConfig = $this->getValuesForFormConfig($creditsUsage->getFormConfig());
            $formConfig['tip_deseu'] = $formConfig['tip_deseu_cod'];
            unset($formConfig['tip_deseu_cod']);
            $formConfig['operatia'] = $formData->getOperatia();
            $creditsUsage->setFormConfig(json_encode($formConfig));
        }

        if ($flow->getCurrentStep() == ($creditsUsage->getIsFormConfigFinished() ? 2 : 1)) {

            $companies = [];
            foreach ($formData->getEGDCompany() as $company) {
                $companies[] = $company;
            }
            $formData->setEGDCompany($companies);

            foreach ($formData->getEGD1GenerareDeseuri() as $key => $item) {

                $agents = [];
                if (count($formData->getEGDCompany()) == 1) {
                    $company = $formData->getEGDCompany();
                    $company = reset($company);
                    if ($formData->getOperatia() == 3) {
                        $company->setCantitateDeseu($formData->getEGD3ValorificareDeseuri()[$key]->getCantitateDeseuValorificata());
                    }
                    if ($formData->getOperatia() == 4) {
                        $company->setCantitateDeseu($formData->getEGD4EliminareDeseuri()[$key]->getCantitateDeseuEliminata());
                    }
                    $agents[] = $company;
                } else {
                    foreach ($formData->getEGDCompany() as $company) {
                        $found = 0;
                        if ($formData->getOperatia() == 3 && $formData->getEGD3ValorificareDeseuri()[$key]->getAgentEconomicValorificare()) {
                            foreach ($formData->getEGD3ValorificareDeseuri()[$key]->getAgentEconomicValorificare() as $agent) {
                                if ($agent->getName() == $company->getName()) {
                                    $agents[] = $agent;
                                    $found = 1;
                                    break;
                                }
                            }
                        }
                        if ($formData->getOperatia() == 4 && $formData->getEGD4EliminareDeseuri()[$key]->getAgentEconomicEliminare()) {
                            foreach ($formData->getEGD4EliminareDeseuri()[$key]->getAgentEconomicEliminare() as $agent) {
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

                $formData->getEGD1GenerareDeseuri()[$key]->setAgentEconomic($agents);
            }
        }

        if ($flow->getCurrentStep() == ($creditsUsage->getIsFormConfigFinished() ? 3 : 2)) {

            $companies = [];
            foreach ($formData->getEGDCompany() as $company) {
                $companies[] = $company;
            }
            $formData->setEGDCompany($companies);

//            foreach ($formData->getEGD2StocareTratareTransportDeseuri() as $key => $item) {
//                $item->setTratareScop(str_replace(array(3, 4), array('V', 'E'), $formData->getOperatia()));
//                $formData->getEGD2StocareTratareTransportDeseuri()[$key] = $item;
//            }

            foreach ($formData->getEGD1GenerareDeseuri() as $key => $item) {
                $valueLastYearInStock = ($key == 0) ? $formData->getlastYearInStock() : 0;
                if ($formData->getOperatia() == 3) {
                    $formData->getEGD1GenerareDeseuri()[$key]->setCantitateDeseuInStoc($item->getCantitateDeseuGenerate() - $item->getCantitateDeseuValorificata() + $valueLastYearInStock + (($key > 0) ? $formData->getEGD1GenerareDeseuri()[$key - 1]->getCantitateDeseuInStoc() : 0));
                }
                if ($formData->getOperatia() == 4) {
                    $formData->getEGD1GenerareDeseuri()[$key]->setCantitateDeseuInStoc($item->getCantitateDeseuGenerate() - $item->getCantitateDeseuEliminata() + $valueLastYearInStock + (($key > 0) ? $formData->getEGD1GenerareDeseuri()[$key - 1]->getCantitateDeseuInStoc() : 0));
                }
            }

            foreach ($formData->getEGD2StocareTratareTransportDeseuri() as $key => $item) {
                $item->setStocareCantitate(round($formData->getEGD1GenerareDeseuri()[$key]->getCantitateDeseuInStoc(), 2));
                $item->setStocareTip(NULL);
                if ($formData->getEGD1GenerareDeseuri()[$key]->getCantitateDeseuInStoc() > 0) {
                    $item->setStocareTip($formData->getStocareTip());
                }
                $item->setTransportMijloc(NULL);
                $item->setTransportDestinatie(NULL);
                if ($formData->getEGD1GenerareDeseuri()[$key]->getCantitateDeseuValorificata() > 0 ||
                  $formData->getEGD1GenerareDeseuri()[$key]->getCantitateDeseuEliminata() > 0) {
                    $item->setTransportMijloc($formData->getTransportMijloc());
                    $item->setTransportDestinatie($formData->getTransportDestinatie());
                }
                $formData->getEGD2StocareTratareTransportDeseuri()[$key] = $item;
            }

            if ($formData->getOperatia() == 3) {
                foreach ($formData->getEGD1GenerareDeseuri() as $key => $item) {
                    $formData->getEGD3ValorificareDeseuri()[$key]->setCantitateDeseuValorificata(0);
                    $formData->getEGD3ValorificareDeseuri()[$key]->setOperatiaDeValorificare(NULL);
                    $formData->getEGD3ValorificareDeseuri()[$key]->setAgentEconomicValorificare(NULL);
                    if ($item->getCantitateDeseuValorificata() > 0) {
                        $formData->getEGD3ValorificareDeseuri()[$key]->setCantitateDeseuValorificata($item->getCantitateDeseuValorificata());
                        $formData->getEGD3ValorificareDeseuri()[$key]->setOperatiaDeValorificare($formData->getOperatiaDeValorificare());
                        $formData->getEGD3ValorificareDeseuri()[$key]->setAgentEconomicValorificare($item->getAgentEconomic());
                    }
                }
            }

            if ($formData->getOperatia() == 4) {
                foreach ($formData->getEGD1GenerareDeseuri() as $key => $item) {
                    $formData->getEGD4EliminareDeseuri()[$key]->setCantitateDeseuEliminata(0);
                    $formData->getEGD4EliminareDeseuri()[$key]->setOperatiaDeEliminare(NULL);
                    $formData->getEGD4EliminareDeseuri()[$key]->setAgentEconomicEliminare(NULL);
                    if ($item->getCantitateDeseuEliminata() > 0) {
                        $formData->getEGD4EliminareDeseuri()[$key]->setCantitateDeseuEliminata($item->getCantitateDeseuEliminata());
                        $formData->getEGD4EliminareDeseuri()[$key]->setOperatiaDeEliminare($formData->getOperatiaDeEliminare());
                        $formData->getEGD4EliminareDeseuri()[$key]->setAgentEconomicEliminare($item->getAgentEconomic());
                    }
                }
            }
        }
    }

    public function processEndHandleForm(&$formData)
    {
        if ($formData->getOperatia() == 3) {
            foreach ($formData->getEGD1GenerareDeseuri() as $key => $item) {
                $item->setCantitateDeseuEliminata(0);
                $formData->getEGD1GenerareDeseuri()[$key] = $item;
            }
            $formData->setOperatiaDeEliminare(null);
        }
        if ($formData->getOperatia() == 4) {
            foreach ($formData->getEGD1GenerareDeseuri() as $key => $item) {
                $item->setCantitateDeseuValorificata(0);
                $formData->getEGD1GenerareDeseuri()[$key] = $item;
            }
            $formData->setOperatiaDeValorificare(null);
        }
    }


    function getEntity()
    {
        return 'AppBundle\Document\EvidentaGestiuniiDeseurilor\EvidentaGestiuniiDeseurilor';
    }
}