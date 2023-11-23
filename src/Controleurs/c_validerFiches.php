<?php

/**
 * Gestion des frais
 *
 * PHP Version 8
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    Lucas Bruel <lucasfox@outlook.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */

/**
 * @var PdoGsb $pdo
 */
use Outils\Utilitaires;

$lesMois = $pdo->getMoisFichesFraisCloturer();


$selectedVisiteurId = null;
$selectedMonth = filter_input(INPUT_GET, 'month', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? $lesMois[0]["numAnnee"] . $lesMois[0]["numMois"];


if(isset($_POST['forfaitEtape']) && isset($_POST['fraisKm'])  && isset($_POST['nuitHotel']) && isset($_POST['repasResto'])){
    $val = array(
        "ETP" => filter_input(INPUT_POST, 'forfaitEtape', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
        "KM" => filter_input(INPUT_POST, 'fraisKm', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
        "NUI" => filter_input(INPUT_POST, 'nuitHotel', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
        "REP" => filter_input(INPUT_POST, 'repasResto', FILTER_SANITIZE_FULL_SPECIAL_CHARS)
    );
    $pdo->majFraisForfait(filter_input(INPUT_GET, 'visiteurId'), filter_input(INPUT_GET, 'month'), $val);
}

if (isset($_GET['visiteurId']) && $_GET['visiteurId'] != 'none') {
    $selectedVisiteurId = filter_input(INPUT_GET, 'visiteurId', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $infoFraisForfait = $pdo->getLesFraisForfait($selectedVisiteurId, $selectedMonth);
    $listeFraisHorsForfait = $pdo->getLesFraisHorsForfait($selectedVisiteurId, $selectedMonth);
}

$visiteurs = $pdo->getVisiteurHavingFicheMonth($selectedMonth);

require PATH_VIEWS . 'validerFiches/v_validerFiches.php';
