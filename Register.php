<?php
    ob_start();
    session_start();
?>
<?php
    $msg = '';
    $userNam = trim($_POST['username']);
    $pass = trim($_POST['password']);
    $email = trim($_POST['email']);
    if($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($userNam != null && $pass != null && $email != null) 
    {
        $username = 'tw';
        $password = 'TW';
        $connection_string = 'localhost/xe';
        $connection = oci_connect($username,$password,$connection_string);

        $query = sprintf("Select user_name,pass from jucator where user_name = '{$userNam}' or email = '{$email}' ");
        $result = oci_parse($connection,$query);
        oci_execute($result);
        $resulting = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS );
        if (!$resulting)
        {
            //The User doesn't exist, must be added
            $queryAdd = sprintf("Insert into jucator(user_name,pass,email) values(:username,:pass,:email) ");
            $res = oci_parse($connection,$queryAdd);
            oci_bind_by_name($res,':username',$userNam);
            oci_bind_by_name($res,':pass',$pass);
            oci_bind_by_name($res,':email',$email);
            oci_execute($res);
            oci_close($connection);
            $_SESSION['valid'] = true;
            $_SESSION['timeout'] = time();
            $_SESSION['username'] = $userNam;
            header("Location: game.html",true,301);
            exit();
        }
        else
        {
            oci_close($connection);
            $msg = "User already exists or email is already in use.";
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
        <title>My Website</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" media="screen" href="style.css"/>
    </head>
<body>
    <div class="login-register">
        <img src="avatar.png" class="avatar">
            <h1>Register</h1>
            <p style="font-size:small; color:red;"><?php echo $msg; ?></p>
            <form method="post">
                <p>Username</p>
                <input type="text" name="username" placeholder="Enter Username">
                <p>Password</p>
                <input type="password" name="password" placeholder="Enter Password">
                <p>Email</p>
                <input type="text" name="email" placeholder="Enter email">
                <input type="submit" value="Create">
            </form>  
    </div>
</body>
</html>