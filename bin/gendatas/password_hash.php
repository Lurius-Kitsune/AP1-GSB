<?php

require './fonctions.php';
$pdo = new PDO('mysql:host=localhost;dbname=gsb_frais', 'userGsb', 'secret');
updateHashToDb(getLesComptable($pdo), true);
updateHashToDb(getLesVisiteurs($pdo), false);

/**
 * Met à jours les mdp en les hashant
 * @param array $pdoResult resultat pdo
 * @param bool $isComptable
 * @return void
 */
function updateHashToDb(array $pdoResult, bool $isComptable): void {
    $pdo = new PDO('mysql:host=localhost;dbname=gsb_frais', 'userGsb', 'secret');
    foreach ($pdoResult as $user) {
        
        // Differencie si l'user est comptable.
        if ($isComptable) {
            $req = $pdo->prepare('UPDATE comptable SET mdp= :hashMdp  WHERE id= :unId ');
        } else {
            $req = $pdo->prepare('UPDATE visiteur SET mdp= :hashMdp  WHERE id= :unId ');
        }
        $req->bindParam(':hashMdp', hashPassword($user['mdp']), PDO::PARAM_STR);
        $req->bindParam(':unId', $user['id'], PDO::PARAM_STR);
        $req->execute();
    }
}

/**
 * Hash le mdp
 * @param string $password mdp en brut
 * @return string mdp hasher
 */
function hashPassword(string $password) {
    $pwdHashMdp = password_hash($password, PASSWORD_DEFAULT);
    return $pwdHashMdp;
}
