<?php
session_start();
include ('../../utils/db.php');

if (isset($_POST['date'])) {
    $date = $_POST['date'];
    
    $stmt = $conn->prepare("INSERT INTO settlements (date) VALUES (?)");
    $stmt->bind_param("s", $date);
    $stmt->execute();
}
?>