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
<?php include PATH_VIEWS . 'suivreFiches/v_actionSuivre.php'; ?>
<?php include PATH_VIEWS . 'suivreFiches/v_filter.php'; ?>

<script src="/js/suivreFiches/selectAll.js"></script>
<form action="/?uc=suivreFiches&action=fichePaiement" method="POST">
<div class="panel user">
    <div class="panel-heading">
        <p class="panel-title">Suivre le paiement des fiches</p>
    </div>
    <table border="1" class="table border-warning bg-warning table-bordered" style="margin-bottom: 0px !important;">
        <thead>
            <tr>
                <?php 
                $col = ['Visiteur', 'Mois', 'Total forfait', 'Total hors forfait', 'Montant validé', 'Mettre en paiement'];
                foreach ($col as $value) {
                    echo "<td class='gras border-warning text-center'>$value</td>";
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($listeInfoFiche as $infoFiche) {
                include PATH_VIEWS . 'suivreFiches/v_ligneTableSuivis.php';
            }
            ?>
        </tbody>
    </table>
</div>
</form>
