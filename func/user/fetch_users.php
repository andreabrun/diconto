<?php
session_start();
include ('../../utils/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $result = $conn->query("SELECT id, username, role 
                            FROM users
                            WHERE id > 1
                            ORDER BY username ASC");

    echo "<table class='usersTable'>
        <tr>
            <th>Id</th>
            <th>Username</th>
            <th>Role</th> 
            <th>Edit</th>
        </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['username']}</td>
            <td>{$row['role']}</td>
            <td> <button onclick='deleteUser({$row['id']})'>Delete</button> </td>
        </tr>";
    }
    echo "</table>";
}
?>