<?php

require './fonctions.php';
$pdo = new PDO('mysql:host=localhost;dbname=gsb_gpt_car_b3', 'userGsbVuln', 'secret');
updateHashToDb(getLesVisiteurs($pdo));
updateHashToDb(getLesComptable($pdo));

function updateHashToDb(array $pdoResult): void
{
    $pdo = new PDO('mysql:host=localhost;dbname=gsb_gpt_car_b3', 'userGsbVuln', 'secret');
    foreach ($pdoResult as $user) {
        $req = $pdo->prepare('UPDATE visiteur SET mdp= :hashMdp  WHERE id= :unId ');
        $req->bindParam(':hashMdp', hashPassword($user['mdp']), PDO::PARAM_STR);
        $req->bindParam(':unId', $user['id'], PDO::PARAM_STR);
        $req->execute();
    }
}

function hashPassword(string $password)
{
    $pwdHashMdp = password_hash($password, PASSWORD_DEFAULT);
    return $pwdHashMdp;
}
