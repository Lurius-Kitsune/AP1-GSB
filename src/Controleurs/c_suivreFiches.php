<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

/**
 * @var PdoGsb $pdo
 */

$listeInfoFiche = $pdo->getResumeFiche();

if(isset($_POST['action'])){
    $listeInfoFiche = $pdo->getResumeFiche($_POST['action']);
}

include PATH_VIEWS . 'suivreFiches/v_suivreFiches.php';