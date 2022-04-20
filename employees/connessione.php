<?php

    $hostname = "172.17.0.1:3306";
    $username = "root";
    $password = "my-secret-pw";
    $db = "mydb";

    
    $database = mysqli_connect($hostname, $username, $password, $db)
                or die("<b>Fallimento:</b> " . mysqli_connect_error());
    

?>