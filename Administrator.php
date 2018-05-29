<?php
    ob_start();
    session_start();
    error_reporting(0);
    ini_set('display_errors', 0);
?>
<?php
    $msgValuteError = '';
    $msgValuteConf = '';
    $msgDurataError = '';
    $msgDurataConf = '';
    $msgPragError = '';
    $msgPragConf = '';
    $msgMarjaError = '';
    $msgMarjaConf = '';
    $valute = $_POST['moneda'];
    $durata = $_POST['durata'];
    $baniiInitiali = $_POST['baniiInitiali'];
    $pragInferior = $_POST['pragPierdut'];
    $pragSuperior = $_POST['pragCastig'];
    $marjaMaxima = $_POST['marjaMaxima'];
    $marjaMinima = $_POST['marjaMinima'];
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if($_REQUEST["Aplica"] == "Valuta")
        {
            if (empty($valute))
            {
                $msgValuteError = "Nu ati ales nicio moneda!\n"; 
            }
            else
            {
                $username = 'tw';
                $password = 'TW';
                $connection_string = 'localhost/xe';
                $connection = oci_connect($username,$password,$connection_string);

                $preQuery = sprintf("update valute set folosit = 0 where nume_valuta != 'ron'");
                $res = oci_parse($connection,$preQuery);
                oci_execute($res);
                $n = count($valute);
                for ($i = 0; $i < $n; $i++)
                {
                    $query = sprintf("update valute set folosit = 1 where nume_valuta = '{$valute[$i]}'");
                    $result = oci_parse($connection,$query);
                    oci_execute($result);
                }
                $msgValuteConf = "Updated! \n";
                oci_close($connection);
            }
        }
        if ($_REQUEST["Aplica"] == "Durata" )
        {
            if($durata != null)
            {
                if ( $durata > 99 )
                {
                    $msgDurataError = "Durata prea mare";
                }
                else
                {
                    $username = 'tw';
                    $password = 'TW';
                    $connection_string = 'localhost/xe';
                    $connection = oci_connect($username,$password,$connection_string);

                    $preQuery = sprintf("update joc set durata_unei_valute = {$durata}");
                    $res = oci_parse($connection,$preQuery);
                    oci_execute($res);
                    $msgDurataConf = "Updated! \n";
                    oci_close($connection);
                }
            }
            else 
            {
                $msgDurataError = "Missing value! \n";
            }
        }
        if ($_REQUEST["Aplica"] == "Prag" )
        {
            if ($baniiInitiali != null && $pragInferior != null && $pragSuperior != null)
            {
                if ( $baniiInitiali < $pragInferior )
                {
                    $msgPragError = "Start = Lost";
                }
                else if ( $pragInferior > $pragSuperior )
                {
                    $msgPragError = "Sup < Inf";
                }
                else
                {
                    $username = 'tw';
                    $password = 'TW';
                    $connection_string = 'localhost/xe';
                    $connection = oci_connect($username,$password,$connection_string);

                    $preQuery = sprintf("update joc set banii_initiali = {$baniiInitiali}, prag_superior = {$pragSuperior}, prag_inferior = {$pragInferior}");
                    $res = oci_parse($connection,$preQuery);
                    oci_execute($res);
                    $msgPragConf = "Updated! \n";
                    oci_close($connection);
                }
            }
            else
            {
                $msgPragError = "Missing Value! \n";
            }
        }
        if ($_REQUEST["Aplica"] == "Marja" )
        {
            if ($marjaMaxima != null && $marjaMinima != null)
            {
                if ($marjaMaxima < $marjaMinima)
                {
                    $msgMarjaError = "Max < Min?";
                }
                else
                {
                    $username = 'tw';
                    $password = 'TW';
                    $connection_string = 'localhost/xe';
                    $connection = oci_connect($username,$password,$connection_string);

                    $preQuery = sprintf("update joc set marja_minima = {$marjaMinima}, marja_maxima = {$marjaMaxima}");
                    $res = oci_parse($connection,$preQuery);
                    oci_execute($res);
                    $msgMarjaConf = "Updated! \n";
                    oci_close($connection);
                }
            }
            else
            {
                $msgMarjaError = "Missing value! \n";
            }
        }
    }
?>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>My Website</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="style.css" />
</head>
<body>
    <header class="Administrator">
        <div>Administrator</div>
    </header>
    <form method="post">
        <div class = "divMare">
            <div class = "impartire">
                <fieldset class = "Administrator">
                <legend>Monezi</legend>
                <div style="font-size:small; color:red;"><?php echo $msgValuteError; ?></div>
                <div style="font-size:small; color:green;"><?php echo $msgValuteConf; ?></div>
                <p>Valute disponibile: </p>
                    <input type="checkbox" name="moneda[]" value="dolar"> Dolar
                    <input type="checkbox" name="moneda[]" value="euro" checked> Euro
                    <input type="checkbox" name="moneda[]" value="lira" > Lira
                    <input type="checkbox" name="moneda[]" value="yen" > Yen
                    <input type="submit" value="Valuta" name = "Aplica">
                </fieldset>
            </div>
            <div class = "impartire">
                <fieldset class = "Administrator">
                    <legend>Perioada</legend>
                    <div style="font-size:small; color:red;"><?php echo $msgDurataError; ?></div>
                    <div style="font-size:small; color:green;"><?php echo $msgDurataConf; ?></div>
                    <label for="txtDurataValuta">Durata unei valute:</label><input type="text" name = "durata" placeholder="Ex: 30">
                    <input type="submit" value="Durata" name = "Aplica">
                </fieldset>
            </div>
            <div class = "impartire">
                <fieldset class = "Administrator">
                    <legend>Bani</legend>
                    <div style="font-size:small; color:red;"><?php echo $msgPragError; ?></div>
                    <div style="font-size:small; color:green;"><?php echo $msgPragConf; ?></div>
                        <label for="txtBaniiInitiali"> Banii initiali a jucatorilor: </label><input type="text" name = "baniiInitiali" placeholder="Ex: 30"><br>
                        <label for="txtPragCastig">Prag pentru a castiga:</label><input type="text" name = "pragCastig" placeholder="Ex: 30"><br>
                        <label for="txtPragPierdut">Prag pentru a pierde:</label><input type="text" name = "pragPierdut" placeholder="Ex: 30">
                        <input type="submit" value="Prag" name = "Aplica">
                </fieldset>
            </div>
            <div class = "impartire">
                <fieldset class = "Administrator">
                    <legend>Marjele pentru valuta</legend>
                    <div style="font-size:small; color:red;"><?php echo $msgMarjaError; ?></div>
                    <div style="font-size:small; color:green;"><?php echo $msgMarjaConf; ?></div>
                        <label for="txtMarjaMax">Marja Minima: </label><input type="text" name = "marjaMinima" placeholder="Ex: 4.10"><br>
                        <label for="txtMarjaMin">Marja Maxima: </label><input type="text" name = "marjaMaxima" placeholder="Ex: 6.10">
                        <input type="submit" value="Marja" name = "Aplica">
                </fieldset>
            </div>
        </div>
    </form>
</body>
</html>