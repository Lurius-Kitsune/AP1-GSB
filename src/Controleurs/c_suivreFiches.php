<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

/**
 * @var PdoGsb $pdo
 */
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

// Partie mise en paiement
if ($action == 'fichePaiement') {
    $listeInfoFiche = $pdo->getResumeFiche();
    $ficheSelectionner = array();
    foreach ($listeInfoFiche as $infoFiche) {
        $toVerif = $infoFiche['mois'] . '-' . $infoFiche['id'];
        if (isset($_POST[$toVerif]) && $_POST[$toVerif] == 'on') {
            $ficheSelectionner[] = $infoFiche;
        }
    }

    foreach ($ficheSelectionner as $uneFiche) {
        $pdo->majEtatFicheFrais($uneFiche['id'], $uneFiche['mois'], 'MP');
    }
}

$listeInfoFiche = $pdo->getResumeFiche();

if (isset($_GET['visiteurId']) && $_GET['visiteurId'] != 'none') {
    $selectedVisiteurId = filter_input(INPUT_GET, 'visiteurId', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    foreach ($listeInfoFiche as $key => $uneFiche) {
        if ($uneFiche['id'] !== $selectedVisiteurId) {
            unset($listeInfoFiche[$key]);
        }
    }
}

if (isset($_GET['month']) && $_GET['month'] != 'none') {
    $selectedMonth= filter_input(INPUT_GET, 'month', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    foreach ($listeInfoFiche as $key => $uneFiche) {
        if ($uneFiche['mois'] !== $selectedMonth) {
            unset($listeInfoFiche[$key]);
        }
    }
}

$nbFiches = count($listeInfoFiche);

if(isset($_GET['qte'])){
    $qteAfficher = filter_input(INPUT_GET, 'qte', FILTER_SANITIZE_NUMBER_INT);
}else{
    $qteAfficher = 10;
}
$nbPages = $nbFiches/$qteAfficher;
if($nbPages>(int)$nbPages){
    $nbPages+=1;
    $nbPages=(int)$nbPages;
}
$pageActuel = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT);

$listeInfoFiche = array_slice($listeInfoFiche, ($qteAfficher*$pageActuel)-$qteAfficher, $qteAfficher);

// Partie limite d'affichage
if (isset($_GET['qte'])) {
    $qteAfficher = $_GET['qte'];
    $_SESSION['lignesAffichees'] = $_GET['qte'];
    $_SESSION['derniereLigne'] = $_GET['qte'];
} else {
    $listeInfoFiche = array_slice($listeInfoFiche, 0, 10);
    $_SESSION['derniereLigne'] = 10;
    $_SESSION['lignesAffichees'] = 10;
}

// Partie page suivante/précédente
if (isset($_POST['suiv'])) {
    $listeInfoFiche = array_slice($listeInfoFiche, $_SESSION['derniereLigne'], $_SESSION['derniereLigne']+10);
    $_SESSION['derniereLigne']+=$_SESSION['lignesAffichees'];
}

if (isset($_POST['prec'])) {
    // à compléter
}


$visiteurs = $pdo->getNomsVisiteurs();
$lesMois = $pdo->getTousLesMoisDisponibles();
include PATH_VIEWS . 'suivreFiches/v_suivreFiches.php';
