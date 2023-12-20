<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->

<style>
    #filter-wrapper {
        position: fixed;
        bottom: auto;
        left: 0;
        width: 326px;
        margin: 1%;
        padding: 10px;
        box-shadow: 2px 4px 13px 2px #666666;
        border-radius: 18px;
    }
    
    .btn-group > .dropdown-menu{
        height: 200px
    }

</style>
<script>
   $(document).ready(function () {
      $('.selectpicker').selectpicker();
   });
</script>

<div id="filter-wrapper" class="container">
    <h4> Recherche : </h4>
    <form action="/" method="GET" class="form-horizontal">
        <input type="hidden" value="suivreFiches" name="uc">
        <div>
            <div class="form-group">
                <label for="monthInput" class="col-sm-3 control-label">Mois</label>
                <div class="col-sm-9">
                    <select class="form-control selectpicker" data-live-search="true" id="monthInput" name="month">
                        <option value="none" >Tous</option>
                        <?php
                        foreach ($lesMois as $mois) {
                            $concatYearsMonth = $mois["numAnnee"] . $mois["numMois"];
                            echo "<option value='" . $concatYearsMonth . "'" . ($selectedMonth == $concatYearsMonth ? 'selected' : '') . ">" . $mois["numMois"] . "/" . $mois["numAnnee"] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="visiteurInput" class="col-sm-3 control-label">Visiteur</label>
                <div class="col-sm-9">
                <select class="form-control selectpicker" data-live-search="true" id="visiteurInput" autocomplete="on" name="visiteurId">
                    <option value="none" >Tous</option>
                    <?php
                    foreach ($visiteurs as $visiteur) {
                        echo "<option value='" . $visiteur["id"] . "'" . ($selectedVisiteurId == $visiteur["id"] ? 'selected' : '') . ">" . $visiteur["nom"] . " " . $visiteur["prenom"] . "</option>";
                    }
                    ?>
                </select>
                </div>
            </div>
            <button type="submit" class="btn btn-warning ms-3 center">Rechercher</button>
        </div>
    </form>
</div>
