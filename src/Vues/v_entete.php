<?php
/**
 * Vue Entête
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
 * @link      https://getbootstrap.com/docs/3.3/ Documentation Bootstrap v3
 */
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta charset="UTF-8">
        <title>Intranet du Laboratoire Galaxy-Swiss Bourdin</title> 
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="./styles/bootstrap/bootstrap.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
        <!-- jQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

        <!-- Bootstrap JS -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <!-- Bootstrap Select JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
        <link href="./styles/style.css" rel="stylesheet">
        <script src="js/affichageNbLignes.js" defer></script>
    </head>
    <body>
        <style>
            :root {
                --couleur: <?= $_SESSION['isComptable'] ? "#ff8a00" : "#337ab7"; ?>;
            }
        </style>
        <div class="container">
            <?php
            $uc = filter_input(INPUT_GET, 'uc', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if ($estConnecte) {
                ?>
                <div class="header">
                    <div class="row vertical-align">
                        <div class="col-md-4">
                            <h1>
                                <img src="./images/logo.jpg" class="img-responsive" 
                                     alt="Laboratoire Galaxy-Swiss Bourdin" 
                                     title="Laboratoire Galaxy-Swiss Bourdin">
                            </h1>
                        </div>
                        <div class="col-md-8">
                            <ul class="nav nav-pills pull-right user" role="tablist">
                                <li <?php if (!$uc || $uc == 'accueil') { ?>class="active" <?php } ?>>
                                    <a href="/">
                                        <span class="glyphicon glyphicon-home"></span>
                                        Accueil
                                    </a>
                                </li>
                                <?php
                                if ($_SESSION['isComptable']) {
                                    ?>
                                    <li <?php if ($uc == 'validerFiches') { ?>class="active"<?php } ?>>
                                        <a href="/?uc=validerFiches">
                                            <span class="glyphicon glyphicon-ok"></span>
                                            Valider les fiches de frais
                                        </a>
                                    </li>
                                    <li <?php if ($uc == 'suivreFiches') { ?>class="active"<?php } ?>>
                                        <a href="/?uc=suivreFiches">
                                            <span class="glyphicon glyphicon-euro"></span>
                                            Suivre le paiement des fiches de frais
                                        </a>
                                    </li>
                                <?php } else { ?>
                                    <li <?php if ($uc == 'gererFrais') { ?>class="active"<?php } ?>>
                                        <a href="/?uc=gererFrais&action=saisirFrais">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                            Renseigner la fiche de frais
                                        </a>
                                    </li>
                                    <li <?php if ($uc == 'etatFrais') { ?>class="active"<?php } ?>>
                                        <a href="/index.php?uc=etatFrais&action=selectionnerMois">
                                            <span class="glyphicon glyphicon-list-alt"></span>
                                            Afficher mes fiches de frais
                                        </a>
                                    </li>
                                <?php } ?>
                                <li 
                                    <?php if ($uc == 'deconnexion') { ?>class="active"<?php } ?>>
                                    <a href="/?uc=deconnexion&action=demandeDeconnexion">
                                        <span class="glyphicon glyphicon-log-out"></span>
                                        Déconnexion
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <?php
                } else {
                    ?>   
                    <h1>
                        <img src="./images/logo.jpg"
                             class="img-responsive center-block"
                             alt="Laboratoire Galaxy-Swiss Bourdin"
                             title="Laboratoire Galaxy-Swiss Bourdin">
                    </h1>
                <?php } ?>
            </div>

