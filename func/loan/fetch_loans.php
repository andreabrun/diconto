<?php
session_start();
include '../../utils/db.php';

if (isset($_POST['user'])) {
    $user = $_POST['user'];
    $userB = $_POST['userb'];
    $editable = $_POST['editable'];
    $loansPeriod = $_POST['loansPeriod'];

    $filterUserB = isset($userB);

    $query = "SELECT l.id, l.label, l.amount, l.date, 
                    ul.id AS uid, ul.username AS usernameL,
                    ub.id AS uid, ub.username AS usernameB,
                    s.date AS sdate
                FROM loans l 
                    JOIN users ul ON l.userL = ul.id
                    LEFT JOIN users ub ON l.userB = ub.id
                    LEFT JOIN settlements s ON l.settlement = s.id
                WHERE ul.id = ? "
                . (($loansPeriod == "all") ? "" : "AND l.settlement IS NULL ") 
                . (($filterUserB) ? "AND ub.id = ? " : "") .
                "ORDER BY l.date DESC";
    
    $stmt = $conn->prepare($query);

    if($filterUserB) {
        $stmt->bind_param("ii", $user, $userB);
    } else {
        $stmt->bind_param("i", $user);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $sum = 0;
    
    echo "<table class='loansTable'>
        <tr>
            <th>User</th>
            <th>Borrower</th>
            <th>Label</th>
            <th>Amount</th>
            <th>Date</th>" .
            (($editable === "true") ? "<th>Edit</th>" : "") .
        "</tr>";
    while ($row = $result->fetch_assoc()) {
        $sum = $sum + $row['amount'];
        echo "<tr>
            <td>{$row['usernameL']}</td>
            <td>{$row['usernameB']}</td>
            <td>{$row['label']}</td>
            <td>{$row['amount']}</td>
            <td>{$row['date']}</td>" .
            (($editable === "true") ? "<td> <button onclick='deleteLoan({$row['id']}, {$row['uid']})'>Delete</button> " : "");
            if($editable === "true") {
                if($row['sdate'] == null) echo "<input type='checkbox' class='loansSelectRow' name='select' value='{$row['id']}' /><label for='select'>Select </label> </td>";
                else echo "Settled on {$row['sdate']} </td>";
            }
        echo "</tr>";
    }
    echo "</table>";

    echo "<h4>Sum: " . $sum . "</h4>";
}
?>