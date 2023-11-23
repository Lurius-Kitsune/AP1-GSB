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

class LigneHorsForfait {

    private ?int $id = null;
    private ?string $idVisiteur = null;
    private ?string $mois = null;
    private ?string $libelle = null;
    private ?string $date = null;
    private ?int $montant = null;
    private bool $isDeny = false;

    public function __construct($params = null) {
        if (!is_null($params)) {
            foreach ($params as $cle => $valeur) {
                $this->$cle = $valeur;
            }
        }
    }

    public function isDeny(): bool
    {
        return $this->isDeny;
    }
    
    public function setLibelle(string $libelle): void
    {
        $this->libelle = $libelle;
    }
}
