<?php
    ob_start();
    session_start();
    //error_reporting(0);
    //ini_set('display_errors', 0);
    if ( !isset($_SESSION['username']) )
    {
        header("Location: index.php",true,301);
        exit();
    }
    else
    {
        if ( !isset($_COOKIE["player"]) )
        {
            $cookie_name = "player";
            $cookie_value = $_SESSION['username'];
            setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
            // $username = 'TW';
            // $password = 'tw';
            // $connection_string = 'localhost/xe';
            // $connection = oci_connect($username,$password,$connection_string);

            // $Query = sprintf("Select banii_initiali from joc");
            // $res = oci_parse($connection,$Query);
            // oci_execute($res);
            // $resulting = oci_fetch_array($res,OCI_BOTH);
            // $totalUserValue = $resulting[0];
            // oci_close($connection);
            // setcookie("TotValue",$totalUserValue, time() + (3600), "/");
            // setcookie("Ron", $totalUserValue, time() + (3600), "/");
            // setcookie("Dolar", 0, time() + (3600), "/");
            // setcookie("Yen", 0, time() + (3600), "/");
            // setcookie("Euro", 0, time() + (3600), "/");
            // setcookie("Lira", 0, time() + (3600), "/");
        }
    }
 ?>
<?php
    $player = $_SESSION['username'];
    if ( !isset($_COOKIE["TotValue"]))
    {
        $username = 'TW';
        $password = 'tw';
        $connection_string = 'localhost/xe';
        $connection = oci_connect($username,$password,$connection_string);

        $Query = sprintf("Select banii_initiali from joc");
        $res = oci_parse($connection,$Query);
        oci_execute($res);
        $resulting = oci_fetch_array($res,OCI_BOTH);
        $totalUserValue = $resulting[0];
        oci_close($connection);
        setcookie("TotValue",$totalUserValue, time() + (3600), "/");
        setcookie("Ron", $totalUserValue, time() + (3600), "/");
        setcookie("Dolar", 0, time() + (3600), "/");
        setcookie("Yen", 0, time() + (3600), "/");
        setcookie("Euro", 0, time() + (3600), "/");
        setcookie("Lira", 0, time() + (3600), "/");
        $Query = sprintf("Select prag_superior,prag_inferior,marja_minima,marja_maxima from joc");
        $res = oci_parse($connection,$Query);
        oci_execute($res);
        $resulting = oci_fetch_array($res,OCI_BOTH);
        $minValue = $resulting[1];
        $minWinValue = $resulting[0];
        $minMarja = $resulting[2];
        $maxMarja = $resulting[3];

        oci_close($connection);
        $DolarExchange = array("Yen"=> rand($minMarja,$maxMarja), "Euro" => rand($minMarja,$maxMarja), "Lira" => rand($minMarja,$maxMarja),"Ron" => rand($minMarja,$maxMarja));
        $yenExchange = array("Dolar" => rand($minMarja,$maxMarja), "Euro" => rand($minMarja,$maxMarja), "Lira" => rand($minMarja,$maxMarja),"Ron" => rand($minMarja,$maxMarja));
        $euroExchange = array("Dolar" => rand($minMarja,$maxMarja), "Yen" => rand($minMarja,$maxMarja), "Lira" => rand($minMarja,$maxMarja), "Ron" => rand($minMarja,$maxMarja));
        $liraExchange = array("Dolar" => rand($minMarja,$maxMarja), "Yen" => rand($minMarja,$maxMarja), "Euro" => rand($minMarja,$maxMarja), "Ron" => rand($minMarja,$maxMarja));
        $ronExchange = array("Dolar" => rand($minMarja,$maxMarja), "Yen" => rand($minMarja,$maxMarja), "Euro" => rand($minMarja,$maxMarja), "Lira" => rand($minMarja,$maxMarja));
        $_SESSION['DolarExchange'] = $DolarExchange;
        $_SESSION['YenExchange'] = $yenExchange;
        $_SESSION['EuroExchange'] = $euroExchange;
        $_SESSION['LiraExchange'] = $liraExchange;
        $_SESSION['RonExchange'] = $ronExchange;
    }

    $username = 'TW';
    $password = 'tw';
    $connection_string = 'localhost/xe';
    $connection = oci_connect($username,$password,$connection_string);
    
    $Query = sprintf("Select prag_superior,prag_inferior,marja_minima,marja_maxima from joc");
    $res = oci_parse($connection,$Query);
    oci_execute($res);
    $resulting = oci_fetch_array($res,OCI_BOTH);
    $minValue = $resulting[1];
    $minWinValue = $resulting[0];
    $minMarja = $resulting[2];
    $maxMarja = $resulting[3];

    oci_close($connection);

    $cookie_name = "player";
    $cookie_value = $_SESSION['username'];
    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
    $totalUserValue = $_COOKIE["TotValue"];
    $RonUser = $_COOKIE["Ron"];
    $DolarUser = $_COOKIE["Dolar"];
    $YenUser = $_COOKIE["Yen"];
    $LiraUser = $_COOKIE["Lira"];
    $EuroUser = $_COOKIE["Euro"];
    $DolarExchange = $_SESSION["DolarExchange"];
    $yenExchange = $_SESSION["YenExchange"];
    $euroExchange = $_SESSION["EuroExchange"];
    $liraExchange = $_SESSION["LiraExchange"];
    $ronExchange =$_SESSION["RonExchange"];
    $msgSchimbErr = '';
    $msgSchimbAff = '';
    $msgUpdateErr = '';
    $msgUpdateAff = '';
    $msgFinishErr = '';
    $msgFinishAff = '';
    $valuteList = array("Dolar" => $DolarExchange,"Yen" => $yenExchange,"Euro" => $euroExchange,"Lira" => $liraExchange,"Ron" => $ronExchange);
    function schimbPosibil($n,$originalMoney,$ron,$dolar,$yen,$lira,$euro)
    {
        switch($originalMoney)
        {
            case "Dolar":
                if ($n > $dolar)
                {
                    return false;
                }
                break;
            case "Ron":
                if ($n > $ron)
                {
                    return false;
                }
                break;
            case "Yen":
                if ($n > $yen)
                {
                    return false;
                }
                break;
            case "Lira":
                if ($n > $lira)
                {
                    return false;
                }
                break;
            case "Euro":
                if ($n > $euro)
                {
                    return false;
                }
                break;
        }
        return true;
    }
    $quantityToExchange = $_POST['quantity'];
    $originalMoney = $_POST['moneyToChange'];
    $newMoney = $_POST['moneyToGet'];
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if ( $_REQUEST["Aplica"] == "Schimba" )
        {
            if ( $quantityToExchange != null )
            {
                if ( $originalMoney == $newMoney )
                {
                    $msgSchimbErr = "Schimb Nefolositor! \n";
                }
                else
                {
                    if ( schimbPosibil($quantityToExchange,$originalMoney,$RonUser,$DolarUser,$YenUser,$LiraUser,$EuroUser))
                    {
                        switch($originalMoney)
                        {
                            case "Dolar":
                                $DolarUser = $DolarUser - $quantityToExchange;
                                setcookie("Dolar", $DolarUser, time() + (3600), "/");
                                setcookie("Ron", $RonUser, time() + (3600), "/");
                                setcookie("Yen", $YenUser, time() + (3600), "/");
                                setcookie("Euro", $EuroUser, time() + (3600), "/");
                                setcookie("Lira", $LiraUser, time() + (3600), "/");
                                break;
                            case "Yen":
                                $YenUser = $YenUser - $quantityToExchange;
                                setcookie("Dolar", $DolarUser, time() + (3600), "/");
                                setcookie("Ron", $RonUser, time() + (3600), "/");
                                setcookie("Yen", $YenUser, time() + (3600), "/");
                                setcookie("Euro", $EuroUser, time() + (3600), "/");
                                setcookie("Lira", $LiraUser, time() + (3600), "/");
                                break;
                            case "Euro":
                                $EuroUser = $EuroUser - $quantityToExchange;
                                setcookie("Dolar", $DolarUser, time() + (3600), "/");
                                setcookie("Ron", $RonUser, time() + (3600), "/");
                                setcookie("Yen", $YenUser, time() + (3600), "/");
                                setcookie("Euro", $EuroUser, time() + (3600), "/");
                                setcookie("Lira", $LiraUser, time() + (3600), "/");
                                break;
                            case "Lira":
                                $LiraUser = $LiraUser - $quantityToExchange;
                                setcookie("Dolar", $DolarUser, time() + (3600), "/");
                                setcookie("Ron", $RonUser, time() + (3600), "/");
                                setcookie("Yen", $YenUser, time() + (3600), "/");
                                setcookie("Euro", $EuroUser, time() + (3600), "/");
                                setcookie("Lira", $LiraUser, time() + (3600), "/");
                                break;
                            case "Ron":
                                $RonUser = $RonUser - $quantityToExchange;
                                setcookie("Dolar", $DolarUser, time() + (3600), "/");
                                setcookie("Ron", $RonUser, time() + (3600), "/");
                                setcookie("Yen", $YenUser, time() + (3600), "/");
                                setcookie("Euro", $EuroUser, time() + (3600), "/");
                                setcookie("Lira", $LiraUser, time() + (3600), "/");
                                break;
                        }
                        switch ( $newMoney )
                        {
                            case "Dolar":
                                $DolarUser = $DolarUser + $valuteList[$newMoney][$originalMoney]*$quantityToExchange;
                                setcookie("Dolar", $DolarUser, time() + (3600), "/");
                                setcookie("Ron", $RonUser, time() + (3600), "/");
                                setcookie("Yen", $YenUser, time() + (3600), "/");
                                setcookie("Euro", $EuroUser, time() + (3600), "/");
                                setcookie("Lira", $LiraUser, time() + (3600), "/");
                                $msgSchimbAff = "Efectuat! \n";
                                break;
                            case "Yen":
                                $YenUser = $YenUser + $valuteList[$newMoney][$originalMoney]*$quantityToExchange;
                                setcookie("Dolar", $DolarUser, time() + (3600), "/");
                                setcookie("Ron", $RonUser, time() + (3600), "/");
                                setcookie("Yen", $YenUser, time() + (3600), "/");
                                setcookie("Euro", $EuroUser, time() + (3600), "/");
                                setcookie("Lira", $LiraUser, time() + (3600), "/");
                                $msgSchimbAff = "Efectuat! \n";
                                break;
                            case "Euro":
                                $EuroUser = $EuroUser + $valuteList[$newMoney][$originalMoney]*$quantityToExchange;
                                setcookie("Dolar", $DolarUser, time() + (3600), "/");
                                setcookie("Ron", $RonUser, time() + (3600), "/");
                                setcookie("Yen", $YenUser, time() + (3600), "/");
                                setcookie("Euro", $EuroUser, time() + (3600), "/");
                                setcookie("Lira", $LiraUser, time() + (3600), "/");
                                $msgSchimbAff = "Efectuat! \n";
                                break;
                            case "Lira":
                                $LiraUser = $LiraUser + $valuteList[$newMoney][$originalMoney]*$quantityToExchange;
                                setcookie("Dolar", $DolarUser, time() + (3600), "/");
                                setcookie("Ron", $RonUser, time() + (3600), "/");
                                setcookie("Yen", $YenUser, time() + (3600), "/");
                                setcookie("Euro", $EuroUser, time() + (3600), "/");
                                setcookie("Lira", $LiraUser, time() + (3600), "/");
                                $msgSchimbAff = "Efectuat! \n";
                                break;
                            case "Ron":
                                $RonUser = $RonUser + $valuteList[$newMoney][$originalMoney]*$quantityToExchange;
                                setcookie("Dolar", $DolarUser, time() + (3600), "/");
                                setcookie("Ron", $RonUser, time() + (3600), "/");
                                setcookie("Yen", $YenUser, time() + (3600), "/");
                                setcookie("Euro", $EuroUser, time() + (3600), "/");
                                setcookie("Lira", $LiraUser, time() + (3600), "/");
                                $msgSchimbAff = "Efectuat! \n";
                                break;
                        }
                        $totalUserValue = $totalUserValue + $RonUser + $valuteList["Ron"]["Dolar"]*$DolarUser + $valuteList["Ron"]["Euro"]*$EuroUser + $valuteList["Ron"]["Lira"]*$LiraUser + $valuteList["Ron"]["Yen"]*$YenUser;
                        setcookie("TotValue",$totalUserValue, time() + (3600), "/");
                        if ( $totalUserValue < $minValue )
                        {
                            $message = "You Lost!";
                            echo "<script type='text/javascript'>alert('$message');</script>";
                            if (isset($_COOKIE['Dolar'])) {
                                unset($_COOKIE['Dolar']);
                                setcookie('Dolar', '', time() - 3600, '/'); // empty value and old timestamp
                            }
                            if (isset($_COOKIE['Ron'])) {
                                unset($_COOKIE['Ron']);
                                setcookie('Ron', '', time() - 3600, '/'); // empty value and old timestamp
                            }
                            if (isset($_COOKIE['Lira'])) {
                                unset($_COOKIE['Lira']);
                                setcookie('Lira', '', time() - 3600, '/'); // empty value and old timestamp
                            }
                            if (isset($_COOKIE['Euro'])) {
                                unset($_COOKIE['Euro']);
                                setcookie('Euro', '', time() - 3600, '/'); // empty value and old timestamp
                            }
                            if (isset($_COOKIE['player'])) {
                                unset($_COOKIE['player']);
                                setcookie('player', '', time() - 3600, '/'); // empty value and old timestamp
                            }
                            if (isset($_COOKIE['Yen'])) {
                                unset($_COOKIE['Yen']);
                                setcookie('Yen', '', time() - 3600, '/'); // empty value and old timestamp
                            }
                            if (isset($_COOKIE['TotValue'])) {
                                unset($_COOKIE['TotValue']);
                                setcookie('TotValue', '', time() - 3600, '/'); // empty value and old timestamp
                            }
                            session_destroy();
                            header("Location: index.php",true,301);
                            exit();
                        }
                    }
                    else
                    {
                        $msgSchimbErr = "Cantitate prea mare! \n";
                    }
                }
            }
            else
            {
                $msgSchimbErr = "Lipsa cantitate!\n";
            }
        }
        if ( $_REQUEST["Aplica"] == "Update" )
        {
        }
        if ( $_REQUEST["Aplica"] == "Log Out" )
        {
            // if (isset($_COOKIE['Dolar'])) {
            //     unset($_COOKIE['Dolar']);
            //     setcookie('Dolar', '', time() - 3600, '/'); // empty value and old timestamp
            // }
            // if (isset($_COOKIE['Ron'])) {
            //     unset($_COOKIE['Ron']);
            //     setcookie('Ron', '', time() - 3600, '/'); // empty value and old timestamp
            // }
            // if (isset($_COOKIE['Lira'])) {
            //     unset($_COOKIE['Lira']);
            //     setcookie('Lira', '', time() - 3600, '/'); // empty value and old timestamp
            // }
            // if (isset($_COOKIE['Euro'])) {
            //     unset($_COOKIE['Euro']);
            //     setcookie('Euro', '', time() - 3600, '/'); // empty value and old timestamp
            // }
            // if (isset($_COOKIE['player'])) {
            //     unset($_COOKIE['player']);
            //     setcookie('player', '', time() - 3600, '/'); // empty value and old timestamp
            // }
            // if (isset($_COOKIE['Yen'])) {
            //     unset($_COOKIE['Yen']);
            //     setcookie('Yen', '', time() - 3600, '/'); // empty value and old timestamp
            // }
            // if (isset($_COOKIE['TotValue'])) {
            //     unset($_COOKIE['TotValue']);
            //     setcookie('TotValue', '', time() - 3600, '/'); // empty value and old timestamp
            // }
            session_destroy();
            header("Location: index.php",true,301);
            exit();
        }
        if ( $_REQUEST["Aplica"] == "Finish" )
        {
            if ( $totalUserValue > $minWinValue )
            {
                $message = "You Won!";
                echo "<script type='text/javascript'>alert('$message');</script>";
                $username = 'TW';
                $password = 'tw';
                $connection_string = 'localhost/xe';
                $connection = oci_connect($username,$password,$connection_string);
                
                $Query = sprintf("insert into scor(user_name,highscore,data_scor) values('{$player}',{$totalUserValue},sysdate)");
                $res = oci_parse($connection,$Query);
                oci_execute($res);

                oci_close($connection);
            }
            else 
            {
                $msgFinishErr = "Inca nu aveti destule puncte pentru a castiga.";
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
        <form method = "post">
        <a href="index.html"><input type="submit" value="Log Out" name ="Aplica"></a>
        </form>
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
                <label for="txtCantitateDolar">Dolar:  <?php echo $DolarUser ?></label><br>
                <label for="txtCantitateYen">Yen:  <?php echo $YenUser ?></label><br>
                <label for="txtCantitateLira">Lira:  <?php echo $LiraUser ?></label>
            </fieldset>
            <fieldset id="Schimb">
                <legend>Schimb</legend>
                <div style="font-size:small; color:red;"><?php echo $msgSchimbErr ?></div>
                <div style="font-size:small; color:green;"><?php echo $msgSchimbAff ?></div>
                <label>Cantitate:</label>
                <input type="text" name = "quantity"><br>
                <label>Moneda din care doriti sa schimbati:</label>
                <select name = "moneyToChange">
                    <option value="Euro">Euro</option>
                    <option value="Ron">Ron</option>
                    <option value="Dolar">Dolar</option>
                    <option value="Yen">Yen</option>
                    <option value="Lira">Lira</option>
                </select><br>
                <label>Moneda in care doriti sa schimbati:</label>
                <select name = "moneyToGet">
                    <option value="Euro">Euro</option>
                    <option value="Ron">Ron</option>
                    <option value="Dolar">Dolar</option>
                    <option value="Yen">Yen</option>
                    <option value="Lira">Lira</option>
                </select><br>
                <input type="submit" value="Schimba" name = "Aplica">
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
                <input type="submit" value="Update">
            </fieldset>
            <input type="submit" value="Finish" id = "ButonFinal" name = "Aplica">
        </div>
    </form>
    <!-- <footer>
        Aici va fi lista RSS, ca la stiri.
    </footer> -->
</body>
</html>