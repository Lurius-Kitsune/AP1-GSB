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

// quantité de visiteurs à afficher
$qteAfficher = 30;


$nbPages = $nbFiches/$qteAfficher;
if($nbPages>(int)$nbPages){  // cast en int car arrondissement du nombre de page (14.8 -> 15)
    $nbPages+=1;
    $nbPages=(int)$nbPages;
}
$pageActuel = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT);

// gestion des pages
$listeInfoFiche = array_slice($listeInfoFiche, ($qteAfficher*$pageActuel)-$qteAfficher, $qteAfficher);

// Partie limite d'affichage

$listeInfoFiche = array_slice($listeInfoFiche, 0, 30);





$visiteurs = $pdo->getNomsVisiteurs();
$lesMois = $pdo->getTousLesMoisDisponibles();
include PATH_VIEWS . 'suivreFiches/v_suivreFiches.php';
