<?php

/**
 * Vue Valider Fiches Frais
 *
 * PHP Version 8
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    Marco Clin <marcoetude@gmail.com>
 * @author    Lucas Bruel <lucasfox@outlook.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */

use Modeles\PdoGsb;

/**
 * @var PdoGsb $pdo
 * @var array $visiteurs
 * @var array $lesMois
 * @var string $selectedVisiteurId
 * @var string $selectedMonth
 */

?>

<div>
    <form action="/" method="GET" class="form-inline">
        <div>
            <div class="form-group">
                <input type="hidden" value="validerFiches" name="uc">
                <label for="visiteurInput">Choisir le visiteur : </label>
                <select class="form-control" id="visiteurInput" autocomplete="on" name="visiteurId">
                    <option value="none" >Selectionner un visiteur.</option>
                    <?php
                    foreach ($visiteurs as $visiteur) {
                        echo "<option value='" . $visiteur["id"] . "'" . ($selectedVisiteurId == $visiteur["id"] ? 'selected' : '') . ">" . $visiteur["prenom"] . " " . $visiteur["nom"] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="monthInput" style="margin-left: 20px">Mois : </label>
                <select class="form-control form-control" id="monthInput" name="month">
                    <?php
                    foreach ($lesMois as $mois) {
                        $concatYearsMonth = $mois["numAnnee"] . $mois["numMois"];
                        echo "<option value='" . $concatYearsMonth . "'" . ($selectedMonth == $concatYearsMonth ? 'selected' : '') . ">" . $mois["numMois"] . "/" . $mois["numAnnee"] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-warning ms-3">Rechercher</button>
        </div>
    </form>
    <h3 class="gras orange">Valider la fiche de frais</h3>
    <h4>Eléments forfaitisés</h4>
    <form action="" method="get">
        <div class="row">
            <div class="form-group col-sm-3">
                <label for="inputForfaitStage">Forfait Etape</label>
                <input type="text" name="forfaitEtape" class="form-control" id="inputForfaitStage">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-3">
                <label for="inputFraisKm">Frais Kilométrique</label>
                <input type="text" name="forfaitEtape" class="form-control" id="inputFraisKm">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-3">
                <label for="inputinputNuitHotel">Nuitée Hôtel</label>
                <input type="text" name="nuitHotel" class="form-control" id="inputinputNuitHotel">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-3">
                <label for="inputRepasResto">Repas Restaurant</label>
                <input type="text" name="repasResto" class="form-control" id="inputRepasResto">
            </div>
        </div>
        <button type="submit" class="btn btn-success">Corriger</button>
        <button type="reset" class="btn btn-danger">Réinitialiser</button>
        <br>
    </form>
    <br><br>
    <div class="panel panel-warning">
        <div class="panel-heading">
            <p class="panel-title">Descriptif des éléments hors forfait</p>
        </div>
        <table border="1" class="table border-warning table-bordered" style="margin-bottom: 0px !important;">
            <thead>
                <tr>
                    <td class="gras border-warning">Date</td>
                    <td class="gras border-warning">Libellé</td>
                    <td class="gras border-warning">Montant</td>
                </tr>
            </thead>
            <tbody>
                <?php include PATH_VIEWS . 'v_tableFichesFrais.php' ?>
                <?php include PATH_VIEWS . 'v_tableFichesFrais.php' ?>
            </tbody>
        </table>
    </div>
</div>

<br><br>
<form class="form-inline">
    <div class="form-group">
        <label for="inputNbJustificatif" class="control-label">Forfait Etape :</label>
        <input type="number" name="nbJustificatif" class="form-control" id="inputNbJustificatif" style="width: 20% !important;">
    </div>
</form>
<br>

<button type="submit" class="btn btn-success">Valider</button>
<button type="reset" class="btn btn-danger">Réinitialiser</button>