<?php

namespace AppBundle\Helper;

class OrderHelper
{

    public static function addInfoToUnlockedDocuments($unlockedDocuments)
    {
        foreach ($unlockedDocuments as $key => $document) {
            $unlockedDocuments[$key]['subject'] = 'order.' . $document['usageType'];
            $unlockedDocuments[$key]['orderId'] = '';
            $unlockedDocuments[$key]['sign'] = '-';
        }

        return $unlockedDocuments;
    }

    public static function addInfoToHistoryOrders($allHistoryOrders)
    {
        foreach ($allHistoryOrders as $key => $order) {
            if ($order['name'] !== null) {
                $allHistoryOrders[$key]['subject'] = 'order.subscription';
                $allHistoryOrders[$key]['orderId'] = $order['id'];
            } else {
                $allHistoryOrders[$key]['subject'] = 'order.credits';
                $allHistoryOrders[$key]['orderId'] = '';
                $allHistoryOrders[$key]['name'] = 'order.credits-bonus';
            }
            $allHistoryOrders[$key]['sign'] = '+';
        }

        return $allHistoryOrders;
    }

    public static function addInfoToExpiredCredits($allExpiredCredits)
    {
        foreach ($allExpiredCredits as $key => $credit) {
            $allExpiredCredits[$key]['subject'] = 'order.credits';
            $allExpiredCredits[$key]['name'] = 'order.credits-expired';
            $allExpiredCredits[$key]['sign'] = '-';
        }

        return $allExpiredCredits;
    }

    public static function prepareCreditHistory($allHistoryOrders, $unlockedDocuments, $allExpiredCredits)
    {
        $creditHistoryItems = array_merge($allHistoryOrders, $unlockedDocuments, $allExpiredCredits);
        $expireDates = [];
        foreach ($creditHistoryItems as $key => $value) {
            $expireDates[$key] = $value['createdAt'];
        }
        array_multisort($expireDates, SORT_DESC, $creditHistoryItems);

        return $creditHistoryItems;
    }

}