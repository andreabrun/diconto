<?php
session_start();
include ('../../utils/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['user'];
    $result = $conn->query("SELECT id, date 
                            FROM settlements
                            ORDER BY date ASC");

    echo "<table class='settlementsTable'>
        <tr>
            <th>Id</th>
            <th>Date</th> 
            <th>Edit</th>
        </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['date']}</td>
            <td> <button onclick='deleteSettlement({$row['id']})'>Delete</button> <button onclick='diconto({$row['id']})'>diconto</button> </td>
        </tr>";
    }
    echo "</table>";
}
?>