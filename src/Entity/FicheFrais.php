<?php

/**
 * Gestion des frais
 *
 * PHP Version 8
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    Lucas Bruel <lucasfox@outlook.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */

namespace App\Entity;

class FicheFrais {

    private ?string $idVisiteur = null;
    private ?string $mois = null;
    private ?float $montantValide = null;
    private ?Date $mois = null;

    public function __construct($params = null) {
        if (!is_null($params)) {
            foreach ($params as $cle => $valeur) {
                $this->$cle = $valeur;
            }
        }
    }

    public function getId (): string
    {
        return $this->id;
    }
    
    public function getLibelle(): string
    {
        return $this->libelle;
    }
}
