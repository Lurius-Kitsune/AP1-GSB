
<style>
    table {
        border-collapse: collapse;
        border: none;
    }

    td {
        border: none;
        border-right: 1px solid black;
        text-align: right;
    }

    th {
        border: none;
        border-right: 1px solid black;
        text-align: center;
    }

    .tableContainer {
        border-left: 0.5px solid black;
        border-right: 0.5px solid black;
        position: relative;
    }

    .gauche {
        text-align: left;
    }

    .fin {
        border-right: none;
    }

    .total {
        width: 35%;
        position: absolute;
        left: 300;
        border-collapse: collapse;
        border: none;
        border-left: 0.5px solid black;
        border-right: 0.5px solid black;
    }

    td {
        border: none;
        border-right: 1px solid black;
        text-align: right;
    }

    th {
        border: none;
        border-right: 1px solid black;
        text-align: center;
    }

    .gauche {
        text-align: left;
    }

    .fin {
        border-right: none;
    }

</style>

<table cellpadding="4">
    <tr>
        <td>Visiteur</td>
        <td><?= $idVisiteur; ?></td>
        <td><?= $identiteVisiteur[0]['prenom'] . " " . strtoupper($identiteVisiteur[0]['nom']) ?></td>
    </tr>
    <tr>
        <td>Mois</td>
        <td><?= $moisStr[$partieMois] . " " . $partieAnnee ?></td>
    </tr>
</table>



<div class="tableContainer"> 
    <table cellpadding="4">
        <tr>
            <th>Frais Forfaitaires</th>
            <th>Quantité</th>
            <th>Montant unitaire</th>
            <th class="fin">Total</th>
        </tr>
        <?php foreach ($lesFraisForfaits as $unFraiForfait) : ?>
            <tr>
                <td class="gauche"><?= $unFraiForfait['libelle']; ?></td>
                <td><?= $unFraiForfait['quantite']; ?></td>
                <td><?= $unFraiForfait['montant'] . '€'; ?></td>
                <td class="fin"><?= round(((float) $unFraiForfait['quantite'] * (float) $unFraiForfait['montant']), 2) . '€'; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <h2 style="text-align: center;">Autres frais</h2>
    <table cellpadding="4">
        <tr>
            <th>Date</th>
            <th>Libellé</th>
            <th class="fin">Montant</th>
        </tr>
        <?php foreach ($lesFraisHorsForfait as $unFraiHorsForfait) : ?>
            <tr>
                <td class="gauche"><?= $unFraiHorsForfait['date']; ?></td>
                <td class="gauche"><?= $unFraiHorsForfait['libelle']; ?></td>
                <td class="fin"><?= $unFraiHorsForfait['montant'] . '€'; ?></td>
            </tr>
        <?php endforeach; ?>
</div>

<table class="total">
    <tr>
        <td class="gauche">TOTAL <?= $partieMois . '/' . $partieAnnee; ?></td>
        <td class="fin"><?= $total . '€'; ?></td>
    </tr>
</table>

<span class="signature">Signature</span>