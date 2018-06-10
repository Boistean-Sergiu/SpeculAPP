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
            $username = 'tw';
            $password = 'TW';
            $connection_string = 'localhost/xe';
            $connection = oci_connect($username,$password,$connection_string);

            $Query = sprintf("Select banii_initiali from joc");
            $res = oci_parse($connection,$Query);
            oci_execute($res);
            $resulting = oci_fetch_array($res,OCI_BOTH);
            $totalUserValue = $resulting[0];
            setcookie("TotValue",$totalUserValue, time() + (3600), "/");
            setcookie("Ron", $totalUserValue, time() + (3600), "/");
            setcookie("Dolar", 0, time() + (3600), "/");
            setcookie("Yen", 0, time() + (3600), "/");
            setcookie("Euro", 0, time() + (3600), "/");
            setcookie("Lira", 0, time() + (3600), "/");
            $Query = sprintf("Select prag_superior,prag_inferior,marja_minima,marja_maxima,durata_unei_valute from joc");
            $res = oci_parse($connection,$Query);
            oci_execute($res);
            $resulting = oci_fetch_array($res,OCI_BOTH);
            $_SESSION['minValue'] = $resulting[1];
            $_SESSION['minWinValue'] = $resulting[0];
            $_SESSION['minMarja'] = $resulting[2];
            $_SESSION['maxMarja'] = $resulting[3];
            $_SESSION['valueDurationS'] = $resulting[4];
            $_SESSION['originTime'] = microtime(true);
            $minMarja = $_SESSION['minMarja'];
            $maxMarja = $_SESSION['maxMarja']; 
            $ronExchange = array("Dolar" => frand($minMarja,$maxMarja), "Yen" => frand($minMarja,$maxMarja), "Euro" => frand($minMarja,$maxMarja), "Lira" => frand($minMarja,$maxMarja), "Ron"=> 1);
            $DolarExchange = array("Dolar" => 1,"Yen"=> round($ronExchange['Yen'] / $ronExchange['Dolar'], 2) , "Euro" => round($ronExchange['Euro'] / $ronExchange['Dolar'],2), "Lira" => round($ronExchange['Lira'] / $ronExchange['Dolar'],2),"Ron" => round($ronExchange['Ron'] / $ronExchange['Dolar'],2));
            $yenExchange = array("Dolar" => round($ronExchange['Dolar'] / $ronExchange['Yen'],2),"Yen"=> 1 , "Euro" => round($ronExchange['Euro'] / $ronExchange['Yen'],2), "Lira" => round($ronExchange['Lira'] / $ronExchange['Yen'],2),"Ron" => round($ronExchange['Ron'] / $ronExchange['Yen'],2));
            $euroExchange = array("Dolar" => round($ronExchange['Dolar'] / $ronExchange['Euro'],2), "Yen" => round($ronExchange['Yen'] / $ronExchange['Euro'],2), "Euro" => 1, "Lira" => round($ronExchange['Lira'] / $ronExchange['Euro'],2), "Ron" => round($ronExchange['Ron'] / $ronExchange['Euro'],2));
            $liraExchange = array("Dolar" => round($ronExchange['Dolar'] / $ronExchange['Lira'],2), "Yen" => round($ronExchange['Yen'] / $ronExchange['Lira'],2), "Euro" => round($ronExchange['Euro'] / $ronExchange['Lira'],2),"Lira" => 1, "Ron" => round($ronExchange['Ron'] / $ronExchange['Lira'],2));
            $_SESSION['DolarExchange'] = $DolarExchange;
            $_SESSION['YenExchange'] = $yenExchange;
            $_SESSION['EuroExchange'] = $euroExchange;
            $_SESSION['LiraExchange'] = $liraExchange;
            $_SESSION['RonExchange'] = $ronExchange;
            oci_close($connection);
            $totalUserValue = $totalUserValue;
            $RonUser = $totalUserValue;
            $DolarUser = 0;
            $YenUser = 0;
            $LiraUser = 0;
            $EuroUser = 0;
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
            $_SESSION['updateValute'] = '/';
            $_SESSION['updateRon']  ='/';
            $_SESSION['updateEuro'] = '/';
            $_SESSION['updateDolar'] = '/';
            $_SESSION['updateYen'] = '/';
            $_SESSION['updateLira'] = '/';
            $valuteList = array("Dolar" => $DolarExchange,"Yen" => $yenExchange,"Euro" => $euroExchange,"Lira" => $liraExchange,"Ron" => $ronExchange);        
        }
        else
        {
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
        }
    }
    function frand($min, $max) {
      $scale = pow(10, 2);
      return mt_rand($min * $scale, $max * $scale) / $scale;
    }
            
    function timeCheck(){
        $now = microtime(true);
        if ( $now - $_SESSION['originTime'] > $_SESSION['valueDurationS'] )
        {
            return false;
        }
        return true;
    }
    $player = $_SESSION['username'];
    $cookie_name = "player";
    $cookie_value = $_SESSION['username'];
    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
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
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if ( $_REQUEST["Aplica"] == "Schimba" )
        {
            $quantityToExchange = $_POST['quantity'];
            $originalMoney = $_POST['moneyToChange'];
            $newMoney = $_POST['moneyToGet'];
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
                                $DolarUser = round($DolarUser - $quantityToExchange,2);
                                setcookie("Dolar", $DolarUser, time() + (3600), "/");
                                setcookie("Ron", $RonUser, time() + (3600), "/");
                                setcookie("Yen", $YenUser, time() + (3600), "/");
                                setcookie("Euro", $EuroUser, time() + (3600), "/");
                                setcookie("Lira", $LiraUser, time() + (3600), "/");
                                break;
                            case "Yen":
                                $YenUser = round($YenUser - $quantityToExchange,2);
                                setcookie("Dolar", $DolarUser, time() + (3600), "/");
                                setcookie("Ron", $RonUser, time() + (3600), "/");
                                setcookie("Yen", $YenUser, time() + (3600), "/");
                                setcookie("Euro", $EuroUser, time() + (3600), "/");
                                setcookie("Lira", $LiraUser, time() + (3600), "/");
                                break;
                            case "Euro":
                                $EuroUser = round($EuroUser - $quantityToExchange,2);
                                setcookie("Dolar", $DolarUser, time() + (3600), "/");
                                setcookie("Ron", $RonUser, time() + (3600), "/");
                                setcookie("Yen", $YenUser, time() + (3600), "/");
                                setcookie("Euro", $EuroUser, time() + (3600), "/");
                                setcookie("Lira", $LiraUser, time() + (3600), "/");
                                break;
                            case "Lira":
                                $LiraUser = round($LiraUser - $quantityToExchange,2);
                                setcookie("Dolar", $DolarUser, time() + (3600), "/");
                                setcookie("Ron", $RonUser, time() + (3600), "/");
                                setcookie("Yen", $YenUser, time() + (3600), "/");
                                setcookie("Euro", $EuroUser, time() + (3600), "/");
                                setcookie("Lira", $LiraUser, time() + (3600), "/");
                                break;
                            case "Ron":
                                $RonUser = round($RonUser - $quantityToExchange,2);
                                setcookie("Dolar", $DolarUser, time() + (3600), "/");
                                setcookie("Ron", $RonUser, time() + (3600), "/");
                                setcookie("Yen", $YenUser, time() + (3600), "/");
                                setcookie("Euro", $EuroUser, time() + (3600), "/");
                                setcookie("Lira", $LiraUser, time() + (3600), "/");
                                break;
                        }
                        if ( timeCheck() == false ) {
                            $_SESSION['originTime'] = microtime(true);
                            $minMarja = $_SESSION['minMarja'];
                            $maxMarja = $_SESSION['maxMarja']; 
                            // $DolarExchange = array("Dolar" => 1,"Yen"=> frand($minMarja,$maxMarja), "Euro" => frand($minMarja,$maxMarja), "Lira" => frand($minMarja,$maxMarja),"Ron" => frand($minMarja,$maxMarja));
                            // $yenExchange = array("Dolar" => frand($minMarja,$maxMarja),"Yen"=> 1 , "Euro" => frand($minMarja,$maxMarja), "Lira" => frand($minMarja,$maxMarja),"Ron" => frand($minMarja,$maxMarja));
                            // $euroExchange = array("Dolar" => frand($minMarja,$maxMarja), "Yen" => frand($minMarja,$maxMarja), "Euro" => 1, "Lira" => frand($minMarja,$maxMarja), "Ron" => frand($minMarja,$maxMarja));
                            // $liraExchange = array("Dolar" => frand($minMarja,$maxMarja), "Yen" => frand($minMarja,$maxMarja), "Euro" => frand($minMarja,$maxMarja),"Lira" => 1, "Ron" => frand($minMarja,$maxMarja));
                            // $ronExchange = array("Dolar" => frand($minMarja,$maxMarja), "Yen" => frand($minMarja,$maxMarja), "Euro" => frand($minMarja,$maxMarja), "Lira" => frand($minMarja,$maxMarja), "Ron"=> 1);
                            $ronExchange = array("Dolar" => frand($minMarja,$maxMarja), "Yen" => frand($minMarja,$maxMarja), "Euro" => frand($minMarja,$maxMarja), "Lira" => frand($minMarja,$maxMarja), "Ron"=> 1);
                            $DolarExchange = array("Dolar" => 1,"Yen"=> round($ronExchange['Yen'] / $ronExchange['Dolar'], 2) , "Euro" => round($ronExchange['Euro'] / $ronExchange['Dolar'],2), "Lira" => round($ronExchange['Lira'] / $ronExchange['Dolar'],2),"Ron" => round($ronExchange['Ron'] / $ronExchange['Dolar'],2));
                            $yenExchange = array("Dolar" => round($ronExchange['Dolar'] / $ronExchange['Yen'],2),"Yen"=> 1 , "Euro" => round($ronExchange['Euro'] / $ronExchange['Yen'],2), "Lira" => round($ronExchange['Lira'] / $ronExchange['Yen'],2),"Ron" => round($ronExchange['Ron'] / $ronExchange['Yen'],2));
                            $euroExchange = array("Dolar" => round($ronExchange['Dolar'] / $ronExchange['Euro'],2), "Yen" => round($ronExchange['Yen'] / $ronExchange['Euro'],2), "Euro" => 1, "Lira" => round($ronExchange['Lira'] / $ronExchange['Euro'],2), "Ron" => round($ronExchange['Ron'] / $ronExchange['Euro'],2));
                            $liraExchange = array("Dolar" => round($ronExchange['Dolar'] / $ronExchange['Lira'],2), "Yen" => round($ronExchange['Yen'] / $ronExchange['Lira'],2), "Euro" => round($ronExchange['Euro'] / $ronExchange['Lira'],2),"Lira" => 1, "Ron" => round($ronExchange['Ron'] / $ronExchange['Lira'],2));
                            $_SESSION['DolarExchange'] = $DolarExchange;
                            $_SESSION['YenExchange'] = $yenExchange;
                            $_SESSION['EuroExchange'] = $euroExchange;
                            $_SESSION['LiraExchange'] = $liraExchange;
                            $_SESSION['RonExchange'] = $ronExchange;
                            $valuteList = array("Dolar" => $DolarExchange,"Yen" => $yenExchange,"Euro" => $euroExchange,"Lira" => $liraExchange,"Ron" => $ronExchange);        
                        }
                        switch ( $newMoney )
                        {
                            case "Dolar":
                                $DolarUser = round($DolarUser + $valuteList[$originalMoney][$newMoney]*$quantityToExchange,2);
                                setcookie("Dolar", $DolarUser, time() + (3600), "/");
                                setcookie("Ron", $RonUser, time() + (3600), "/");
                                setcookie("Yen", $YenUser, time() + (3600), "/");
                                setcookie("Euro", $EuroUser, time() + (3600), "/");
                                setcookie("Lira", $LiraUser, time() + (3600), "/");
                                $msgSchimbAff = "Efectuat! \n";
                                break;
                            case "Yen":
                                $YenUser = round($YenUser + $valuteList[$originalMoney][$newMoney]*$quantityToExchange,2);
                                setcookie("Dolar", $DolarUser, time() + (3600), "/");
                                setcookie("Ron", $RonUser, time() + (3600), "/");
                                setcookie("Yen", $YenUser, time() + (3600), "/");
                                setcookie("Euro", $EuroUser, time() + (3600), "/");
                                setcookie("Lira", $LiraUser, time() + (3600), "/");
                                $msgSchimbAff = "Efectuat! \n";
                                break;
                            case "Euro":
                                $EuroUser = round($EuroUser + $valuteList[$originalMoney][$newMoney]*$quantityToExchange,2);
                                setcookie("Dolar", $DolarUser, time() + (3600), "/");
                                setcookie("Ron", $RonUser, time() + (3600), "/");
                                setcookie("Yen", $YenUser, time() + (3600), "/");
                                setcookie("Euro", $EuroUser, time() + (3600), "/");
                                setcookie("Lira", $LiraUser, time() + (3600), "/");
                                $msgSchimbAff = "Efectuat! \n";
                                break;
                            case "Lira":
                                $LiraUser = round($LiraUser + $valuteList[$originalMoney][$newMoney]*$quantityToExchange,2);
                                setcookie("Dolar", $DolarUser, time() + (3600), "/");
                                setcookie("Ron", $RonUser, time() + (3600), "/");
                                setcookie("Yen", $YenUser, time() + (3600), "/");
                                setcookie("Euro", $EuroUser, time() + (3600), "/");
                                setcookie("Lira", $LiraUser, time() + (3600), "/");
                                $msgSchimbAff = "Efectuat! \n";
                                break;
                            case "Ron":
                                $RonUser = round($RonUser + $valuteList[$originalMoney][$newMoney]*$quantityToExchange,2);
                                setcookie("Dolar", $DolarUser, time() + (3600), "/");
                                setcookie("Ron", $RonUser, time() + (3600), "/");
                                setcookie("Yen", $YenUser, time() + (3600), "/");
                                setcookie("Euro", $EuroUser, time() + (3600), "/");
                                setcookie("Lira", $LiraUser, time() + (3600), "/");
                                $msgSchimbAff = "Efectuat! \n";
                                break;
                        }
                        $totalUserValue = round($RonUser + $valuteList["Dolar"]["Ron"]*$DolarUser + $valuteList["Euro"]["Ron"]*$EuroUser + $valuteList["Lira"]["Ron"]*$LiraUser + $valuteList["Yen"]["Ron"]*$YenUser,2);
                        setcookie("TotValue",$totalUserValue, time() + (3600), "/");
                        if ( $totalUserValue < $_SESSION['minValue'] )
                        {
                            echo '<script type="text/javascript">alert("You Lost!");</script>';
                            if (isset($_COOKIE['player'])){
                                unset($_COOKIE['player']);
                                setcookie('player', '', time() - 3600, '/'); // empty value and old timestamp
                            }
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
        if ( $_REQUEST['Aplica'] == "Update" )
        {
            if ( timeCheck() == false ) {
                $_SESSION['originTime'] = microtime(true);
                $minMarja = $_SESSION['minMarja'];
                $maxMarja = $_SESSION['maxMarja']; 
                // $DolarExchange = array("Dolar" => 1,"Yen"=> frand($minMarja,$maxMarja), "Euro" => frand($minMarja,$maxMarja), "Lira" => frand($minMarja,$maxMarja),"Ron" => frand($minMarja,$maxMarja));
                // $yenExchange = array("Dolar" => frand($minMarja,$maxMarja),"Yen"=> 1 , "Euro" => frand($minMarja,$maxMarja), "Lira" => frand($minMarja,$maxMarja),"Ron" => frand($minMarja,$maxMarja));
                // $euroExchange = array("Dolar" => frand($minMarja,$maxMarja), "Yen" => frand($minMarja,$maxMarja), "Euro" => 1, "Lira" => frand($minMarja,$maxMarja), "Ron" => frand($minMarja,$maxMarja));
                // $liraExchange = array("Dolar" => frand($minMarja,$maxMarja), "Yen" => frand($minMarja,$maxMarja), "Euro" => frand($minMarja,$maxMarja),"Lira" => 1, "Ron" => frand($minMarja,$maxMarja));
                // $ronExchange = array("Dolar" => frand($minMarja,$maxMarja), "Yen" => frand($minMarja,$maxMarja), "Euro" => frand($minMarja,$maxMarja), "Lira" => frand($minMarja,$maxMarja), "Ron"=> 1);
                $ronExchange = array("Dolar" => frand($minMarja,$maxMarja), "Yen" => frand($minMarja,$maxMarja), "Euro" => frand($minMarja,$maxMarja), "Lira" => frand($minMarja,$maxMarja), "Ron"=> 1);
                $DolarExchange = array("Dolar" => 1,"Yen"=> round($ronExchange['Yen'] / $ronExchange['Dolar'], 2) , "Euro" => round($ronExchange['Euro'] / $ronExchange['Dolar'],2), "Lira" => round($ronExchange['Lira'] / $ronExchange['Dolar'],2),"Ron" => round($ronExchange['Ron'] / $ronExchange['Dolar'],2));
                $yenExchange = array("Dolar" => round($ronExchange['Dolar'] / $ronExchange['Yen'],2),"Yen"=> 1 , "Euro" => round($ronExchange['Euro'] / $ronExchange['Yen'],2), "Lira" => round($ronExchange['Lira'] / $ronExchange['Yen'],2),"Ron" => round($ronExchange['Ron'] / $ronExchange['Yen'],2));
                $euroExchange = array("Dolar" => round($ronExchange['Dolar'] / $ronExchange['Euro'],2), "Yen" => round($ronExchange['Yen'] / $ronExchange['Euro'],2), "Euro" => 1, "Lira" => round($ronExchange['Lira'] / $ronExchange['Euro'],2), "Ron" => round($ronExchange['Ron'] / $ronExchange['Euro'],2));
                $liraExchange = array("Dolar" => round($ronExchange['Dolar'] / $ronExchange['Lira'],2), "Yen" => round($ronExchange['Yen'] / $ronExchange['Lira'],2), "Euro" => round($ronExchange['Euro'] / $ronExchange['Lira'],2),"Lira" => 1, "Ron" => round($ronExchange['Ron'] / $ronExchange['Lira'],2));
                $_SESSION['DolarExchange'] = $DolarExchange;
                $_SESSION['YenExchange'] = $yenExchange;
                $_SESSION['EuroExchange'] = $euroExchange;
                $_SESSION['LiraExchange'] = $liraExchange;
                $_SESSION['RonExchange'] = $ronExchange;
                $valuteList = array("Dolar" => $DolarExchange,"Yen" => $yenExchange,"Euro" => $euroExchange,"Lira" => $liraExchange,"Ron" => $ronExchange);        
            }
            $select = $_POST['knowValue'];
            $_SESSION['updateValute'] = $_POST['knowValue'] ;
            $_SESSION['updateRon']  = $valuteList[$select]['Ron'];
            $_SESSION['updateEuro'] = $valuteList[$select]['Euro'];
            $_SESSION['updateDolar'] = $valuteList[$select]['Dolar'];
            $_SESSION['updateYen'] = $valuteList[$select]['Yen'];
            $_SESSION['updateLira'] = $valuteList[$select]['Lira'];
        }
        if ( $_REQUEST["Aplica"] == "Log Out" )
        {
            if (isset($_COOKIE['player'])) {
                unset($_COOKIE['player']);
                setcookie('player','',time() - 3600, '/');
            }
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
        if ( $_REQUEST["Aplica"] == "Finish" )
        {
            if ( $totalUserValue >= $_SESSION['minWinValue'] )
            {
                $username = 'tw';
                $password = 'TW';
                $connection_string = 'localhost/xe';
                $connection = oci_connect($username,$password,$connection_string);
                
                $Query = sprintf("insert into scor(user_name,highscore,data_scor) values('{$player}',{$totalUserValue},sysdate)");
                $res = oci_parse($connection,$Query);
                oci_execute($res);
                oci_close($connection);

                echo '<script type="text/javascript">alert("You Won!");</script>';
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
    <title>SpeculAPP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="style.css" />
    <script>
        function butoane() {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200) {
                    document.getElementById("imagini").innerHTML = this.responseText;
                }
            };
            xhttp.open("GET","userGameinfo.php",true);
            xhttp.send();
        }
        var interval = setInterval( function() {
            if (window.XMLHttpRequest) 
            {
               xmlhttp = new XMLHttpRequest();
            }
            else 
            {
               xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
               if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
                {
                  document.getElementById("rssOutput").innerHTML = xmlhttp.responseText;
                }
            }
            
            xmlhttp.open("GET","rss.php",true);
            xmlhttp.send();
         }, 100);
    </script>
</head>
<body>
    <header id="UserHeader">
        <div id = "imagini">
            <div id="Export">
               <input type="submit" onclick="butoane()" value =  "Hello!">
           </div>
        </div>
        <div id="Informatii">
            <p>Valoare totala a user-ului este: <?php echo $totalUserValue ?> RON</p>
            <p>Pragul pentru a pierde este: <?php echo $_SESSION['minValue'] ?> RON</p>
            <p>Pragul pentru a castiga este: <?php echo $_SESSION['minWinValue'] ?> RON</p>
        </div>
        
        <form method = "post">
            <a href="index.html"><input type="submit" value="Log Out" name ="Aplica"></a>
        </form>
    </header>
    <fieldset id="Valute">
        <legend>Valuta</legend>
        <label for="txtInformatiiCerute">Moneda cautata: <?php echo $_SESSION['updateValute'] ?></span></label><br>
        <label for="txtValutaRon">Ron: <?php echo $_SESSION['updateRon'] ?></label><br>
        <label for="txtValutaEuro">Euro: <?php echo $_SESSION['updateEuro'] ?></label><br>
        <label for="txtValutaDolar">Dolar: <?php echo $_SESSION['updateDolar'] ?> </label><br>
        <label for="txtValutaYen">Yen: <?php echo $_SESSION['updateYen'] ?> </label><br>
        <label for="txtValutaLira">Lira: <?php echo $_SESSION['updateLira'] ?> </label>
    </fieldset>
    
    <div id="UserMenu">
        <form method = "post">
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
                <select id="knowValue" name ="knowValue">
                    <option value="Ron">Ron</option>
                    <option value="Euro">Euro</option>
                    <option value="Dolar">Dolar</option>
                    <option value="Yen">Yen</option>
                    <option value="Lira">Lira</option>
                </selec><br>
                <input type="submit" name="Aplica" value="Update">
            </fieldset>
            <input type="submit" value="Finish" id = "ButonFinal" name = "Aplica">
        </form>
    </div>
    <footer id="rssOutput">
    </footer>
</body>
</html>