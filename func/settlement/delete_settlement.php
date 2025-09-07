<?php
session_start();
include ('../../utils/db.php');

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Expenses
    $stmt = $conn->prepare("SELECT id FROM expenses WHERE settlement IS NOT NULL AND settlement = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $whereusedexpenses = $stmt->get_result();

    // Loans
    $stmt = $conn->prepare("SELECT id FROM loans WHERE settlement IS NOT NULL AND settlement = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $whereusedloans = $stmt->get_result();

    if ($whereusedexpenses->num_rows > 0 || $whereusedloans->num_rows > 0) {
        echo "ERROR: Settlement already used!";
    } else {
        $stmt = $conn->prepare("DELETE FROM settlements WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        echo "OK";
    }

    
}
?>