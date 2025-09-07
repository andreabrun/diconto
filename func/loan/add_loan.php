<?php
session_start();
include ('../../utils/db.php');

if (isset($_POST['amount'])) {
    $label = $_POST['label'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    $uid = $_SESSION['user_id'];
    $userbid = $_POST['userb'];
    
    $stmt = $conn->prepare("INSERT INTO loans (userL, userB, label, amount, date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisds", $uid, $userbid, $label, $amount, $date);
    $stmt->execute();
}
?>