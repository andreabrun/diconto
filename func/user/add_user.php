<?php
session_start();
include ('../../utils/db.php');

if ($_SESSION['role'] !== 'admin') {
    die("Access denied");
}

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = hash("sha256", $_POST['password']);
    $role = $_POST['role'];
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $role);
    $stmt->execute();
}
?>
