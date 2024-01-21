<?php

function initCss($pdf) : void{
    $pdf->SetFont('helvetica', '', 10);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFillColor(255, 255, 255);
}

$idVisiteur = filter_input(INPUT_GET, 'idVisiteur', FILTER_SANITIZE_SPECIAL_CHARS);
$mois = filter_input(INPUT_GET, 'mois', FILTER_SANITIZE_SPECIAL_CHARS);


$identiteVisiteur = $pdo->getNomVisiteur($idVisiteur);
$lesFraisForfaits = $pdo->getLesFraisForfait($idVisiteur, $mois);
$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);

// Crée une nouvelle instance PDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Définit les informations du document
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('GSB');
$pdf->SetTitle('État de frais engagés');
$pdf->SetSubject('Frais Engagés');
$pdf->SetKeywords('TCPDF, PDF, exemple, état des frais, GSB');

// Supprimer les en-têtes et pieds de page par défaut
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Définit les marges
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

// Ajoute une page
$pdf->AddPage();

// Chemin vers le logo
$logoPath = '../public/images/logo.jpg'; // Assurez-vous que le chemin d'accès est correct

// Insérer le logo
$pdf->Image($logoPath, '88', '', 40, 20, 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);

// Définit la police
$pdf->SetFont('helvetica', '', 10);

// Titre
$pdf->Ln(25); // Déplacez le curseur vers le bas pour vous éloigner du logo
$pdf->SetFont('helvetica', 'B', 12);
$pdf->SetTextColor(34, 66, 124);
$pdf->Cell(0, 0, 'ÉTAT DE FRAIS ENGAGÉS', 0, 1, 'C', 0, '', 0, false, 'T', 'M');
initCss($pdf);
$pdf->Cell(0, 0, 'A retourner accompagné des justificatifs au plus tard le 10 du mois qui suit l’engagement des frais', 0, 1, 'C', 0, '', 0, false, 'T', 'M');

$pdf->Ln(6);

$moisStr=[
    '01' => 'Janvier',
    '02' => 'Février',
    '03' => 'Mars',
    '04' => 'Avril',
    '05' => 'Mai',
    '06' => 'Juin',
    '07' => 'Juillet',
    '08' => 'Août',
    '09' => 'Septembre',
    '10' => 'Octobre',
    '11' => 'Novembre',
    '12' => 'Décembre'
];
$partieAnnee = substr($mois, 0, 4);
$partieMois = substr($mois, 4);

// Tableau pour les informations du visiteur



// calcul du total
$total=0;
foreach ($lesFraisForfaits as $unFraiForfait){
    $total += round(((float)$unFraiForfait['quantite'] * (float)$unFraiForfait['montant']), 2);
}
foreach ($lesFraisHorsForfait as $unFraiHorsForfait){
    $total += (int)$unFraiHorsForfait['montant'];
}

ob_start();

include PATH_VIEWS . '/pdf/v_pdfView.php';

$html = ob_get_contents();

ob_end_clean();

$pdf->writeHTML($html, true, false, false, false, '');

// Ferme et génère le document PDF
ob_end_clean();
$pdf->Output('etat_de_frais.pdf', 'I');
?>