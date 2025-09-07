<?php
include 'utils/db.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>


<?php
$otheruserslist = [];
if (!empty($_SESSION['role']) && $_SESSION['role'] === 'user') {
    $stmt = $conn->prepare("SELECT id, username 
                            FROM users
                            WHERE id > 1 AND id <> ? 
                            ORDER BY username ASC");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $otheruserslist[] = $row;
    }
}
$otherusers = count($otheruserslist);

?>

<!DOCTYPE html>
<html>

<head>
<title>Expenses</title>
<link rel="stylesheet" href="style.css">
<script type="text/javascript" src="script.js"></script>
<script>

let currentview = 'expenses';

function switchView() {
    if(currentview == "expenses") {
        document.getElementById("expensesView").style = "display: none;";
        document.getElementById("loansView").style = "display: block;";
        currentview = "loans";
        loadLoans(<?php echo $_SESSION['user_id'] ?>, true);
    } else {
        document.getElementById("expensesView").style = "display: block;";
        document.getElementById("loansView").style = "display: none;";
        currentview = "expenses";
        loadExpenses(<?php echo $_SESSION['user_id'] ?>, true);
    }
}

function selectAllRows() {
    var checkboxes = document.getElementsByClassName(currentview + 'SelectRow');
    for (let i = 0; i < checkboxes.length; i++) {
        checkboxes.item(i).checked = true;
    }
}

function getCurrentView() {
    return currentview;
}

function getSelectedRows() {
    var res = [];
    var checkboxes = document.getElementsByClassName(currentview + 'SelectRow');
    for (let i = 0; i < checkboxes.length; i++) {
        if(checkboxes.item(i).checked){
            res.push(checkboxes.item(i).value);
        }
    }
    return res;
}


<?php if (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
    window.onload = loadUsers();
<?php endif; ?>

<?php if (!empty($_SESSION['role']) && $_SESSION['role'] === 'user'): ?>
    window.onload = loadExpenses(<?php echo $_SESSION['user_id'] ?>, true);
    window.onload = loadSettlements();
<?php endif; ?>
</script>
</head>

<body>
<h2>Welcome to diconto, <?php echo $_SESSION['username']; ?></h2>

<a href="logout.php">Logout</a>

<br/><br/>

<?php if (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
    <h3>Users</h3>
    <div id="usersDiv">Loading...</div>

    <h3>Add User</h3>
    <input type="text" id="username" placeholder="Username" required>
    <input type="password" id="password" placeholder="Password" required>
    <select name="role" id="role">
        <option value="user">User</option>
        <option value="admin">Admin</option>
    </select>
    <button onclick="addUser()">Add User</button>
<?php endif; ?>

<?php if (!empty($_SESSION['role']) && $_SESSION['role'] === 'user'): ?>
    
    
    <button onclick="switchView()">Switch View</button>

    <div id="settlementView">
        <div id="settlementsContainer">
            <div id="settlementsDiv">Loading...</div>

            <input type="date" id="settlementdate" placeholder="Date">
            <button onclick="addSettlement()">Add new settlement</button>
        </div>
    </div>

    <div id="expensesView" style="display: block;">

    <h3>Expenses</h3>
    
    <select name="expensesPeriod" id="expensesPeriod">
        <option value="last_settlement" selected>From last conto</option>
        <option value="all">All</option>
    </select>

    <button onclick="loadExpenses(<?php echo $_SESSION['user_id'] ?>, true)">Reload</button>
    
    <button onclick="selectAllRows()">Select All Rows</button>

    <div class="expensesContainer">

    <div id="expensesDiv">Loading...</div>

    <?php if ($otherusers >= 1): ?>
    <div id="otheruserexpensesContainerDiv">
        
        <h4>Other's Expenses</h4>
        <select name="otheruser" id="otheruser">
        <?php 
            foreach($otheruserslist as $otheruser){
                echo "<option value='{$otheruser['id']}'>{$otheruser['username']}</option>";
            }
        ?>
        </select>
        <button onclick="loadOtherExpenses()">Load Other Expenses</button>
        
        <br/>

        <div id="otheruserexpensesDiv"></div>
        
    </div>
    <?php endif; ?>

    </div>

    <h3>Add Expense</h3>
    <input type="text" id="label" placeholder="Label">
    <input type="number" step="0.01" id="amount" placeholder="Amount">
    <input type="date" id="date" placeholder="Date">
    <button onclick="addExpense(<?php echo $_SESSION['user_id'] ?>)">Enter</button>

    </div>


    <div id="loansView" style="display: none;">
    <h3>Loans</h3>
    
    <select name="loansPeriod" id="loansPeriod">
        <option value="last_settlement" selected>From last conto</option>
        <option value="all">All</option>
    </select>

    <select name="otheruser" id="selectuserb">
        <option value='0'>All</option>
    <?php 
        foreach($otheruserslist as $otheruser){
            echo "<option value='{$otheruser['id']}'>{$otheruser['username']}</option>";
        }
    ?>
    </select>

    <button onclick="loadLoans(<?php echo $_SESSION['user_id'] ?>, true)">Load Loans</button>

    <button onclick="selectAllRows()">Select All Rows</button>

    <div class="loansContainer">

    <div id="loansDiv"></div>

    </div>

    <?php if ($otherusers >= 1): ?>
    <div id="addloanContainerDiv">
        <h3>Add Loan</h3>
        <input type="text" id="loanlabel" placeholder="Label">
        <input type="number" step="0.01" id="loanamount" placeholder="Amount">
        <input type="date" id="loandate" placeholder="Date">
        <select name="otheruser" id="loanuserb">
        <?php 
            foreach($otheruserslist as $otheruser){
                echo "<option value='{$otheruser['id']}'>{$otheruser['username']}</option>";
            }
        ?>
        </select>
        <button onclick="addLoan(<?php echo $_SESSION['user_id'] ?>)">Enter</button>
    </div>

    <?php endif; ?>
    </div>

<?php endif; ?>
</body>
</html>