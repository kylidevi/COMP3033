<?php
    $conn = mysqli_connect('localhost','project_user','passw0rd','projectdb');
    if ($conn->connect_error) {
        die('Error : ('. $conn->connect_errno .') '. $conn->connect_error);
    }
?>