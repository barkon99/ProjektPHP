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
        <title>Bookweb - dodaj ocene</title>
    </head>
    <body>
        <form action="page.php" method="post">
            <?php   
            $i = 1;           
            while($row = $query->fetch_assoc()) 
            {               
                $ocena2 = "ocena".$i;
                echo "ID: ".$row['id']."<br>"."Tytuł: ".$row['tytul']."<br>"."Autor: ".$row['autor']."<br>"."Rok: ".$row['rok']."<br>"."<br>";
                echo "Ocena(1-10) "."<input name =$ocena2>"."<br><input type = submit value = zatwierdz >"."<br><br><br>";
                $i++;
            } 
            ?>
            <a href="page.php">cofnij</a>
        </form>
    </body>
</html>