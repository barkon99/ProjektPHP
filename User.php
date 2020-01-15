<?php


class User
{
    public $login;
    private $haslo;
    private $haslo2;
    public $email;
    public $connection;
    public function __construct($login,$haslo,$connection) 
    {
        $this->login = $login;
        $_SESSION['login'] = $login;
        $this->haslo = $haslo;
        $this->connection = $connection;
    }
    public function przypiszdane($email,$haslo2)
    {
        $this->email = $email;
        $this->haslo2 = $haslo2;
    }

    public function logowanie()
    {
        
        if((!isset($this->login))||(!isset($this->haslo)))
        {
           header("Location: index.php");
           exit();
        }       
        require_once 'connect.php';
        try
        {
            $this->login = htmlentities($this->login, ENT_QUOTES,"UTF-8");
            if($rezultat = $this->connection->query("Select * from uzytkownicy where login = '$this->login'"))
            {
                $ilu_userow = $rezultat->num_rows;
                if($ilu_userow==1)
                {
                    $uzytkownik = $rezultat->fetch_assoc();
                    $_SESSION['id'] = $uzytkownik['id'];
                    if($this->haslo==$uzytkownik['haslo'])
                    {

                            $_SESSION['zalogowany'] = true;
                            unset($_SESSION['blad']);
                            header("Location: page.php");
                        
                    }
                }
                else if($rezultat2 = $this->connection->query("Select * from autorzy where login = '$this->login'"))
                {
                    $ilu_autorow = $rezultat2->num_rows;
                    if($ilu_autorow==1)
                    {
                        $autor = $rezultat2->fetch_assoc();
                        $_SESSION['id'] = $autor['id'];
                        $_SESSION['login'] = $autor['login'];
                        if($this->haslo==$autor['haslo'])
                        {
                             $_SESSION['zalogowany'] = true;
                            unset($_SESSION['blad']);
                            header("Location: page2.php");
                        }
                    }
                }
                else
                {
                    $_SESSION['blad'] = '<span style = "color:red">Nieprawidlowy login lub haslo</span>';
                    header("Location: index.php");
                }

                $this->connection->close();
            }
        }
        catch (Exception $ex)
        {
            echo "connection failed ". $ex->getMessage();
        }
    }
    
    public function rejestracja()
    {
        session_start();

            $flaga = true;

            if((strlen($this->login)<3)||  strlen($this->login)>20)
            {
                $flaga = false;
                $_SESSION['error_login'] = "Login musi posiadac od 3 do 20 znakow!";
            }
            if(ctype_alnum($this->login)==false)
            {
                $flaga = false;
                $_SESSION['error_login'] = "Login musi sie skladac tylko z liter i cyfr!";
            }

            if((filter_var($this->email,FILTER_VALIDATE_EMAIL)!=$this->email)||(empty($this->email)))
            {
                $flaga = false;
                $_SESSION['error_email'] = "Podaj poprawny email";       
            }

            if((strlen($this->haslo)<8)||(strlen($this->haslo)>20))
            {
                $flaga = false;
                $_SESSION['error_haslo'] = "Hasło musi posiadac od 8 do 20 znakow!";
            }
            if($this->haslo!=$this->haslo2)
            {
                $flaga = false;
                $_SESSION['error_haslo'] = "Hasła muszą byc takie same!";
            }
            //$haslohash = password_hash($this->haslo, PASSWORD_DEFAULT);
            if(!isset($_POST['regulamin']))
            {
                $flaga = false;
                $_SESSION['error_regulamin'] = "Potwierdz regulamin";        
            }

            require_once 'connect.php';
            try
            {
                if($this->connection->connect_errno!=0)
                {
                    throw new Exception(mysqli_connect_erno());
                }
                else
                {
                    $result = $this->connection->query("SELECT email from uzytkownicy where email = '$this->email'");
                    if(!$result)
                    {
                        throw new Exception($this->connection->error);
                    }           
                    $ile_maili = $result -> num_rows;
                    if($ile_maili>0)
                    {
                        $flaga = false;
                        $_SESSION['error_email'] = "Istnieje juz taki email w bazie"; 
                    }


                    $result =  $this->connection->query("Select login from uzytkownicy where login = '$this->login'");
                    if(!$result)
                    {
                        throw new Exception($this->connection->error);
                    }  
                    $ile_loginow = mysqli_num_rows($result);
                    if($ile_loginow>0)
                    {
                        $flaga = false;
                        $_SESSION['error_login'] = "Istnieje juz taki login w bazie"; 
                    }

                    if($flaga == true)
                    {
                        if(!isset($_SESSION['checkbox']))
                        {    
                            if($this->connection->query("INSERT INTO uzytkownicy VALUES(NULL, '$this->email','$this->login','$this->haslo')"))
                            {
                                $_SESSION['udanarejestracja'] = true;
                                header("Location: page.php");
                            }
                        }
                        else
                        {
                            if($this->connection->query("INSERT INTO autorzy VALUES(NULL,'$this->login','$this->email','$this->haslo')"))
                            {
                                $_SESSION['udanarejestracja'] = true;
                                header("Location: page2.php");
                            }                           
                        }
                    }           
                }
            } 
            catch (Exception $ex) 
            {
                echo '<span style = "color:red;">Błąd serwera!</span>';
                echo '</br> Informacja o bledzie: '.$ex;
            }
        
    }   
}
?>


