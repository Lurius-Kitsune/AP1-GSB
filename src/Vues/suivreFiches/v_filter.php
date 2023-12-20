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
        width: 310px;
        margin: 1%;
        padding: 10px;
        box-shadow: 2px 4px 13px 2px #666666;
        border-radius: 18px;
    }

</style>
<div id="filter-wrapper" class="container">
    <h4> Recherche : </h4>
    <form action="/?uc=suivreFiches" method="post">
        <input type="submit" name="action" value="10"></input>
        <input type="submit" name="action" value="20"></input>
        <input type="submit" name="action" value="30"></input>
        <input type="submit" name="action" value="50"></input>
    </form>
    <form  action="/?uc=suivreFiches" method="post">
        <input type="submit" name="prec" value="<"></input>
        <input type="submit" name="suiv" value=">"></input>
    </form>
</div>
