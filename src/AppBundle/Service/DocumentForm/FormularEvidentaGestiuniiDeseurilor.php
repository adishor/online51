<?php

namespace AppBundle\Service\DocumentForm;

use AppBundle\Service\DocumentForm\Base\FormularGeneric;
use Symfony\Component\Yaml\Yaml;
use AppBundle\Service\DocumentForm\Base\FormularFormConfigTextInterface;
use AppBundle\Service\DocumentForm\Base\FormularFormConfigValueInterface;
use AppBundle\Service\DocumentForm\Base\FormularFormDefaultInterface;
use AppBundle\Service\DocumentForm\Base\FormularFormChangeStructureInterface;
use AppBundle\Service\DocumentForm\Base\FormularFormTemplateInterface;
use AppBundle\Service\DocumentForm\Base\FormularFormProcessInterface;

class FormularEvidentaGestiuniiDeseurilor extends FormularGeneric implements FormularFormConfigValueInterface, FormularFormConfigTextInterface, FormularFormDefaultInterface, FormularFormChangeStructureInterface, FormularFormTemplateInterface, FormularFormProcessInterface
{

    public function getParameters()
    {
        return Yaml::parse($this->kernelRootDir . "/config/documentForm/" . strtolower($this->slug) . '.yml')['parameters'];
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
                        $formConfigValue[$key] = $tipDeseuArray[$deseuCodes[0]]['name'] . "; " .
                          $tipDeseuArray[$deseuCodes[0]]['values'][$deseuCodes[1]]['name'] . "; " .
                          $tipDeseuArray[$deseuCodes[0]]['values'][$deseuCodes[1]]['values'][$deseuCodes[2]];
                        $formConfigValue[$key . "_cod"] = $config;
                        break;
                    case 'operatia':
                        $formConfigValue[$key] = $parameters['operatia'][$config];
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

        return array(
            'message' => 'document-form.text.egd',
            'variables' => array(
                'waste-type' => ($short) ? $formConfigValue['tip_deseu_cod'] : $formConfigValue['tip_deseu'],
                'year' => $formConfigValue['an'],
                'operation' => isset($formConfigD->operatia) ? $formConfigValue['operatia'] : 'document-form.text.egd-operatia-unselect'
            )
        );
    }

    public function applyDefaultFormData($creditsUsage, $formData, $user)
    {
        $formConfigValue = $this->getValuesForFormConfig($creditsUsage->getFormConfig());

        $formData->setAgentEconomic($user->getCompany());
        $formData->setAn($formConfigValue['an']);
        $formData->setTipDeseu($formConfigValue['tip_deseu']);
        $formData->setTipDeseuCod($formConfigValue['tip_deseu_cod']);
        $creditsUsage->setFormData($this->jmsSerializer->serialize($formData, 'json'));
        $this->entityManager->flush();
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
            $EGD1CantitateDeseuGenerateTotal += $EGD1[$key]->getCantitateDeseuGenerate();
            $EGD1CantitateDeseuValorificataTotal += $EGD1[$key]->getCantitateDeseuValorificata();
            $EGD1CantitateDeseuEliminataTotal += $EGD1[$key]->getCantitateDeseuEliminata();
            $EGD1CantitateDeseuInStocTotal += $EGD1[$key]->getCantitateDeseuInStoc();
            $EGD2StocareCantitateTotal += $EGD2[$key]->getStocareCantitate();
            $EGD2TratareCantitateTotal += $EGD2[$key]->getTratareCantitate();
            $EGD3CantitateDeseuValorificataTotal += $EGD3[$key]->getCantitateDeseuValorificata();
            $EGD4CantitateDeseuEliminataTotal += $EGD4[$key]->getCantitateDeseuEliminata();
            $EGD1CantitateDeseuInStocValorificata[$key] = $EGD1[$key]->getCantitateDeseuGenerate() - $EGD1[$key]->getCantitateDeseuValorificata();
            $EGD1CantitateDeseuInStocEliminata[$key] = $EGD1[$key]->getCantitateDeseuGenerate() - $EGD1[$key]->getCantitateDeseuEliminata();
        }
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
        if ($flow->getCurrentStep() == 1 && $creditsUsage->getIsFormConfigFinished()) {
            $formConfig = $this->getValuesForFormConfig($creditsUsage->getFormConfig());
            $formConfig['tip_deseu'] = $formConfig['tip_deseu_cod'];
            unset($formConfig['tip_deseu_cod']);
            $formConfig['operatia'] = $formData->getOperatia();
            $creditsUsage->setFormConfig(json_encode($formConfig));

            foreach ($formData->getEGD2StocareTratareTransportDeseuri() as $key => $item) {
                $item->setTratareScop(str_replace(array(3, 4), array('V', 'E'), $formData->getOperatia()));
                $formData->getEGD2StocareTratareTransportDeseuri()[$key] = $item;
            }

            if ($formData->getOperatia() == 3) {
                foreach ($formData->getEGD1GenerareDeseuri() as $key => $item) {
                    $item->setCantitateDeseuEliminata(0);
                    $formData->getEGD1GenerareDeseuri()[$key] = $item;
                }
            }
            if ($formData->getOperatia() == 4) {
                foreach ($formData->getEGD1GenerareDeseuri() as $key => $item) {
                    $item->setCantitateDeseuValorificata(0);
                    $formData->getEGD1GenerareDeseuri()[$key] = $item;
                }
            }
        }

        if ($flow->getCurrentStep() == ($creditsUsage->getIsFormConfigFinished() ? 2 : 1)) {
            foreach ($formData->getEGD2StocareTratareTransportDeseuri() as $key => $item) {
                $item->setStocareTip($formData->getStocareTip());
                $item->setTratareMod($formData->getTratareMod());
                $item->setTratareScop($formData->getTratareScop());
                $item->setTransportMijloc($formData->getTransportMijloc());
                $item->setTransportDestinatie($formData->getTransportDestinatie());
                $formData->getEGD2StocareTratareTransportDeseuri()[$key] = $item;
            }

            if ($formData->getOperatiaDeValorificare()) {
                foreach ($formData->getEGD3ValorificareDeseuri() as $key => $item) {
                    $item->setOperatiaDeValorificare($formData->getOperatiaDeValorificare());
                    $item->setAgentEconomicValorificare(NULL);
                    foreach ($formData->getEGDCompany() as $company) {
                        if ($key + 1 >= $company->getStartMonth()) {
                            $item->setAgentEconomicValorificare($company->getName());
                        }
                    }
                    $formData->getEGD3ValorificareDeseuri()[$key] = $item;
                }
            }

            if ($formData->getOperatiaDeEliminare()) {
                foreach ($formData->getEGD4EliminareDeseuri() as $key => $item) {
                    $item->setOperatiaDeEliminare($formData->getOperatiaDeEliminare());
                    $item->setAgentEconomicEliminare(NULL);
                    foreach ($formData->getEGDCompany() as $company) {
                        if ($key + 1 >= $company->getStartMonth()) {
                            $item->setAgentEconomicEliminare($company->getName());
                        }
                    }
                    $formData->getEGD4EliminareDeseuri()[$key] = $item;
                }
            }
        }
    }

}