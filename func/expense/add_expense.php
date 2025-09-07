<?php
session_start();
include ('../../utils/db.php');

if (isset($_POST['amount'])) {
    $label = $_POST['label'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    $uid = $_SESSION['user_id'];
    
    $stmt = $conn->prepare("INSERT INTO expenses (user, label, amount, date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isds", $uid, $label, $amount, $date);
    $stmt->execute();
}
?>