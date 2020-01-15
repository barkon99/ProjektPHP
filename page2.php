<?php
     session_start();
     require_once 'connect.php';
     $login = $_SESSION['login'];
?>


<!Doctype html>
<html>
    <head>
        <meta charset="Utf-8">
        <title>Bookweb - Autor</title>
        <style>
            .error
            {
                color:red;
                margin-top: 10px;
                margin-bottom: 10px; 
            }
            .no_error
            {
                color:green;
                margin-top: 10px;
                margin-bottom: 10px; 
            }
            .bold
            {
                font-weight: bold;
                margin-top: 10px;
                margin-bottom: 10px; 
            }              
        </style>
    </head>
    <body>
        <style> td, th { border: 1px solid black; } </style>
        <form  method="post">
            <?php 
            echo "Witaj ".$_SESSION['login']."!"."<br>";
            echo "<div class = 'bold'>"."Dodane ksiazki"."<br>"."<br>";                  
            ?>
    <table>
       <thead>
          <tr>
             <th>ID</th> <th>Tytuł ksiązki</th><th>Średnia ocen</th> 
          </tr>
       </thead>
       <tbody>
           <?php
                if(isset($_POST['tytul']))
                {
                   
                    $tytul = $_POST['tytul'];
                    $rok = $_POST['rok'];
                    $id = $_SESSION['id'];
                    $flaga = true;
                    
                    if(!($rok>0&&$rok<2020))
                    {
                        $flaga = false;
                        $_SESSION['e_rok'] = "Podaj prawidłowy rok!";
                    }
                    if(empty($_POST['rok']))
                    {
                        $flaga = false;
                        $_SESSION['e_rok'] = "Podaj rok!";                        
                    }                    
                    if(empty($_POST['tytul']))
                    {
                        $flaga = false;
                        $_SESSION['e_tytul'] = "Podaj tytuł!";                        
                    }
                    
                    try
                    {
                        $connection = new mysqli($host, $user, $password, $db);
                       
                        $userquery = $connection->query("SELECT * from ksiazki where tytul = '$tytul'");
                        if($userquery->num_rows>0)
                        {
                            $flaga = false;
                            $_SESSION['e_tytul'] = "Podana ksiazka juz istnieje w bazie";                              
                        }
                        if($flaga == TRUE)
                        {
                            $connection->query("INSERT INTO ksiazki VALUES(NULL,'$tytul','$login','$id','$rok','0')");
                            $_SESSION['poprawny'] = "Dziekujemy za dodanie ksiazki!";
                        }
                        //$connection->close();
                    }
                    catch(Exception $ex)
                    {
                       echo "connection failed ". $ex->getMessage();
                    }
                }
                try
                {
                    $connection = new mysqli($host, $user, $password, $db);
                    $userquery = $connection->query("SELECT id, tytul, rok from ksiazki WHERE autor = '$login'");
                    
                    if($userquery->num_rows>0)
                    {
                        while($row = $userquery->fetch_assoc())
                        {
                            $id_ksiazki = $row['id'];
                            $userquery2 = $connection->query("SELECT ocena from oceny WHERE id_ksiazki = '$id_ksiazki'");
                            $suma = 0;
                            $ilosc = 0;
                            $srednia = 0;
                            if($userquery2->num_rows>0)
                            {
                                while($row2 = $userquery2->fetch_assoc())
                                {
                                    $suma += $row2['ocena'];
                                    $ilosc++;
                                }
                                $srednia = $suma/$ilosc;
                            }
                            echo "<tr><td>{$row['id']}</td><td>{$row['tytul']}</td><td>$srednia</td></tr>"; 
                        }                       
                    }
                    $connection->close();

                }
                catch(Exception $ex)
                {
                   echo "connection failed ". $ex->getMessage();
                }
           ?>
       </tbody>
    </table>
            <br><br><br>    
            Tytuł <input type="text" name="tytul"><br><br>
            <?php
            if(isset($_SESSION['e_tytul']))
            {
                echo '<div class = "error">'.$_SESSION['e_tytul'].'</div>';
                unset($_SESSION['e_tytul']);
            }
            ?>
            Rok wydania <input type="text" name="rok"><br><br>
            <?php
            if(isset($_SESSION['e_rok']))
            {
                echo '<div class = "error">'.$_SESSION['e_rok'].'</div>';
                unset($_SESSION['e_rok']);
            }
            
            if(isset($_SESSION['poprawny']))
            {
                echo '<div class = "no_error">'.$_SESSION['poprawny'].'</div>'."<br>";
                unset($_SESSION['poprawny']);
            }   
            ?>
            <input type="submit" value="Dodaj ksiazke"><br><br>
        </form>

        <?php 
            echo '<a href = "logout.php">Wyloguj sie</a>'; 
        ?>
    </body>
</html>