<?php
session_start();
include ('utils/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($id, $hashed, $role);

    if ($stmt->fetch() && hash("sha256", $password) === $hashed) {
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid credentials.";
    }
    $stmt->close();
}


$user_list = [];
$result = $conn->query("SELECT username, role FROM users ORDER BY username ASC");
while ($row = $result->fetch_assoc()) {
    $user_list[] = $row;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <script type="text/javascript" src="script.js"></script>
</head>

<body>
<h2>Login</h2>
<?php if (!empty($error)) echo "<p style='color:red'>$error</p>"; ?>

<div class="userList">
    <h3>Registered Users</h3>
    <table class="usersTable">
        <tr><th>Username</th><th>Role</th></tr>
        <?php foreach ($user_list as $u): ?>
            <tr>
                <td onclick="selectUser('<?php echo htmlspecialchars($u['username']); ?>')">
                    <u><?php echo htmlspecialchars($u['username']); ?></u>
                </td>
                <td><?php echo htmlspecialchars($u['role']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<div id="passwordForm" class="hidden">
    <h3>Welcome, <span id="usernameDisplay"></span></h3>
    <form method="POST">
        <input type="hidden" id="selectedUser" name="username">
        <input type="password" id="password" name="password" placeholder="Password" required><br><br>
        <button type="submit">Login</button>
        <button type="button" onclick="backToUserList()">Clear</button>
    </form>
</div>

</body>

</html>
