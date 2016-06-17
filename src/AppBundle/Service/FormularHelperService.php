<?php

namespace AppBundle\Service;

use Symfony\Component\Translation\TranslatorInterface;

class FormularHelperService
{
    protected $translator;
    protected $tipDeseuEGD;
    protected $operatiaEGD;

    public function __construct(TranslatorInterface $translator, $tipDeseu, $operatia)
    {
        $this->translator = $translator;
        $this->tipDeseuEGD = $tipDeseu;
        $this->operatiaEGD = $operatia;
    }

    public function CalculateEvidentaGestiuniiDeseurilorTotals($formData)
    {
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

        return $EGDTotals;
    }

    public function getFormTextEvidentaGestiuniiDeseurilor($EncodedFormConfig, $short = false)
    {

        $formConfig = json_decode($EncodedFormConfig);
        $deseuCodes = explode(" ", $formConfig->tip_deseu);

        $waste = (!$short) ? $this->tipDeseuEGD[$deseuCodes[0]]['name'] . "; " .
          $this->tipDeseuEGD[$deseuCodes[0]]['values'][$deseuCodes[1]]['name'] . "; " .
          $this->tipDeseuEGD[$deseuCodes[0]]['values'][$deseuCodes[1]]['values'][$deseuCodes[2]] : $formConfig->tip_deseu;

        return $this->translator->trans('document-form.text.egd', array(
              'waste-type' => $waste,
              'year' => $formConfig->an,
              'operation' => $this->operatiaEGD[$formConfig->operatia],
        ));
    }

}