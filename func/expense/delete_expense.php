<?php
session_start();
include ('../../utils/db.php');

if (isset($_POST['eid'])) {
    $eid = $_POST['eid'];
    
    $stmt = $conn->prepare("DELETE FROM expenses WHERE id = ?");
    $stmt->bind_param("i", $eid);
    $stmt->execute();
}
?>