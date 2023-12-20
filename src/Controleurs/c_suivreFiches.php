<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

/**
 * @var PdoGsb $pdo
 */
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);


if($action == 'fichePaiement'){
    $listeInfoFiche = $pdo->getResumeFiche();
    $ficheSelectionner = array();
    foreach ($listeInfoFiche as $infoFiche) {
        $toVerif = $infoFiche['mois'].'-'.$infoFiche['id'];
        if(isset($_POST[$toVerif]) && $_POST[$toVerif] == 'on'){
            $ficheSelectionner[] = $infoFiche;
        }
    }
    
    foreach ($ficheSelectionner as $uneFiche){
        $pdo->majEtatFicheFrais($uneFiche['id'], $uneFiche['mois'], 'MP');
    }
}

$listeInfoFiche = $pdo->getResumeFiche();

if(isset($_POST['action'])){
    $listeInfoFiche = $pdo->getResumeFiche($_POST['action']);
}

include PATH_VIEWS . 'suivreFiches/v_suivreFiches.php';