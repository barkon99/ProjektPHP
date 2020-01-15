<?php
    session_start();
    require_once 'connect.php';
    $connection = new mysqli($host, $user, $password, $db);
    $query = $connection->query("Select tytul,autor,rok,id from ksiazki");
?>

<!Doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Bookweb - dodaj recenzjee</title>
    </head>
    <body>
        <form action="page.php" method="post">
            <?php   
            $i = 1;          
            while($row = $query->fetch_assoc()) 
            {               
                $recenzja = "recenzja".$i;
                echo "ID: ".$row['id']."<br>"."Tytuł: ".$row['tytul']."<br>"."Autor: ".$row['autor']."<br>"."Rok: ".$row['rok']."<br>"."<br>";
                echo "<textarea name = '$recenzja' cols=70 rows=10></textarea>"."<br><input type = submit value = Dodaj recenzje >"."<br><br><br>";
                $i++;
            } 
            ?>
            <a href="page.php">cofnij</a>
        </form>
    </body>
</html>