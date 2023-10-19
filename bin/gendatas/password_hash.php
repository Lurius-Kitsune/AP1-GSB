<?php

require './fonctions.php';
$pdo = new PDO('mysql:host=localhost;dbname=gsb_frais', 'userGsb', 'secret');
updateHashToDb(getLesComptable($pdo), true);
updateHashToDb(getLesVisiteurs($pdo), false);


function updateHashToDb(array $pdoResult, bool $isComptable): void {
    $pdo = new PDO('mysql:host=localhost;dbname=gsb_frais', 'userGsb', 'secret');
    foreach ($pdoResult as $user) {
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

function hashPassword(string $password) {
    $pwdHashMdp = password_hash($password, PASSWORD_DEFAULT);
    return $pwdHashMdp;
}
