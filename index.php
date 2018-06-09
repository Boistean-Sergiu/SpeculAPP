<?php
    ob_start();
    session_start();
    error_reporting(0);
    ini_set('display_errors', 0);
?>
<?php
    $msg = '';
    $userNam = $_POST['username'];
    $pass = $_POST['password'];
    if($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($userNam!=null && $pass!=null) 
    {
        $username = 'tw';
        $password = 'TW';
        $connection_string = 'localhost/xe';
        $connection = oci_connect($username,$password,$connection_string);

        $query = sprintf("Select user_name,pass from jucator where user_name = '{$userNam}' and pass = '{$pass}' ");
        $result = oci_parse($connection,$query);
        oci_execute($result);
        $resulting = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS );
        if (!$resulting)
        {
            $msg = 'Wrong username or password';
            oci_close($connection);
        }
        else
        {
            oci_close($connection);
            $_SESSION['valid'] = true;
            $_SESSION['timeout'] = time();
            $_SESSION['username'] = $userNam;
            header("Location: game.php",true,301);
            exit();
        }
    }
    else
    {
        $msg = "Weird mistake";
    }}
?>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SpeculAPP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="style.css" />
</head>
<body>  
    <div class="login-register">
        <img src="avatar.png" class="avatar">
            <h1>Sign In</h1>
            <form role = "form" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "post">
            <p style="font-size:small; color:red;"><?php echo $msg; ?><p>
                <p>Username</p>
                <input type="text"  name="username" placeholder="Enter Username" required autofocus>
                <p>Password</p>
                <input type="password" name="password" placeholder="Enter Password" required>
                <input type="submit" value="submit">
                <a href="Register.php">Don't have an account?</a> 
            </form>  
    </div>
</body>
</html>

