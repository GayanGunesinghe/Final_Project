<?php
    $servername = "localhost";
    $username = "root";
    $password = "toor";
    $dbname = "project_samtessi";
    //create new connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    //check connection
    if($conn ->connect_error){
        die("Connection Failed: " .$conn->connect_error);
    }
    return true;
    ?>
