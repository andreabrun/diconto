<?php
session_start();
include ('../../utils/db.php');

$data = json_decode(file_get_contents("php://input"), true);

$type = $data['type'];
$rows = $data['rows'];
$sid = (int)$data['sid'];

$allowedTables = ['expenses', 'loans'];
if (!in_array($type, $allowedTables)) {
    die("Invalid table type.");
}

$placeholders = implode(',', array_fill(0, count($rows), '?'));

$sql = "UPDATE {$type}
        SET settlement = {$sid}
        WHERE id IN ($placeholders)";

echo "sql {$sql}";
$stmt = $conn->prepare($sql);

$paramtypes = str_repeat('i', count($rows));
$params = array_merge([$paramtypes], $rows);
$stmt->bind_param(...$params);

$stmt->execute();

?>