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
 * @var $infoFiche array
 */
?>
<tr class="text-center">
    <td>
            <p><?= $infoFiche['nom'] . ' ' . $infoFiche['prenom']?></p>
    </td>
    <td>
       <p><?= $infoFiche['mois']?></p>
    </td>
    <td>
        <p><?= $infoFiche['totalForfait']?> €</p>
    </td>
        <td>
        <p><?= $infoFiche['totalHorsForfait']?> €</p>
    </td>
    <td>
        <p><?= $infoFiche['totalValide']?> €</p>
    </td>
    <td>
        <input type="checkbox" name="<?= $infoFiche['mois'].'-'.$infoFiche['id']?>">
    </td>
</tr>
