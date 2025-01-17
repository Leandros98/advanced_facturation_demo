<?php

define('TAUX_TVA', [18,10,0]);
function isInternetConnection(){
    try{
        if(fsockopen('www.google.fr',80)){
            return true;
        }
    }catch(\Exception $e){
        return false;
    }
}
function prixVenteHorsTva($price, $taux = 0.18){
    $res = $price / (1 + $taux );
    return ARRONDIR_RESULTAT ? round($res) : number_format($res, 2 );
}
function prixVenteTvac($price, $taux = 0.18){
    $res = $price * (1 + $taux );
    return ARRONDIR_RESULTAT ? round($res) : number_format($res, 2 );
}

function getPrice($price)
{
    $price = floatval($price);
    return number_format($price, 2, ',', ' . ');
}
const MOUVEMENT_STOCK = [
    'EN' => 'Entrée Normales',
    'ER' => 'Entrée Retour',
    'EI' => 'Entrée Inventaire',
    'EAJ' => 'Entrées Ajustement',
    'ET' => 'Entrées Transfert',
    'EAU' => 'Entrées Autres',
    'SN' => 'Sorties Normales',
    'SP' => 'Sorties Perte',
    'SV' => 'Sorties Vol',
    'SD' => 'Sorties Désuétude',
    'SC' => 'Sorties Casse',
    'SAJ' => 'Sorties Ajustement',
    'ST' => 'Sorties Transfert',
    'SAU' => 'Sorties Autres',
];

function getMouvement($key){
    return  MOUVEMENT_STOCK[$key];
}

function setActiveRoute($route){
    return request()->routeIs($route) ? 'active' : '';
}

function isValideNumber($number){
    if (is_numeric($number)) {
        return true;
    } else {
        return false;
    }
}
