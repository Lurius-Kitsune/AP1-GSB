<?php

/**
 * Vue Accueil
 *
 * PHP Version 8
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */

?>
<div id="accueil">
    <h2>
        Gestion des frais<small> - Comptable : 
            <?= $_SESSION['prenom'] . ' ' . $_SESSION['nom'] ?></small>
    </h2>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <span class="glyphicon glyphicon-bookmark"></span>
                    Navigation
                </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <a href="index.php?uc=validerFiches"
                           class="btn btn-primary btn-lg" role="button">
                            <span class="glyphicon glyphicon-ok"></span>
                            <br>Valider des fiches de frais</a>
                        <a href="#"
                           class="btn btn-primary btn-lg" role="button">
                            <span class="glyphicon glyphicon-euro"></span>
                            <br>Suivre le paiement des fches de frais</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>