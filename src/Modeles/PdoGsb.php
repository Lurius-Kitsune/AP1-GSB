<?php

/**
 * Classe d'accès aux données.
 *
 * PHP Version 8
 *
 * @category  PPE
 * @package   GSB
 * @author    Cheri Bibi - Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL - CNED <jgil@ac-nice.fr>
 * @author    Marco Clin <marcoetude@gmail.com>
 * @author    Lucas Bruel <lucasfox@outlook.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.php.net/manual/fr/book.pdo.php PHP Data Objects sur php.net
 */

namespace Modeles;

use PDO;
use Outils\Utilitaires;

require '../config/bdd.php';

class PdoGsb
{

    protected $connexion;
    private static $instance = null;

    /**
     * Constructeur privé, crée l'instance de PDO qui sera sollicitée
     * pour toutes les méthodes de la classe
     */
    private function __construct()
    {
        $this->connexion = new PDO(DB_DSN, DB_USER, DB_PWD);
        $this->connexion->query('SET CHARACTER SET utf8');
    }

    /**
     * Méthode destructeur appelée dès qu'il n'y a plus de référence sur un
     * objet donné, ou dans n'importe quel ordre pendant la séquence d'arrêt.
     */
    public function __destruct()
    {
        $this->connexion = null;
    }

    /**
     * Fonction statique qui crée l'unique instance de la classe
     * Appel : $instancePdoGsb = PdoGsb::getPdoGsb();
     *
     * @return l'unique objet de la classe PdoGsb
     */
    public static function getPdoGsb(): PdoGsb
    {
        if (self::$instance == null) {
            self::$instance = new PdoGsb();
        }
        return self::$instance;
    }

    /**
     * Obtient le mdp hasher du login user.
     * 
     * @param string $login     login de l'user
     * @param bool $isComptable Est-t'il comptable ? (true/false)
     * 
     * @return ?string mdp de la bd
     */
    public function getMdpUser($login, $isComptable): ?string
    {
        if ($isComptable) {
            return $this->getMdpComptable($login);
        } else {
            return $this->getMdpVisiteur($login);
        }
    }

