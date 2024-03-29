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
?>
<form action="/?uc=validerFiches&month=<?= $selectedMonth; ?>&visiteurId=<?= $selectedVisiteurId; ?>" method="post">
    <input name="case" type="hidden" value="ligneHorsForfait"/>
    <input id="inputIdLigne" name="idLigneHorsForfait" type="hidden" value="<?= $fraisHorsForfait['id']; ?>"/>
    <tr>
        <td>
            <div class="form-group">
                <input type="date" class="form-control" name="dateLigneHorsForfait" id="inputDate" placeholder="01/01/0001" value="<?= $fraisHorsForfait['date'] ?>">
            </div>
        </td>
        <td>
            <div class="form-group">
                <div class="input-group" <?= !$fraisHorsForfait['isDeny']==1 ? 'style="width: 100%;"' : ''; ?>>
                    <?= $fraisHorsForfait['isDeny']==1 ? '<div class="input-group-addon" style="background-color: #c9302c; color: white;">Refusé</div>' : ''; ?>
                    <input type="text" class="form-control" name="libelleLigneHorsForfait" id="inputLibelle" placeholder="Libellé" value="<?= $fraisHorsForfait['libelle'] ?>">
                </div>
            </div>
        </td>
        <td>
            <div class="form-group">
                <div class="input-group">
                    <input type="number" class="form-control" name="montantLigneHorsForfait" id="inputMontant" placeholder="0.00" value="<?= $fraisHorsForfait['montant'] ?>">
                    <div class="input-group-addon">€</div>
                </div>
            </div>
        </td>
        <td>
            <button type="submit" class="btn btn-success" name="buttonInput" value="corriger">Corriger</button>
            <button type="submit" class="btn btn-warning" name="buttonInput" value="reporter" <?= $fraisHorsForfait['isDeny']==1 ? 'disabled' : 'value="refuser"' ?>>Reporter</button>
            <button type="submit" class="btn btn-danger" name="buttonInput" <?= $fraisHorsForfait['isDeny']==1 ? 'disabled' : 'value="refuser"' ?>>Refuser</button>
            <button type="reset" class="btn btn-danger" style='margin-top: 5px;'>Réinitialiser</button>
        </td>
    </tr>
</form>
