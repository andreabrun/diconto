<?php
session_start();
include '../../utils/db.php';

if (isset($_POST['user'])) {
    $user = $_POST['user'];
    $editable = $_POST['editable'];
    $expensesPeriod = $_POST['expensesPeriod'];

    $query = "SELECT e.id, e.label, e.amount, e.date, u.id AS uid, u.username, s.date AS sdate
                FROM expenses e JOIN users u ON e.user = u.id
                LEFT JOIN settlements s ON e.settlement = s.id
                WHERE u.id = ? " 
                . (($expensesPeriod == "all") ? "" : "AND e.settlement IS NULL ") .
                "ORDER BY e.date DESC";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    $sum = 0;
    echo "<table class='expensesTable'>
        <tr>
            <th>User</th>
            <th>Label</th>
            <th>Amount</th>
            <th>Date</th>" .
            (($editable === "true") ? "<th>Edit</th>" : "") .
        "</tr>";
    while ($row = $result->fetch_assoc()) {
        $sum = $sum + $row['amount'];
        echo "<tr>
            <td>{$row['username']}</td>
            <td>{$row['label']}</td>
            <td>{$row['amount']}</td>
            <td>{$row['date']}</td>" .
            (($editable === "true") ? "<td> <button onclick='deleteExpense({$row['id']}, {$row['uid']})'>Delete</button> " : "");
            if($editable === "true") {
                if($row['sdate'] == null) echo "<input type='checkbox' class='expensesSelectRow' name='select' value='{$row['id']}' /><label for='select'>Select </label> </td>";
                else echo "Settled on {$row['sdate']} </td>";
            }
        echo "</tr>";
    }
    echo "</table>";

    echo "<h4>Sum: " . $sum . "</h4>";
}
?>