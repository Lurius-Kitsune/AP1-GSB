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
<div>
    <form action="" method="get">
        <div>
            <p id="visiteur">Choisir le visiteur : 
                <select class="form-control form-control-md col-2">
                    <option>Large select</option>
                </select>
            </p>
        </div>


        <p id="mois" class="gras">Mois : 
            <select name="mois" class="dropdown-menu" aria-labelledby="dropdownMenu3">
                <option value="1">Réponse 1</option>
                <option value="2">Réponse 2</option>
            </select>
        </p>
    </form>
    <h3 class="gras orange">Valider la fiche de frais</h3>

    <h4>Eléments forfaitisés</h4>
    <p class="gras">Forfait Etape</p>
    <input type="text" name="forfaitEtape">
    <p class="gras">Frais Kilométrique</p>
    <input type="text" name="fraisKm">
    <p class="gras">Nuitée Hôtel</p>
    <input type="text" name="nuitHotel">
    <p class="gras">Repas Restaurant</p>
    <input type="text" name="repasResto">
    <br>

    <button class="ok">
        <span>Corriger</span>
    </button>
    <button class="nok">
        <span>Réinitialiser</span>
    </button>
    <br><br>

    <table border="1">
        <thead>
            <tr class="panel-heading">
                <th>Descriptif des éléments hors forfait</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="gras">Date</td>
                <td class="gras">Libellé</td>
                <td class="gras">Montant</td>
            </tr>
            <tr>
                <td>12/08/2022</td>
                <td>achat de fleurs</td>
                <td>29.90</td>
                <td>
                    <button>
                        <span>Corriger</span>
                    </button><button>
                        <span>Réinitialiser</span>
                    </button>
                </td>
            </tr>
            <tr>
                <td>14/08/2022</td>
                <td>taxi</td>
                <td>32.50</td>
                <td>
                    <button class="ok">
                        <span>Corriger</span>
                    </button>
                    <button class="nok">
                        <span>Réinitialiser</span>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
    <br><br>

    <p class="gras" >Nombres de justificatifs : </p>
    <input type="text" name="nbJustificatifs">

    <br>

    <button class="ok">
        <span>Corriger</span>
    </button>
    <button>
        <span class="nok">Réinitialiser</span>
    </button>
</div>


