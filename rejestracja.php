<?php
if(isset($_POST['email']))
{
    require_once 'connect.php';
    include 'User.php';
    include 'Author.php';
    $connection = new mysqli($host, $user, $password, $db);
    if(isset($_POST['autor']))
    {
        $_SESSION['checkbox'] = $_POST['autor'];
        $autor = new User($_POST['login'],$_POST['haslo'],$connection);
        $autor->przypiszdane($_POST['email'], $_POST['haslo2']);
        $autor->rejestracja();
        
    }
    else
    {
        $user = new User($_POST['login'],$_POST['haslo'],$connection);
        $user->przypiszdane($_POST['email'], $_POST['haslo2']);
        $user->rejestracja();
    }
}
?>


<!Doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Bookweb - Załóż konto</title>
        <style>
            .error
            {
                color:red;
                margin-top: 10px;
                margin-bottom: 10px; 
            }
        </style>
    </head>
    <body>
        <form method="post">
            Witaj w formularzu rejestracyjnym!<br><br>
            <label>
                <input type="checkbox" name="autor">Autor<br><br>
            </label>
            E-mail <br/><input type = "text" name="email"><br/>
            <?php
            if(isset($_SESSION['error_email']))
            {
                echo '<div class = "error">'.$_SESSION['error_email'].'</div>';
                unset($_SESSION['error_email']);
            }
            ?>
            Login <br/><input type = "text" name="login"><br/>
            <?php
            if(isset($_SESSION['error_login']))
            {
                echo '<div class = "error">'.$_SESSION['error_login'].'</div>';
                unset($_SESSION['error_login']);
            }
            ?>
            Hasło <br/><input type = "password" name="haslo"><br/>
            <?php
            if(isset($_SESSION['error_haslo']))
            {
                echo '<div class = "error">'.$_SESSION['error_haslo'].'</div>';
                unset($_SESSION['error_haslo']);
            }
            ?>           
            Powtórz Hasło <br/><input type = "password" name="haslo2"><br/>
            <label>
                <input type="checkbox" name="regulamin">Regulamin<br>
            </label>
            <?php
            if(isset($_SESSION['error_regulamin']))
            {
                echo '<div class = "error">'.$_SESSION['error_regulamin'].'</div>';
                unset($_SESSION['error_regulamin']);
            }
            ?>              
            
            <br><input type="submit" value="Zarejestruj sie">
          
        </form>          
    </body>
</html>

