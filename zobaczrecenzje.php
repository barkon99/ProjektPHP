<?php
require_once 'connect.php';
session_start();
$connection = new mysqli($host, $user, $password, $db);
if(isset($_SESSION['id_ksiazki']))
{
    $id_ksiazki = $_SESSION['id_ksiazki'];
    $query = $connection->query("Select * from recenzje where id_ksiazki = '$id_ksiazki'");
}
?>
<!Doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Bookweb - recenzje</title>
    </head>
    <body>
        <form action="page.php" method="post">
            <?php 
            $link = $_SERVER['QUERY_STRING'];
            $zm = substr($link, 3);
            $zmienna = (int)$zm;       
            
            if(isset($_SESSION['id_ksiazki']))
            {
                $id_ksiazki = $_SESSION['id_ksiazki'];
                $query = $connection->query("Select * from recenzje where id_ksiazki = $zmienna");
                if($query->num_rows == 0)
                {
                    echo "Brak recenzji do danej książki"."<br>";
                }
                while($row = $query->fetch_assoc()) 
                {     
                    echo "Tytul: ".$_SESSION['tytul']."<br>"."Recenzja: ".$row['recenzja']."<br>"."<br>";         
                }
                unset($_SESSION['id_ksiazki']);
            }
            ?>
            <a href="page.php">cofnij</a>
        </form>
    </body>
</html>