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
use App\Entity\LigneHorsForfait;

if (!empty($_POST)) {
    $case = filter_input(INPUT_POST, 'case', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    switch ($case) {
        case "ligneHorsForfait":
            actionLigneHorsForfait($pdo);
            break;
        case "formForfait":
            actionForfait($pdo);
            break;
        case "ficheFrais":
            validerFiche($pdo);
            break;
        default :
            break;
    }
}

$selectedVisiteurId = null;

$lesMois = $pdo->getMoisFichesFraisCloturer();

$selectedMonth = filter_input(INPUT_GET, 'month', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? $lesMois[0]["numAnnee"] . $lesMois[0]["numMois"];

if (isset($_GET['visiteurId']) && $_GET['visiteurId'] != 'none') {
    $selectedVisiteurId = filter_input(INPUT_GET, 'visiteurId', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $infoFicheFrais = $pdo->getLesInfosFicheFrais($selectedVisiteurId, $selectedMonth);
    $infoFraisForfait = $infoFicheFrais['idEtat'] === 'CL' ? $pdo->getLesFraisForfait($selectedVisiteurId, $selectedMonth) : null;
    $listeFraisHorsForfait = $infoFicheFrais['idEtat'] === 'CL' ? $pdo->getLesFraisHorsForfait($selectedVisiteurId, $selectedMonth) : null;
    $nbJustificatif = $infoFicheFrais['nbJustificatifs'];
}

$visiteurs = $pdo->getVisiteurHavingFicheMonth($selectedMonth);

require PATH_VIEWS . 'validerFiches/v_validerFiches.php';

/**
 * Interaction avec les lignes Hors Forfait
 */
function actionLigneHorsForfait($pdo) {
    $buttonInput = filter_input(INPUT_POST, 'buttonInput', FILTER_SANITIZE_SPECIAL_CHARS);
    if ($buttonInput == 'refuser') {
        $selectedVisiteurId = filter_input(INPUT_GET, 'visiteurId', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $idLigneHorsForfait = filter_input(INPUT_POST, 'idLigneHorsForfait', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $pdo->denyUnFraisHorsForfait($idLigneHorsForfait, $selectedVisiteurId);
    } else if ($buttonInput == 'corriger') {
        $params = array(
            "id" => filter_input(INPUT_POST, 'idLigneHorsForfait', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            "date" => filter_input(INPUT_POST, 'dateLigneHorsForfait', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            "libelle" => filter_input(INPUT_POST, 'libelleLigneHorsForfait', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            "montant" => filter_input(INPUT_POST, 'montantLigneHorsForfait', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
        );
        $ligneHf=array();
        foreach($params as $paramCle => $paramVal){
            $ligneHf[$paramCle]=$paramVal;
        }
        $pdo->majFraisHorsForfait(filter_input(INPUT_GET, 'visiteurId', FILTER_SANITIZE_SPECIAL_CHARS), filter_input(INPUT_GET, 'month', FILTER_SANITIZE_SPECIAL_CHARS), $ligneHf);
    } else if ($buttonInput == 'reporter'){
        $selectedVisiteurId = filter_input(INPUT_GET, 'visiteurId', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $idLigneHorsForfait = filter_input(INPUT_POST, 'idLigneHorsForfait', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $pdo->reportLigneHf($selectedVisiteurId, $idLigneHorsForfait);
        echo "<br><div class=\"alert alert-warning\" role=\"alert\">La ligne a bien été reporté.</div>";
    }
}

/**
 * Interaction avec la partie frais forfait
 */
function actionForfait($pdo) {
    if (isset($_POST['forfaitEtape']) && isset($_POST['fraisKm']) && isset($_POST['nuitHotel']) && isset($_POST['repasResto'])) {
        $val = array(
            "ETP" => filter_input(INPUT_POST, 'forfaitEtape', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            "KM" => filter_input(INPUT_POST, 'fraisKm', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            "NUI" => filter_input(INPUT_POST, 'nuitHotel', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            "REP" => filter_input(INPUT_POST, 'repasResto', FILTER_SANITIZE_FULL_SPECIAL_CHARS)
        );
        $pdo->majFraisForfait(filter_input(INPUT_GET, 'visiteurId', FILTER_SANITIZE_SPECIAL_CHARS), filter_input(INPUT_GET, 'month', FILTER_SANITIZE_SPECIAL_CHARS), $val);
        echo "<br><div class=\"alert alert-success\" role=\"alert\">Les données ont bien été mises à jour.</div>";
    }
}

function validerFiche($pdo) {
    $visiteurId = filter_input(INPUT_POST, 'idVisiteur', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $month = filter_input(INPUT_POST, 'month', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $nbJustificatif = filter_input(INPUT_POST, 'nbJustificatif', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    $pdo->majNbJustificatifs($visiteurId, $month, $nbJustificatif);
    $pdo->majMontantValiderFicheFrais($visiteurId, $month);
    $pdo->majEtatFicheFrais($visiteurId, $month, 'VA');
    echo "<br><div class=\"alert alert-success\" role=\"alert\">La fiche à bien été validé.</div>";
}
