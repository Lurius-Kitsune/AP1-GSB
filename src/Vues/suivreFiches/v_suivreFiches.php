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

/**
 * @var $listesInfoFiche array
 */

use Outils\Utilitaires;
?>
<div class="panel user">
    <div class="panel-heading">
        <p class="panel-title">Suivre le payements des fiches</p>
    </div>
    <table border="1" class="table border-warning table-bordered" style="margin-bottom: 0px !important;">
        <thead>
            <tr>
                <?php 
                $col = ['Visiteur', 'Mois', 'Total forfait', 'Total hors forfait', 'Montant validé', 'Mettre en payement'];
                foreach ($col as $value) {
                    echo "<td class='gras border-warning text-center'>$value</td>";
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $listeInfoFiche = array(
                array(
                    'nom' => 'Bob',
                    'prenom' => 'Patrick',
                    'mois' => Utilitaires::dateAnglaisVersFrancais('2023-11-2'),
                    'totalForfait' => 12,
                    'totalHorsForfait' => 10,
                    'totalValider' => 123,
                )
            );
            foreach ($listeInfoFiche as $infoFiche) {
                include PATH_VIEWS . 'suivreFiches/v_ligneTableSuivis.php';
            }
            ?>
        </tbody>
    </table>
</div>