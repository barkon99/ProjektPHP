<?php
session_start(); 
?>
<!Doctype html>
<html>
    <head>
        <meta charset="Utf-8">
        <title>Bookweb - Strona główna</title>
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
        <?php
        echo "Witaj ".$_SESSION['login']." na Bookwebie!"."<br><br>";
        require_once 'connect.php';
        $connection = new mysqli($host, $user, $password, $db);
        $userquery = $connection->query("Select * from ksiazki");
        ?>
<style> td, th { border: 1px solid black; } </style>
<form method="post">
    <table>
       <thead>
          <tr>
             <th>ID</th> <th>Tytuł ksiązki</th> <th>Autor</th> <th>Średnia ocen</th> <th>Twoja ocena</th><th>Recenzje</th>
          </tr>
       </thead>
       <tbody>
           <?php
           $ocena='-';
           $i = 1;
           $id_uzytkownika = $_SESSION['id']; 
           if($userquery->num_rows>0)
           {                
               while($row = $userquery->fetch_assoc())
               {
                   $flaga = true;
                   $srednia = 0;
                   $suma = 0;
                   $ilosc = 0;
                   $id_ksiazki = $row['id'];                
                   if(isset($_POST['ocena'.$i])&& !empty($_POST['ocena'.$i]))
                   {
                        $query2 = $connection->query("Select * from oceny");
                        while($row1 = $query2->fetch_assoc())
                        {
                            if($row1['id_oceniajacego'] == $_SESSION['id'] && $row1['id_ksiazki'] == $row['id'])
                            {
                                $flaga = false;
                                $_SESSION['error'] = "Dodałes juz ocene do tej ksiazki!";
                            }
                        }                        
                        if(!($_POST['ocena'.$i]>=1&&$_POST['ocena'.$i]<=10))
                        {
                            $flaga = false;
                            $_SESSION['error'] = "Podaj liczbe od 1 do 10!";
                        }
                        if($flaga==true)
                        {
                            $ocena = $_POST['ocena'.$i];                         
                            $connection->query("INSERT INTO oceny values(NULL,'$id_uzytkownika','$id_ksiazki','$ocena')");
                            unset($_POST['ocena'.$i]);
                        }
                        $userquery3 = $connection->query("Select ocena from oceny where id_ksiazki = '$id_ksiazki' AND id_oceniajacego = '$id_uzytkownika'");                        
                        $row1 = $userquery3->fetch_assoc();
                        if($userquery3->num_rows==0){$ocena = "-";}
                        else {$ocena = $row1['ocena'];}

                   }                   
                   else
                   {
                       $user_query = $connection->query("Select ocena from oceny where id_ksiazki = '$id_ksiazki' AND id_oceniajacego = '$id_uzytkownika'");
                       if($user_query->num_rows>0)
                       {
                            $row1 = $user_query->fetch_assoc();
                            $ocena = $row1['ocena'];
                       }
                       else
                       {
                           $ocena = "-";
                       }
                   }
                   $userquery3 = $connection->query("Select ocena from oceny where id_ksiazki = '$id_ksiazki'");
                   if($userquery3->num_rows>0)
                   {
                       while($row2 = $userquery3->fetch_assoc())
                       {
                            $suma+=$row2['ocena'];
                            $ilosc++;
                       }
                       $srednia = $suma/$ilosc;
                   }
                   
                   
                   
                   if(isset($_POST['recenzja'.$i])&& !empty($_POST['recenzja'.$i]))
                   {
                        $flaga = true;
                        $query2 = $connection->query("Select * from recenzje");
                        while($row1 = $query2->fetch_assoc())
                        {
                            if($row1['id_oceniajacego'] == $_SESSION['id'] && $row1['id_ksiazki'] == $row['id'])
                            {
                                $flaga = false;
                                $_SESSION['error'] = "Dodałes juz recenzje do tej ksiazki!";
                            }
                        }                        
                        if($flaga==true)
                        {
                            $recenzja = $_POST['recenzja'.$i];
                            
                            $connection->query("INSERT INTO recenzje values(NULL,'$id_uzytkownika','$id_ksiazki','$recenzja')");
                            unset($_POST['recenzja'.$i]);
                        }

                   } 
                   $zmienna =$row['id'];
                   echo "<tr><td>{$row['id']}</td><td>{$row['tytul']}</td><td>{$row['autor']}</td><td>{$srednia}</td><td>{$ocena}</td><td><a href = 'zobaczrecenzje.php?id=$zmienna'>Zobacz recenzje</a></td></tr>";
                   $_SESSION['id_ksiazki'] = $id_ksiazki;
                   $_SESSION['tytul'] = $row['tytul'];
                   $i++;
               }
           }
           ?>
       </tbody>
    </table>    
</form>
        <?php
            if(isset($_SESSION['error']))
            {
                echo '<div class = "error">'.$_SESSION['error']."</div>"."<br>"  ;
                unset($_SESSION['error']);

            }        
            echo '<a href = "ocena.php">Dodaj ocene</a><br> ';
            echo '<a href = "recenzja.php">Dodaj recenzje</a><br><br>';
            echo '<a href = "logout.php">Wyloguj sie</a>'
        ?>
    </body>
</html>