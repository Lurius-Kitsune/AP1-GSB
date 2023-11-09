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
<form action="">
    <tr>
        <td>
            <div class="form-group">
                <input type="date" class="form-control" id="inputDate" placeholder="01/01/0001" value="<?= $fraisHorsForfait['4'] ?>">
            </div>
        </td>
        <td>
            <div class="form-group">
                <input type="text" class="form-control" id="inputLibelle" placeholder="Libellé" value="<?= $fraisHorsForfait['libelle'] ?>">
            </div>
        </td>
        <td>
            <div class="form-group">
                <div class="input-group">
                    <input type="number" class="form-control" id="inputPrix" placeholder="0.00" value="<?= $fraisHorsForfait['montant'] ?>">
                    <div class="input-group-addon">€</div>
                </div>
            </div>
        </td>
        <td>
            <button type="submit" class="btn btn-success">Corriger</button>
            <button type="reset" class="btn btn-danger">Réinitialiser</button>
        </td>
    </tr>
</form>