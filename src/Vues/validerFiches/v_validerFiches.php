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
 * @var array $infoFraisForfait
 * @var array $listeFraisHorsForfait
*/

?>

<script>
    function reloadPage() {
        // Récupérer la valeur sélectionnée dans la selectbox
        var selectedValue = document.getElementById("monthInput").value;

        // Recharger la page avec le paramètre GET
        window.location.href = window.location.pathname + "?uc=validerFiches&month=" + selectedValue;
    }
</script>
<div>
    <form action="/" method="GET" class="form-inline">
        <input type="hidden" value="validerFiches" name="uc">
        <div>
            <div class="form-group">
                <label for="monthInput" style="margin-left: 20px">Mois : </label>
                <select class="form-control form-control" id="monthInput" name="month" onchange="reloadPage()">
                    <?php
                    foreach ($lesMois as $mois) {
                        $concatYearsMonth = $mois["numAnnee"] . $mois["numMois"];
                        echo "<option value='" . $concatYearsMonth . "'" . ($selectedMonth == $concatYearsMonth ? 'selected' : '') . ">" . $mois["numMois"] . "/" . $mois["numAnnee"] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <?php if (isset($visiteurs) && !empty($visiteurs)) : ?>
            <div class="form-group">
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
            <?php endif; ?>
            <button type="submit" class="btn btn-warning ms-3">Rechercher</button>
        </div>
    </form>
    <?php 
    if (is_null($selectedVisiteurId)) {
        echo '<h3></h3>'; 
     } elseif(empty($infoFraisForfait)) {
        echo '<h3>Aucune fiche de frais trouver</h3>';
    } else {
    ?>
    <h3 class="gras orange">Valider la fiche de frais</h3>
    <h4>Eléments forfaitisés</h4>
    <form action="" method="get">
        <div class="row">
            <div class="form-group col-sm-3">
                <label for="inputForfaitStage">Forfait Etape</label>
                <input type="text" name="forfaitEtape" class="form-control" id="inputForfaitStage" 
                       value="<?= $infoFraisForfait[0]['quantite'] ?? '0' ?>" 
                       placeholder="<?= $infoFraisForfait[0]['quantite'] ?? '0' ?>">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-3">
                <label for="inputFraisKm">Frais Kilométrique</label>
                <input type="text" name="forfaitEtape" class="form-control" id="inputFraisKm" 
                       value="<?= $infoFraisForfait[1]['quantite'] ?? '0' ?>"
                       placeholder="<?= $infoFraisForfait[1]['quantite'] ?? '0' ?>">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-3">
                <label for="inputinputNuitHotel">Nuitée Hôtel</label>
                <input type="text" name="nuitHotel" class="form-control" id="inputinputNuitHotel" 
                       value="<?= $infoFraisForfait[2]['quantite'] ?? '0' ?>"
                       placeholder="<?= $infoFraisForfait[2]['quantite'] ?? '0' ?>">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-3">
                <label for="inputRepasResto">Repas Restaurant</label>
                <input type="text" name="repasResto" class="form-control" id="inputRepasResto" 
                       value="<?= $infoFraisForfait[3]['quantite'] ?? '0' ?>"
                       placeholder="<?= $infoFraisForfait[3]['quantite'] ?? '0' ?>">
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
                <?php 
                foreach($listeFraisHorsForfait as $fraisHorsForfait){
                    include PATH_VIEWS . 'validerFiches/v_tableFichesHorsForfait.php';
                }    
                ?>
            </tbody>
        </table>
    </div>
</div>

<br><br>
<form class="form-inline">
    <div class="form-group">
        <label for="inputNbJustificatif" class="control-label">Nombre de justificatifs :</label>
        <input type="number" name="nbJustificatif" class="form-control" id="inputNbJustificatif" 
               style="width: 20% !important;" 
               value="<?= count($listeFraisHorsForfait); ?>"
               placeholder="<?= count($listeFraisHorsForfait); ?>">
    </div>
<br>

<button type="submit" class="btn btn-success">Valider</button>
<button type="reset" class="btn btn-danger">Réinitialiser</button>
</form>

<?php 
    echo '</div>';
} 
?>
