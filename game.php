<?php
    ob_start();
    session_start();
    //error_reporting(0);
    //ini_set('display_errors', 0);
?>
<?php
    $username = 'tw';
    $password = 'TW';
    $connection_string = 'localhost/xe';
    $connection = oci_connect($username,$password,$connection_string);

    $Query = sprintf("Select banii_initiali from joc");
    $res = oci_parse($connection,$preQuery);
    oci_execute($res);
    $resulting = oci_fetch_array($res,OCI_ASSOC+OCI_RETURN_NULLS );
    echo $resulting;
    $totalUserValue = 0;
    $minValue = 0;
    $minWinValue = 0;
    $RonUser = 0;
    $DollarUser = 0;
    $YenUser = 0;
    $EuroUser = 0;
    $LiraUser = 0;
    $msgSchimbErr = '';
    $msgSchimbAff = '';
    $msgUpdateErr = '';
    $msgUpdateAff = '';
    $msgFinishErr = '';
    $msfFinishAff = '';
?>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>My Website</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="style.css" />
    <script src = "script.js"></script>
</head>
<body>
    <header id="UserHeader">
        <div id = "imagini">
           <input type="image" src="html5.png" alt="Submit">
           <input type="image" src="json.png" alt="Submit">
           <input type="image" src="pdf.png" alt="Submit">
        </div>
        <div id="Informatii">
            <p>Valoare totala a user-ului este: <?php echo $totalUserValue ?> RON</p>
            <p>Pragul pentru a pierde este: <?php echo $minValue ?> RON</p>
            <p>Pragul pentru a castiga este: <?php echo $minWinValue ?> RON</p>
        </div>
        <a href="index.html"><input type="submit" value="Log Out"></a>
    </header>
    <fieldset id="Valute">
        <legend>Valuta</legend>
        <label for="txtInformatiiCerute">Moneda cautata: Euro</label><br>
        <label for="txtValutaEuro">Euro: 1</label><br>
        <label for="txtValutaDolar">Dolar: 1.12</label><br>
        <label for="txtValutaYen">Yen: 510</label><br>
        <label for="txtValutaLira">Lira: 0,89</label>
    </fieldset>
    <form method = "post">
        <div id="UserMenu">
            <fieldset id="InfoUser">
                <legend>Informatii jucator</legend>
                <label for="txtCantitateRon">Ron: <?php echo $RonUser ?></label><br>
                <label for="txtCantitateEuro">Euro: <?php echo $EuroUser ?></label><br>
                <label for="txtCantitateDolar">Dolar:  <?php echo $DollarUser ?></label><br>
                <label for="txtCantitateYen">Yen:  <?php echo $YenUser ?></label><br>
                <label for="txtCantitateLira">Lira:  <?php echo $LiraUser ?></label>
            </fieldset>
            <fieldset id="Schimb">
                <legend>Schimb</legend>
                <div style="font-size:small; color:red;"><?php echo $msgSchimbErr ?></div>
                <div style="font-size:small; color:green;"><?php echo $msgSchimbAff ?></div>
                <label>Cantitate:</label>
                <input type="text"><br>
                <label>Moneda din care doriti sa schimbati:</label>
                <select>
                    <option value="Euro">Euro</option>
                    <option value="Dolar">Dolar</option>
                    <option value="Yen">Yen</option>
                    <option value="Lira">Lira</option>
                </select><br>
                <label>Moneda in care doriti sa schimbati:</label>
                <select>
                    <option value="Euro">Euro</option>
                    <option value="Dolar">Dolar</option>
                    <option value="Yen">Yen</option>
                    <option value="Lira">Lira</option>
                </select><br>
                <input type="submit" value="Schimba">
            </fieldset>
            <fieldset id = "Update">
                <legend>Update</legend>
                <div style="font-size:small; color:red;"><?php echo $msgUpdateErr ?></div>
                <div style="font-size:small; color:green;"><?php echo $msgUpdateAff ?></div>
                <label for="txtMonedaCeruta">Selectati moneda pentru care vreti sa stiti valuta:</label>
                <select>
                    <option value="Euro">Euro</option>
                    <option value="Dolar">Dolar</option>
                    <option value="Yen">Yen</option>
                    <option value="Lira">Lira</option>
                </select><br>
                <div style="font-size:small; color:red;"><?php echo $msgUpdateErr ?></div>
                <div style="font-size:small; color:green;"><?php echo $msgUpdateAff ?></div>
                <input type="submit" value="Update">
            </fieldset>
            <input type="submit" value="Finish" id = "ButonFinal">
        </div>
    </form>
    <footer>
        Aici va fi lista RSS, ca la stiri.
    </footer>
</body>
</html>