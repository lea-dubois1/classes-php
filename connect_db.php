<?php 

    session_start();

    $db_host = 'localhost';
    $db_username = 'root';
    $db_password = '';
    $db_name = 'classes';
    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    if($conn->connect_error){
        die('Erreur : ' . $conn->connect_error);
    }
    echo 'Connection réussie<br>';
    
?>