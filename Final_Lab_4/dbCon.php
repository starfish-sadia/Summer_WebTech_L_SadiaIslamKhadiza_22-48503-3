<?php

$servername = "localhost";
$username = "root";    
$password = "";        
$dbname = "testdb";

$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if (!$conn->query($sql)) {
    die("Database creation failed: " . $conn->error);
}

$conn->select_db($dbname);

$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL
)";
if (!$conn->query($sql)) {
    die("Table creation failed: " . $conn->error);
}

if (isset($_POST['create'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    if ($name && $email) {
        $stmt = $conn->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $email);
        $stmt->execute();
        $stmt->close();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    if ($id && $name && $email) {
        $stmt = $conn->prepare("UPDATE users SET name=?, email=? WHERE id=?");
        $stmt->bind_param("ssi", $name, $email, $id);
        $stmt->execute();
        $stmt->close();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if ($id) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

$editUser = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    if ($id) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $editUser = $result->fetch_assoc();
        $stmt->close();
    }
}


$result = $conn->query("SELECT * FROM users");

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>DB CRUD</title>
<style>
    body { font-family: Arial, sans-serif; padding: 20px; }
    table { border-collapse: collapse; width: 600px; margin-top: 20px; }
    th, td { border: 1px solid #ccc; padding: 8px; }
    th { background: #eee; }
    form { margin-bottom: 20px; }
    input[type="text"], input[type="email"] { padding: 5px; width: 250px; }
    button { padding: 6px 12px; }
    a { text-decoration: none; color: blue; }
    a:hover { text-decoration: underline; }
</style>
</head>
<body>

<h2><?php echo $editUser ? "Edit User" : "Add New User"; ?></h2>

<form method="post" action="">
    <?php if ($editUser): ?>
        <input type="hidden" name="id" value="<?php echo $editUser['id']; ?>">
    <?php endif; ?>
    Name: <input type="text" name="name" required value="<?php echo $editUser ? htmlspecialchars($editUser['name']) : ''; ?>">
    Email: <input type="email" name="email" required value="<?php echo $editUser ? htmlspecialchars($editUser['email']) : ''; ?>">
    <button type="submit" name="<?php echo $editUser ? 'update' : 'create'; ?>">
        <?php echo $editUser ? 'Update' : 'Create'; ?>
    </button>
    <?php if ($editUser): ?>
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>">Cancel</a>
    <?php endif; ?>
</form>

<h2>User List</h2>
<table>
    <tr>
        <th>#</th><th>Name</th><th>Email</th><th>Actions</th>
    </tr>
    <?php
    $counter = 1; 
    if ($result->num_rows > 0):
        while ($row = $result->fetch_assoc()):
    ?>
        <tr>
            <td><?php echo $counter++; ?></td> 
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td>
                <a href="?edit=<?php echo $row['id']; ?>">Edit</a> |
                <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?');">Delete</a>
            </td>
        </tr>
    <?php
        endwhile;
    else:
    ?>
        <tr><td colspan="4">No users found.</td></tr>
    <?php endif; ?>
</table>

</body>
</html>

<?php
$conn->close();
?>
