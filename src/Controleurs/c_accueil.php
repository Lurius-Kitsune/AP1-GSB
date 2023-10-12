<?php

/**
 * Gestion de l'accueil
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

if ($estConnecte) {
    include_once PATH_VIEWS . 'v_entete.php';
    $_SESSION['isComptable'] = true; // @TO-DO : Delete it
    if ($_SESSION['isComptable']){
	include PATH_VIEWS . 'v_accueil_comptable.php';
    }
    else {
        include PATH_VIEWS . 'v_accueil_visiteur.php';
    }
} else {
    include PATH_VIEWS . 'v_connexion.php';
}
