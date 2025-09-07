<?php
session_start();
include ('../../utils/db.php');

if (isset($_POST['uid'])) {
    $uid = $_POST['uid'];

    $stmt = $conn->prepare("DELETE FROM expenses WHERE user = ?");
    $stmt->bind_param("i", $uid);
    $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
}
?>