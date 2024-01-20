<?php

function initCss($pdf) : void{
    $pdf->SetFont('helvetica', '', 10);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFillColor(255, 255, 255);
}

$identiteVisiteur = $pdo->getNomVisiteur($_GET['idVisiteur']);
$lesFraisForfaits = $pdo->getLesFraisForfait($_GET['idVisiteur'], $_GET['mois']);
$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($_GET['idVisiteur'], $_GET['mois']);

// Crée une nouvelle instance PDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Définit les informations du document
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Votre Nom');
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

$pdf->Cell(0, 10, "Visiteur : ", 0, 1);

// Tableau pour les informations du visiteur
$html = '
<table border="1" cellpadding="4">
    <tr>
        <td>Matricule</td>
        <td>Nom</td>
    </tr>
    <tr>
        <td>' . $_GET["idVisiteur"] . '</td>
        <td>' . $identiteVisiteur[0]['prenom'] . " " . $identiteVisiteur[0]['nom'] . '</td>
    </tr>
</table>';

$pdf->writeHTML($html, true, false, false, false, '');

// Espacer avant le prochain tableau
$pdf->Ln(4);

// Tableau pour les frais forfaitaires
$html = '
<table border="1" cellpadding="4">
    <tr>
        <th>Frais Forfaitaires</th>
        <th>Quantité</th>
        <th>Montant unitaire</th>
        <th>Total</th>
    </tr>';

// affichage de chaque ligne des frais forfaits
foreach ($lesFraisForfaits as $unFraiForfait){
    $html.= '<tr>'
    . '        <td>' . $unFraiForfait['libelle'] . '</td>'
    . '        <td>' . $unFraiForfait['quantite'] . '</td>'
    . '        <td>' . $unFraiForfait['montant'] . '</td>'
    . '        <td>' . (int)$unFraiForfait['quantite'] * (int)$unFraiForfait['montant'] . '</td>'
    . '     </tr>';
}

// Fin et affichage du tableau
$html.='    
</table>
<h2 style="text-align: center;">Autres frais</h2>';
$pdf->writeHTML($html, true, false, false, false, '');

// Espacer avant le prochain tableau
$pdf->Ln(4);

// Tableau pour les autres frais
$html = '
<table border="1" cellpadding="4">
    <tr>
        <th>Date</th>
        <th>Libellé</th>
        <th>Montant</th>
    </tr>';

// affichage de chaque ligne des frais forfaits
foreach ($lesFraisHorsForfait as $unFraiHorsForfait){
    $html.= '<tr>'
    . '        <td>' . $unFraiHorsForfait['date'] . '</td>'
    . '        <td>' . $unFraiHorsForfait['libelle'] . '</td>'
    . '        <td>' . $unFraiHorsForfait['montant'] . '</td>'
    . '     </tr>';
}

// Fin et affichage du tableau
$html .='</table>';
$pdf->writeHTML($html, true, false, false, false, '');

// Ajoutez un espace pour la signature
$pdf->Ln(10);

// Ligne pour la signature
$pdf->Cell(0, 0, 'Signature', 0, 1, 'R', 0, '', 0, false, 'T', 'M');

// Ferme et génère le document PDF
ob_end_clean();
$pdf->Output('etat_de_frais.pdf', 'I');
?>