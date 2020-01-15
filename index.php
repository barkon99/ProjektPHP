<!DOCTYPE html>

        <?php
           session_start();
        ?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Bookweb</title>
    </head>
    <body>
        <form action="Zaloguj.php" method="post">
            Login:<br><input type="text" name="login"><br>
            Haslo:<br><input type="password" name = "haslo"><br><br>
            <input type="submit" value="Zaloguj sie"><br><br>
            <a href="rejestracja.php">Zarejestruj sie</a>
        </form>
        <?php
           if(isset($_SESSION['blad']))
           {
               echo $_SESSION['blad'];
               unset($_SESSION['blad']);
           }
        ?>
    </body>
</html>
