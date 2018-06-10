<?php
    error_reporting(0);
    //ini_set('display_errors', 0);
    header("Content-Type: application/rss+xml; charset=ISO-8859-1");

    $rssfeed = '<?xml version="1.0" encoding="ISO-8859-1"?>';
    $rssfeed .= "\n";
    $rssfeed .= '<rss version="2.0">';
    $rssfeed .= "\n";
    $rssfeed .= '<channel>';
    $rssfeed .= "\n";
    $rssfeed .= '<title>Highscores</title>';
    $rssfeed .= "\n";
    $rssfeed .= '<description>Top 10:</description>';
    $rssfeed .= "\n";
 
    $username = 'tw';
    $password = 'TW';
    $connection_string = 'localhost/xe';
    $connection = oci_connect($username,$password,$connection_string);
    $Query = sprintf("Select user_name, highscore from (Select user_name, highscore from scor order by highscore desc) where rownum <= 10");
    $res = oci_parse($connection,$Query);
    oci_execute($res);
    oci_fetch_all($res, $resulting, null, null, OCI_FETCHSTATEMENT_BY_ROW);
    $rowNumber = 1;
    $column = 0;
    foreach ($resulting as $row)
    {
        $rssfeed .= '   <player>';
        $rssfeed .= "\n";
        $rssfeed .= '       <position>' . $rowNumber . '-</position>';
        $rssfeed .= "\n";
        foreach ($row as $col)
        {
            if ( $column == 0 )
            {
                $rssfeed .= '       <playerName>' . $col . '</playerName>';
                $rssfeed .= "\n";
                $column = $column + 1;
            }
            else if ( $column == 1 )
            {
                $rssfeed .= '       <playerScore>' . $col . ';</link>';
                $rssfeed .= "\n";
                $column = 0;
            }
        }
        $rssfeed .= '   </playerScore>';
        $rssfeed .= "\n";
        $rowNumber = $rowNumber + 1;
    }
    
    oci_free_statement($res);
    oci_close($connection);

    $rssfeed .= '</channel>';
    $rssfeed .= "\n";
    $rssfeed .= '</rss>';
 
    echo $rssfeed;
?>