    /**
     * Fonction qui renvoie le mdp hasher du login comptable.
     *
     * @return string le mdp hasher du login utilisateur.
     */
    private function getMdpComptable($login): string
    {
        $requetePrepare = $this->connexion->prepare(
            'SELECT mdp '
                . 'FROM comptable '
                . 'WHERE comptable.login = :unLogin'
        );
        $requetePrepare->bindParam(':unLogin', $login, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetch(PDO::FETCH_OBJ)->mdp;
    }

    /**
     * Fonction qui renvoie le mdp hasher du login visiteur.
     *
     * @return ?string le mdp hasher du login utilisateur.
     */
    private function getMdpVisiteur($login): ?string
    {
        $requetePrepare = $this->connexion->prepare(
            'SELECT mdp '
                . 'FROM visiteur '
                . 'WHERE visiteur.login = :unLogin'
        );
        $requetePrepare->bindParam(':unLogin', $login, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetch(PDO::FETCH_OBJ)->mdp;
    }

    /**
     * Fonction qui renvoie l'utilisateur courant sous forme d'array,
     * false si il n'existe pas
     * 
     * @param string $login  le login de l'utilisateur (username)
     * @return array|bool
     */
    public function getUser($login): array|bool
    {
        $req = $this->getInfosComptable($login);
        if (is_array($req)) {
            $user = $this->getInfosComptable($login);
            $user["isComptable"] = true;
            return $user;
        } else {
            $req = $this->getInfosVisiteur($login);
            if (is_array($req)) {
                $user = $this->getInfosVisiteur($login);
                $user["isComptable"] = false;
                return $user;
            } else {
                return false;
            }
        }
    }

    /**
     * Retourne les informations d'un comptable
     *
     * @param String $login Login du comptable
     * @param String $mdp   Mot de passe du comptable
     *
     * @return array|bool   l'id, le nom et le prénom sous la forme d'un tableau associatif ou null si rien
     */
    public function getInfosComptable($login): array|bool
    {
        $requetePrepare = $this->connexion->prepare(
            'SELECT comptable.id AS id, comptable.nom AS nom, '
                . 'comptable.prenom AS prenom '
                . 'FROM comptable '
                . 'WHERE comptable.login = :unLogin'
        );
        $requetePrepare->bindParam(':unLogin', $login, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetch();
    }

    /**
     * Retourne les informations d'un visiteur
     *
     * @param String $login Login du visiteur
     * @param String $mdp   Mot de passe du visiteur
     *
     * @return array|bool   l'id, le nom et le prénom sous la forme d'un tableau associatif ou null si rien
     */
    public function getInfosVisiteur($login): array|bool
    {
        $requetePrepare = $this->connexion->prepare(
            'SELECT visiteur.id AS id, visiteur.nom AS nom, '
                . 'visiteur.prenom AS prenom '
                . 'FROM visiteur '
                . 'WHERE visiteur.login = :unLogin'
        );
        $requetePrepare->bindParam(':unLogin', $login, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetch();
    }

    /**
     * Retourne sous forme d'un tableau associatif toutes les lignes de frais
     * hors forfait concernées par les deux arguments.
     * La boucle foreach ne peut être utilisée ici car on procède
     * à une modification de la structure itérée - transformation du champ date-
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return array             Tous les champs des lignes de frais hors forfait sous la forme
     *                           d'un tableau associatif
     */
    public function getLesFraisHorsForfait($idVisiteur, $mois): array
    {
        $requetePrepare = $this->connexion->prepare(
            'SELECT * FROM lignefraishorsforfait '
                . 'WHERE lignefraishorsforfait.idvisiteur = :unIdVisiteur '
                . 'AND lignefraishorsforfait.mois = :unMois'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $lesLignesHorsForfait = $requetePrepare->fetchAll(PDO::FETCH_ASSOC);
        return $lesLignesHorsForfait;
    }

    /**
     * Passe une ligne Hors forfait en état "Refusé".
     *
     * @param String $id ID de la ligne Hors Forfait 
     *
     * @return null
     */
    public function denyUnFraisHorsForfait(string $id, string $idVisiteur): void
    {
        $requetePrepare = $this->connexion->prepare(
            'UPDATE lignefraishorsforfait '
                . 'SET isDeny = true '
                . 'WHERE lignefraishorsforfait.id = :unId '
                . 'AND lignefraishorsforfait.idvisiteur = :idVisiteur '
        );
        $requetePrepare->bindParam(':unId', $id, PDO::PARAM_STR);
        $requetePrepare->bindParam(':idVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
    }

    /**
     * Retourne le nombre de justificatif d'un visiteur pour un mois donné
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return int               le nombre entier de justificatifs
     */
    public function getNbjustificatifs($idVisiteur, $mois): int
    {
        $requetePrepare = $this->connexion->prepare(
            'SELECT fichefrais.nbjustificatifs as nb FROM fichefrais '
                . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
                . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $laLigne = $requetePrepare->fetch();
        return $laLigne['nb'];
    }

    /**
     * Retourne sous forme d'un tableau associatif toutes les lignes de frais
     * au forfait concernées par les deux arguments
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return array             l'id, le libelle et la quantité sous la forme d'un tableau
     *                           associatif
     */
    public function getLesFraisForfait($idVisiteur, $mois): array
    {
        $requetePrepare = $this->connexion->prepare(
            'SELECT fraisforfait.id as idfrais, '
                . 'fraisforfait.libelle as libelle, '
                . 'fraisforfait.montant as montant, '
                . 'lignefraisforfait.quantite as quantite '
                . 'FROM lignefraisforfait '
                . 'INNER JOIN fraisforfait '
                . 'ON fraisforfait.id = lignefraisforfait.idfraisforfait '
                . 'WHERE lignefraisforfait.idvisiteur = :unIdVisiteur '
                . 'AND lignefraisforfait.mois = :unMois '
                . 'AND fraisforfait.id != "4D" and fraisforfait.id != "56D" '
                . 'AND fraisforfait.id != "4E" and fraisforfait.id != "56E" AND fraisforfait.id != "KM" '
                . 'ORDER BY lignefraisforfait.idfraisforfait'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetchAll();
    }

    /**
     * Renvoie le frai kilométrique pour un visiteur et un mois donné en 
     * paramètres 
     * 
     * @param string $idVisiteur  ID du visiteur
     * @param string $mois        Mois sous la forme aaaamm
     * @return array
     */
    public function getLeFraisKm($idVisiteur, $mois): array | bool
    {
        $requetePrepare = $this->connexion->prepare(
            'SELECT fraisforfait.id as idfrais, '
                . 'fraisforfait.libelle as libelle, '
                . 'fraisforfait.montant as montant, '
                . 'lignefraisforfait.quantite as quantite '
                . 'FROM lignefraisforfait '
                . 'INNER JOIN fraisforfait '
                . 'ON fraisforfait.id = lignefraisforfait.idfraisforfait '
                . 'WHERE lignefraisforfait.idvisiteur = :unIdVisiteur '
                . 'AND lignefraisforfait.mois = :unMois '
                . 'AND lignefraisforfait.idfraisforfait = "4D" OR lignefraisforfait.idfraisforfait = "56D" '
                . 'OR lignefraisforfait.idfraisforfait = "4E" OR lignefraisforfait.idfraisforfait = "56E" '
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $result = $requetePrepare->fetch();
        if ($result == false) {
            $requetePrepare = $this->connexion->prepare(
                'SELECT fraisforfait.id as idfrais, '
                    . 'fraisforfait.libelle as libelle, '
                    . 'fraisforfait.montant as montant, '
                    . 'lignefraisforfait.quantite as quantite '
                    . 'FROM lignefraisforfait '
                    . 'INNER JOIN fraisforfait '
                    . 'ON fraisforfait.id = lignefraisforfait.idfraisforfait '
                    . 'WHERE lignefraisforfait.idvisiteur = :unIdVisiteur '
                    . 'AND lignefraisforfait.mois = :unMois '
                    . 'AND lignefraisforfait.idfraisforfait = "KM" '
            );
            $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
            $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
            $requetePrepare->execute();
            return $requetePrepare->fetch();
        }
        else {
            return $result;
        }
    }

    /**
     * Retourne tous les id de la table FraisForfait
     *
     * @return array les id de la table FraisForfait
     */
    public function getLesIdFrais(): array
    {
        $requetePrepare = $this->connexion->prepare(
            'SELECT fraisforfait.id as idfrais '
                . 'FROM fraisforfait '
                . 'WHERE fraisforfait.id != "KM" and id != "56D" and id != "4E" and id != "56E" '
                . 'ORDER BY fraisforfait.id'
        );
        $requetePrepare->execute();
        return $requetePrepare->fetchAll();
    }

    /**
     * Renvoie tout les frais kilométriques différents, avec leur id
     * leur libelle et leur montant
     * 
     * @return array
     */
    public function getLesFraisKmList(): array
    {
        $requetePrepare = $this->connexion->prepare(
            'SELECT * '
                . 'FROM fraisforfait '
                . 'WHERE id = "4D" OR id = "56D" OR id = "4E" OR id = "56E" OR id = "KM" '
                . 'ORDER BY fraisforfait.id'
        );
        $requetePrepare->execute();
        return $requetePrepare->fetchAll();
    }

    /**
     * Met à jour la table ligneFraisForfait
     * Met à jour la table ligneFraisForfait pour un visiteur et
     * un mois donné en enregistrant les nouveaux montants
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     * @param Array  $lesFrais   tableau associatif de clé idFrais et
     *                           de valeur la quantité pour ce frais
     *
     * @return null
     */
    public function majFraisForfait($idVisiteur, $mois, $lesFrais): void
    {
        $lesCles = array_keys($lesFrais);
        foreach ($lesCles as $unIdFrais) {
            $qte = $lesFrais[$unIdFrais];
            $requetePrepare = $this->connexion->prepare(
                'UPDATE lignefraisforfait '
                    . 'SET lignefraisforfait.quantite = :uneQte '
                    . 'WHERE lignefraisforfait.idvisiteur = :unIdVisiteur '
                    . 'AND lignefraisforfait.mois = :unMois '
                    . 'AND lignefraisforfait.idfraisforfait = :idFrais'
            );
            $requetePrepare->bindParam(':uneQte', $qte, PDO::PARAM_INT);
            $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
            $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
            $requetePrepare->bindParam(':idFrais', $unIdFrais, PDO::PARAM_STR);
            $requetePrepare->execute();
        }
    }

    /**
     * Met à jour le type de frais kilométriques d'un utilisateur et un mois donné.
     * Le différent type de frais est passé en paramètre avec un array.
     * L'array doit avoir les champs 'type' et 'oldType'
     * 
     * @param string $idVisiteur    L'ID du visiteur
     * @param string $mois          Le mois correspondant aux frais modifiés
     * @param array $lesFraisKm     L'array contenant l'ancien et le nouveau type de frais
     * @return void
     */
    public function majFraisKm($idVisiteur, $mois, $lesFraisKm): void
    {
        $requetePrepare = $this->connexion->prepare(
            'UPDATE lignefraisforfait '
                . 'SET lignefraisforfait.idfraisforfait = :idFrais '
                . 'WHERE lignefraisforfait.idvisiteur = :unIdVisiteur '
                . 'AND lignefraisforfait.mois = :unMois '
                . 'AND lignefraisforfait.idfraisforfait = :oldIdFrais'
        );
        $requetePrepare->bindParam(':idFrais', $lesFraisKm['type'], PDO::PARAM_STR);
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->bindParam(':oldIdFrais', $lesFraisKm['oldType'], PDO::PARAM_STR);
        $requetePrepare->execute();
    }

    /**
     * Met à jour la table ligneFraisForfait
     * Met à jour la table ligneFraisForfait pour un visiteur et
     * un mois donné en enregistrant les nouveaux montants
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     * @param array  $uneLigne   array correspondant à une ligne hors forfait
     *
     * @return null
     */
    public function majFraisHorsForfait($idVisiteur, $mois, array $uneLigne): void
    {
        $requetePrepare = $this->connexion->prepare(
            'UPDATE lignefraishorsforfait '
                . 'SET lignefraishorsforfait.date = :uneDate, '
                . 'lignefraishorsforfait.libelle = :unLibelle, '
                . 'lignefraishorsforfait.montant = :unMontant '
                . 'WHERE lignefraishorsforfait.idvisiteur = :unIdVisiteur '
                . 'AND lignefraishorsforfait.mois = :unMois '
                . 'AND lignefraishorsforfait.id = :id'
        );
        $requetePrepare->bindValue(':uneDate', $uneLigne['date'], PDO::PARAM_STR);
        $requetePrepare->bindValue(':unLibelle', $uneLigne['libelle'], PDO::PARAM_STR);
        $requetePrepare->bindValue(':unMontant', $uneLigne['montant'], PDO::PARAM_STR);
        $requetePrepare->bindValue(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindValue(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->bindValue(':id', $uneLigne['id'], PDO::PARAM_INT);
        $requetePrepare->execute();
    }

    /**
     * Met à jour le nombre de justificatifs de la table ficheFrais
     * pour le mois et le visiteur concerné
     *
     * @param String  $idVisiteur      ID du visiteur
     * @param String  $mois            Mois sous la forme aaaamm
     * @param Integer $nbJustificatifs Nombre de justificatifs
     *
     * @return null
     */
    public function majNbJustificatifs($idVisiteur, $mois, $nbJustificatifs): void
    {
        $requetePrepare = $this->connexion->prepare(
            'UPDATE fichefrais '
                . 'SET nbjustificatifs = :unNbJustificatifs '
                . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
                . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam(
            ':unNbJustificatifs',
            $nbJustificatifs,
            PDO::PARAM_INT
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
    }

    /**
     * Teste si un visiteur possède une fiche de frais pour le mois passé en argument
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return vrai ou faux
     */
    public function estPremierFraisMois($idVisiteur, $mois): bool
    {
        $boolReturn = false;
        $requetePrepare = $this->connexion->prepare(
            'SELECT fichefrais.mois FROM fichefrais '
                . 'WHERE fichefrais.mois = :unMois '
                . 'AND fichefrais.idvisiteur = :unIdVisiteur'
        );
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
        if (!$requetePrepare->fetch()) {
            $boolReturn = true;
        }
        return $boolReturn;
    }

    /**
     * Retourne le dernier mois en cours d'un visiteur
     *
     * @param String $idVisiteur ID du visiteur
     *
     * @return String le mois sous la forme aaaamm
     */
    public function dernierMoisSaisi($idVisiteur): string
    {
        $requetePrepare = $this->connexion->prepare(
            'SELECT MAX(mois) as dernierMois '
                . 'FROM fichefrais '
                . 'WHERE fichefrais.idvisiteur = :unIdVisiteur'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
        $laLigne = $requetePrepare->fetch();
        $dernierMois = $laLigne['dernierMois'];
        return $dernierMois;
    }

    /**
     * Crée une nouvelle fiche de frais et les lignes de frais au forfait
     * pour un visiteur et un mois donnés
     *
     * Récupère le dernier mois en cours de traitement, met à 'CL' son champs
     * idEtat, crée une nouvelle fiche de frais avec un idEtat à 'CR' et crée
     * les lignes de frais forfait de quantités nulles
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return null
     */
    public function creeNouvellesLignesFrais($idVisiteur, $mois): void
    {
        $dernierMois = $this->dernierMoisSaisi($idVisiteur);
        $laDerniereFiche = $this->getLesInfosFicheFrais($idVisiteur, $dernierMois);
        if ($laDerniereFiche['idEtat'] == 'CR') {
            $this->majEtatFicheFrais($idVisiteur, $dernierMois, 'CL');
        }
        $requetePrepare = $this->connexion->prepare(
            'INSERT INTO fichefrais (idvisiteur,mois,nbjustificatifs,'
                . 'montantvalide,datemodif,idetat) '
                . "VALUES (:unIdVisiteur,:unMois,0,0,now(),'CR')"
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $lesIdFrais = $this->getLesIdFrais();
        foreach ($lesIdFrais as $unIdFrais) {
            $requetePrepare = $this->connexion->prepare(
                'INSERT INTO lignefraisforfait (idvisiteur,mois,'
                    . 'idfraisforfait,quantite) '
                    . 'VALUES(:unIdVisiteur, :unMois, :idFrais, 0)'
            );
            $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
            $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
            $requetePrepare->bindParam(':idFrais', $unIdFrais['idfrais'], PDO::PARAM_STR);
            $requetePrepare->execute();
        }
    }

    /**
     * Crée un nouveau frais hors forfait pour un visiteur un mois donné
     * à partir des informations fournies en paramètre
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     * @param String $libelle    Libellé du frais
     * @param String $date       Date du frais au format français jj//mm/aaaa
     * @param Float  $montant    Montant du frais
     *
     * @return null
     */
    public function creeNouveauFraisHorsForfait($idVisiteur, $mois, $libelle, $date, $montant): void
    {
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            $dateFr = $date;
        } else {
            $dateFr = Utilitaires::dateFrancaisVersAnglais($date);
        }
        $requetePrepare = $this->connexion->prepare(
            'INSERT INTO lignefraishorsforfait '
                . 'VALUES (null, :unIdVisiteur,:unMois, :unLibelle, :uneDateFr,'
                . ':unMontant, false) '
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unLibelle', $libelle, PDO::PARAM_STR);
        $requetePrepare->bindParam(':uneDateFr', $dateFr, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMontant', $montant, PDO::PARAM_INT);
        $requetePrepare->execute();
    }

    /**
     * Supprime le frais hors forfait dont l'id est passé en argument
     *
     * @param String $idFrais ID du frais
     *
     * @return null
     */
    public function supprimerFraisHorsForfait($idFrais): void
    {
        $requetePrepare = $this->connexion->prepare(
            'DELETE FROM lignefraishorsforfait '
                . 'WHERE lignefraishorsforfait.id = :unIdFrais'
        );
        $requetePrepare->bindParam(':unIdFrais', $idFrais, PDO::PARAM_STR);
        $requetePrepare->execute();
    }

    /**
     * Retourne les mois pour lesquel un visiteur a une fiche de frais
     *
     * @param String $idVisiteur ID du visiteur
     *
     * @return array un tableau associatif de clé un mois -aaaamm- et de valeurs
     *         l'année et le mois correspondant
     */
    public function getLesMoisDisponibles($idVisiteur): array
    {
        $requetePrepare = $this->connexion->prepare(
            'SELECT fichefrais.mois AS mois FROM fichefrais '
                . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
                . 'ORDER BY fichefrais.mois desc'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
        $lesMois = array();
        while ($laLigne = $requetePrepare->fetch()) {
            $mois = $laLigne['mois'];
            $numAnnee = substr($mois, 0, 4);
            $numMois = substr($mois, 4, 2);
            $lesMois[] = array(
                'mois' => $mois,
                'numAnnee' => $numAnnee,
                'numMois' => $numMois
            );
        }
        return $lesMois;
    }

    /**
     * Retourne les les visiteur pour un mois donné si
     * La fiche est cloturer
     *
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return array un tableau avec des champs de jointure entre une fiche de frais
     * 
     */
    public function getVisiteurHavingFicheMonth($mois): array|bool
    {
        $requetePrepare = $this->connexion->prepare(
            'SELECT visiteur.nom as nom, '
                . 'visiteur.prenom as prenom, '
                . 'visiteur.id as id '
                . 'FROM visiteur '
                . 'INNER JOIN fichefrais on fichefrais.idvisiteur = visiteur.id '
                . 'WHERE fichefrais.mois = :unMois '
                . 'AND fichefrais.idetat = "CL"'
        );
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $lesLignes = $requetePrepare->fetchAll();
        return $lesLignes;
    }

    /**
     * Retourne les informations d'une fiche de frais d'un visiteur pour un
     * mois donné
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return array un tableau avec des champs de jointure entre une fiche de frais
     *         et la ligne d'état
     */
    public function getLesInfosFicheFrais($idVisiteur, $mois): array
    {
        $requetePrepare = $this->connexion->prepare(
            'SELECT fichefrais.idetat as idEtat, '
                . 'fichefrais.datemodif as dateModif,'
                . 'fichefrais.nbjustificatifs as nbJustificatifs, '
                . 'fichefrais.montantvalide as montantValide, '
                . 'etat.libelle as libEtat '
                . 'FROM fichefrais '
                . 'INNER JOIN etat ON fichefrais.idetat = etat.id '
                . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
                . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $laLigne = $requetePrepare->fetch();
        return $laLigne;
    }

    /**
     * Modifie l'état et la date de modification d'une fiche de frais.
     * Modifie le champ idEtat et met la date de modif à aujourd'hui.
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     * @param String $etat       Nouvel état de la fiche de frais
     *
     * @return null
     */
    public function majEtatFicheFrais($idVisiteur, $mois, $etat): void
    {
        $requetePrepare = $this->connexion->prepare(
            'UPDATE fichefrais '
                . 'SET idetat = :unEtat, datemodif = now() '
                . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
                . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam(':unEtat', $etat, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
    }

    /**
     * Modifie l'état et la date de modification d'une fiche de frais.
     * Modifie le champ idEtat et met la date de modif à aujourd'hui.
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     * @param String $etat       Nouvel état de la fiche de frais
     *
     * @return null
     */
    public function majMontantValiderFicheFrais($idVisiteur, $mois): void
    {
        $montant = (float) $this->getMontantTotalForfait($idVisiteur, $mois) + (float) $this->getMontantTotalHorsForfait($idVisiteur, $mois);
        $requetePrepare = $this->connexion->prepare(
            'UPDATE fichefrais '
                . 'SET montantvalide = :unMontant '
                . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
                . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam(':unMontant', $montant, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
    }

    /**
     * Renvoie le nom d'un visiteur médical à partir de son ID
     * 
     * @param type $id  l'ID du visiteur médical
     * @return array
     */
    public function getNomVisiteur($id): array
    {
        $requetePrepare = $this->connexion->prepare(
            'select visiteur.nom, visiteur.prenom ' .
                'from visiteur ' .
                'where visiteur.id = :id'
        );
        $requetePrepare->bindParam('id', $id, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retourne les noms de tout les visiteurs en vue
     * de les afficher dans la maquette de validation
     * de fiches de frais
     * 
     * @return array|bool Les noms de tout les visiteurs
     */
    public function getNomsVisiteurs(): array|bool
    {
        $requetePrepare = $this->connexion->prepare(
            'SELECT visiteur.prenom, visiteur.nom, visiteur.id '
                . 'FROM visiteur '
                . 'ORDER BY visiteur.nom'
        );
        $requetePrepare->execute();
        return $requetePrepare->fetchAll();
    }

    /**
     * Retourne l'ensemble des mois disponibles en vue
     * de les afficher dans la maquette de validation
     * de fiches de frais
     * 
     * @return array L'ensemble des mois disponibles
     */
    public function getTousLesMoisDisponibles(): array
    {
        $requetePrepare = $this->connexion->prepare(
            'SELECT DISTINCT fichefrais.mois AS mois FROM fichefrais '
                . 'ORDER BY fichefrais.mois desc'
                . ''
        );
        $requetePrepare->execute();
        $lesMois = array();
        while ($laLigne = $requetePrepare->fetch()) {
            $mois = $laLigne['mois'];
            $numAnnee = substr($mois, 0, 4);
            $numMois = substr($mois, 4, 2);
            $lesMois[] = array(
                'numAnnee' => $numAnnee,
                'numMois' => $numMois
            );
        }
        return $lesMois;
    }

    /**
     * Retourne l'ensemble des mois disponibles où la fiche correspondante
     * est dans l'état cloturé.
     * 
     * @return array L'ensemble des mois correspondants
     */
    public function getMoisFichesFraisCloturer(): array
    {
        $requetePrepare = $this->connexion->prepare(
            'SELECT DISTINCT fichefrais.mois AS mois FROM fichefrais '
                . 'WHERE fichefrais.idetat = "CL" '
                . 'ORDER BY fichefrais.mois desc'
        );
        $requetePrepare->execute();
        $lesMois = array();
        while ($laLigne = $requetePrepare->fetch()) {
            $mois = $laLigne['mois'];
            $numAnnee = substr($mois, 0, 4);
            $numMois = substr($mois, 4, 2);
            $lesMois[] = array(
                'numAnnee' => $numAnnee,
                'numMois' => $numMois
            );
        }
        return $lesMois;
    }

    /**
     * Retourne vrai si la fiche dont le mois et l'id du visiteur
     * ont été renseigné existe, faux sinon
     * 
     * @param int $mois          mois au format aaaamm 
     * @param string $idVisiteur ID du visiteur
     * 
     * @return bool              Est-ce que la fiche existe ? (true/false)
     */
    private function ficheExiste(int $mois, string $idVisiteur): bool
    {
        $requetePrepare = $this->connexion->prepare(
            'select * from fichefrais '
                . 'where mois = :mois '
                . 'and idVisiteur = :idVisiteur'
        );
        $requetePrepare->bindParam(':mois', $mois, PDO::PARAM_INT);
        $requetePrepare->bindParam(':idVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
        if (count($requetePrepare->fetchAll()) == 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Retourne les info de la ligne de frais hors forfaits dont l'id
     * a été passé en paramètres
     * 
     * @param int $idLigne ID de la ligne
     * 
     * @return array        Informations sur la ligne hors forfait désirée
     */
    private function getFraisHorsForfait($idLigne): array
    {
        $requetePrepare = $this->connexion->prepare(
            'select * from lignefraishorsforfait '
                . 'where lignefraishorsforfait.id = :id'
        );
        $requetePrepare->bindParam(':id', $idLigne, PDO::PARAM_INT);
        $requetePrepare->execute();
        return $requetePrepare->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Retourne le mois de la ligne de frais hors forfaits
     * dont l'id a été renseigné
     * 
     * @param int $idLigneHf ID de la ligne hors forfait
     * 
     * @return array         Le mois souhaité
     */
    private function recupMoisLigneHf(string $idLigneHf): array
    {
        $requetePrepare = $this->connexion->prepare(
            'select mois from lignefraishorsforfait '
                . 'where id = :id '
        );
        $requetePrepare->bindParam(':id', $idLigneHf, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Retourne le mois suivant celui donné dans la fonction
     * 
     * @param string $mois mois au format aaaamm
     * 
     * @return string    Le mois suivant celui donné
     */
    private function getMoisSuivant($mois): string
    {
        $partieAnnee = (int)substr((string)$mois, 0, 4);
        $partieMois = (int)substr((string)$mois, -2);
        if ($partieMois == 12) {
            $partieAnnee += 1;
            $partieMois = 01;
            return (string)$partieAnnee . (string)$partieMois;
        } else {
            $partieMois += 1;
            if ($partieMois < 10) {
                $partieMois = '0' . (string)$partieMois;
            }
            return (string) $partieAnnee . (string) $partieMois;
        }
    }

    /**
     * 
     * Fonction supprimant de la base de données la ligne
     * hors forfait choisit en fonction de son mois, de son 
     * visiteur, et de son libellé
     * 
     * @param string $mois       mois sous la forme aaaamm
     * @param string $idVisiteur ID du visiteur
     * @param string $libelle    libellé de la ligne hors forfait
     * 
     * @return null
     */
    private function deleteLigneHf($mois, $idVisiteur, $libelle): void
    {
        $requetePrepare = $this->connexion->prepare(
            'delete from lignefraishorsforfait ' .
                'where mois = :mois and idVisiteur = :idVisiteur ' .
                'and libelle = :libelle'
        );
        $requetePrepare->bindParam(':mois', $mois, PDO::PARAM_STR);
        $requetePrepare->bindParam(':idVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':libelle', $libelle, PDO::PARAM_STR);
        $requetePrepare->execute();
    }

    /**
     * Reporte la ligne de frais hors forfait du visiteur passé en paramètres
     * sur sa fiche de paie du mois suivant.
     * 
     * Si il n'y a aucune fiche de frais le mois suivant, en créé une
     * puis reporte la ligne.
     * 
     * @param string $idVisiteur ID du visiteur
     * @param int $idLigneHf     ID de la ligne hors forfait
     * 
     * @return null
     */
    public function reportLigneHf(string $idVisiteur, string $idLigneHf): void
    {
        $mois = $this->recupMoisLigneHf($idLigneHf);
        $moisSuivant = $this->getMoisSuivant($mois['mois']);
        $ligneAReporter = $this->getFraisHorsForfait($idLigneHf);
        if ($this->ficheExiste($moisSuivant, $idVisiteur)) {
            $this->creeNouveauFraisHorsForfait($idVisiteur, $moisSuivant, $ligneAReporter['libelle'], $ligneAReporter['date'], $ligneAReporter['montant']);
            $this->deleteLigneHf($mois['mois'], $idVisiteur, $ligneAReporter['libelle']);
        } else {
            $this->creeNouvellesLignesFrais($idVisiteur, $moisSuivant);
            $this->creeNouveauFraisHorsForfait($idVisiteur, $moisSuivant, $ligneAReporter['libelle'], $ligneAReporter['date'], $ligneAReporter['montant']);
            $this->deleteLigneHf($mois['mois'], $idVisiteur, $ligneAReporter['libelle']);
        }
    }

    /**
     * Retourne nom,prenom,idvisiteur,mois,montantvalide,totalHorsForfait,totalForfait 
     * des fiche dont l'état est en VA
     *
     * @return array un tableau avec des champs de jointure entre d'une fiche de frais
     * 
     */
    public function getResumeFiche(): array|bool
    {
        $requetePrepare = $this->connexion->prepare(
            'SELECT visiteur.nom as nom, '
                . 'visiteur.prenom as prenom, '
                . 'visiteur.id as id, '
                . 'fichefrais.mois, '
                . 'fichefrais.montantvalide as totalValide '
                . 'FROM visiteur '
                . 'INNER JOIN fichefrais on fichefrais.idvisiteur = visiteur.id '
                . 'WHERE fichefrais.idetat = "VA" '
                . 'order by mois '
        );
        $requetePrepare->execute();
        $lesLignes = $requetePrepare->fetchAll();
        foreach ($lesLignes as $cleLigne => $uneLigne) {
            $lesLignes[$cleLigne] = array_merge($lesLignes[$cleLigne], array(
                'totalForfait' => $this->getMontantTotalForfait($uneLigne['id'], $uneLigne['mois']),
                'totalHorsForfait' => $this->getMontantTotalHorsForfait($uneLigne['id'], $uneLigne['mois']),
            ));
        }
        return $lesLignes;
    }

    /**
     * 
     * Fonction retournant le montant total des frais forfaitisés
     * pour un visiteur et un mois donné
     * 
     * @param string $idVisiteur ID du visiteur
     * @param string $mois       mois sous la forme aaaamm
     * 
     * @return string            Le montant total des frais forfaitisés
     */
    public function getMontantTotalForfait(string $idVisiteur, string $mois): string
    {
        $requetePrepare = $this->connexion->prepare(
            'SELECT SUM(fraisforfait.montant*lignefraisforfait.quantite) as totalForfait '
                . 'FROM lignefraisforfait '
                . 'INNER JOIN fraisforfait on lignefraisforfait.idfraisforfait = fraisforfait.id '
                . 'WHERE idvisiteur = :idvisiteur and mois = :mois'
        );
        $requetePrepare->bindParam(':idvisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':mois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetchColumn();
    }

    /**
     * 
     * Fonction retournant le montant total des frais hors forfait
     * pour un visiteur et un mois donné
     * 
     * @param string $idVisiteur ID du visiteur
     * @param string $mois       mois sous la forme aaaamm
     * 
     * @return string            Le montant total des frais hors forfait
     */
    public function getMontantTotalHorsForfait(string $idVisiteur, string $mois): string
    {
        $requetePrepare = $this->connexion->prepare(
            'SELECT SUM(lignefraishorsforfait.montant) as totalHorsForfait '
                . 'FROM lignefraishorsforfait '
                . 'WHERE idvisiteur = :idvisiteur and mois = :mois '
                . 'AND isDeny = false'
        );
        $requetePrepare->bindParam(':idvisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':mois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        return strval($requetePrepare->fetchColumn());
    }
}
