<?php

session_start();

class User
{
    private $id;
    public $login;
    public $email;
    public $firstname;
    public $lastname;
    private $conn;

    public function __construct() {

        $db_username = 'root';
        $db_password = '';
        
        // On essaie de se connecter
        try{

            $this->conn = new PDO('mysql:host=localhost;dbname=classes;charset=utf8', $db_username, $db_password);

            // On définit le mode d'erreur de PDO sur Exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            echo 'Connection réussie<br>';
        }

        // On capture les exceptions si une exception est lancée
        catch(PDOException $e){

            // et on affiche les informations relatives à celle-ci
            echo "Erreur : " . $e->getMessage();

        }

    }

    public function register($login, $password, $email, $firstname, $lastname) {

        $sql = "SELECT * FROM utilisateurs WHERE login=:login";
        
        // Check if the username is already present or not in our Database.
        $req = $this->conn->prepare($sql);
        $req->execute(array(':login' => $login));
        $row = $req->rowCount();
        
        if($row <= 0) {     // If the login do not exist in the Database, we check for errors

            // Cripting the password
            $hash = password_hash($password, PASSWORD_DEFAULT);
                
            // Add data to the database 
            $sql = "INSERT INTO `utilisateurs` (`login`, `password`, `email`, `firstname`, `lastname`) VALUES (:login, :pass, :email, :firstname, :lastname)";
            $req = $this->conn->prepare($sql);
            $req->execute(array(':login' => $login,
                                ':pass' => $hash,
                                ':email' => $email,
                                ':firstname' => $firstname,
                                ':lastname' => $lastname));
    
            if ($sql) {      // If the user is created
                echo '<strong>Success!</strong> Your account is now created and you can login.';
                
                $userData = [
                    'login' => $login,
                    'password' => $hash,
                    'email' => $email,
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                ];

                return $userData;
            }
            
        }else{      // If login already exist
            echo '<strong>Error!</strong> Le login existe déjà'; 
        }
    }

    public function connect($login, $password) {

        $sql = "SELECT * FROM utilisateurs WHERE login=:login";
        
        // Check if the username is already present or not in our Database.
        $req = $this->conn->prepare($sql);
        $req->execute(array(':login' => $login));
        $row = $req->rowCount();
        
        if($row == 1){    // If the login exist in the data base, continue

            $tab = $req->fetch(PDO::FETCH_ASSOC);
            $dataPass = $tab['password'];
            $id = $tab['id'];

            if(password_verify($password,$dataPass)){    // Check if the password existe in the database and decript it

                $_SESSION['id'] = $id;
                $_SESSION['login'] = $login;
                $_SESSION['password'] = $dataPass;
                $_SESSION['email'] = $tab['email'];
                $_SESSION['firstname'] = $tab['firstname'];
                $_SESSION['lastname'] = $tab['lastname'];

                echo '<strong>Success!</strong> You\'re connected';

            }else{    // If the password do not match, error
                echo '<strong>Error!</strong> Wrong password';
            }
        }else{    // If the login do not exist, error
            echo '<strong>Error!</strong> The login do not exist. You don\'t have an account? <a href=\"inscription.php\">Signup</a>';
        }

    }

    public function disconnect() {

        session_destroy();
        exit('Vous avez bien été deconnecté');

    }

    public function delete() {

        if($_SESSION){

            // Set variables to use in the following request.
            $sessionId = $_SESSION['id'];

            $sql = "DELETE FROM `utilisateurs` WHERE id = :sessionId";
        
            // Check if the username is already present or not in our Database.
            $req = $this->conn->prepare($sql);
            $req->execute(array(':sessionId' => $sessionId));

            session_destroy();
            exit('You have deleted your account');


        }else{
            echo 'Please login to delete your account<br>';
        }

    }

    public function update($login, $password, $email, $firstname, $lastname) {

        // Set variables to use in the following request.
        $sessionId = $_SESSION['id'];
        $passwordTrue = $_SESSION['password'];

        $sql = "SELECT * FROM utilisateurs WHERE id = :sessionId";
        
        // Check if the username is already present or not in our Database.
        $req = $this->conn->prepare($sql);
        $req->execute(array(':sessionId' => $sessionId));
        $row = $req->rowCount();
            
        if ($_SESSION['login'] != $login){

            if($row!=1){

                echo '<strong>Error!</strong> The login already exist';

            }else{

                $sqlLog = "UPDATE utilisateurs SET login = :login WHERE id = :sessionId";
        
                // Check if the username is already present or not in our Database.
                $req = $this->conn->prepare($sqlLog);
                $req->execute(array(':login' => $login, ':sessionId' => $sessionId));
                
                $_SESSION['login'] = $login;
                echo '<strong>Success!</strong> Your login has been edited<br>';

            }

        }

        if(!password_verify($password,$passwordTrue)){
            
            // Cripting the new password
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $sqlPass = "UPDATE utilisateurs SET password = '$hash' WHERE id = '$sessionId'";
            $rs = $this->conn->query($sqlPass);

            $_SESSION['password'] = $hash;
            echo '<strong>Success!</strong> Your password has been edited<br>';

        }
            
        if ($_SESSION['email'] != $email){

            $sqlMail = "UPDATE utilisateurs SET email = '$email' WHERE id = '$sessionId'";
            $rs = $this->conn->query($sqlMail);
            $_SESSION['email'] = $email;
            echo '<strong>Success!</strong> Your email has been edited<br>';

        }
            
        if ($_SESSION['firstname'] != $firstname){

            $sqlFirstN = "UPDATE utilisateurs SET firstname = '$firstname' WHERE id = '$sessionId'";
            $rs = $this->conn->query($sqlFirstN);
            $_SESSION['firstname'] = $firstname;
            echo '<strong>Success!</strong> Your first name has been edited<br>';

        }
            
        if ($_SESSION['lastname'] != $lastname){

            $sqlLastN = "UPDATE utilisateurs SET lastname = '$lastname' WHERE id = '$sessionId'";
            $rs = $this->conn->query($sqlLastN);
            $_SESSION['lastname'] = $lastname;
            echo '<strong>Success!</strong> Your last name has been edited<br>';

        }

    }

    public function isConnected() {

        if($_SESSION){
            return true;
        }else{
            return false;
        }

    }

    public function getAllInfos() {

        if($_SESSION){
            return $_SESSION;
        }else{
            echo 'Please login to view your infos<br>';
        }

    }

    public function getLogin() {

        if($_SESSION){
            return $_SESSION['login'];
        }else{
            echo 'Please login to view your login<br>';
        }

    }

    public function getEmail() {

        if($_SESSION){
            return $_SESSION['email'];
        }else{
            echo 'Please login to view your email<br>';
        }

    }

    public function getFirstname() {

        if($_SESSION){
            return $_SESSION['firstname'];
        }else{
            echo 'Please login to view your first name<br>';
        }

    }

    public function getLastname() {

        if($_SESSION){
            return $_SESSION['lastname'];
        }else{
            echo 'Please login to view your last name<br>';
        }

    }

}

$newUser = new User();
//$newUser->register('admin', 'admin', 'admin@gmail.com', 'Admin', 'Admin');
//$newUser->connect('admin', 'admin');
//$newUser->update('admin', 'admin', 'admin@gmail.com', 'Admin', 'Admin');
//$newUser->delete();
var_dump($_SESSION);

?>