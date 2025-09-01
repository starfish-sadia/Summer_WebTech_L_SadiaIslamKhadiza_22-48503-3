<?php
session_start();

$message = "Not found";

if (!isset($_SESSION['email']) || !isset($_COOKIE['user'])) {
    echo "<h2>$message</h2>";
    exit();
}

$email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef2f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .dashboard-box {
            background: #fff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
            text-align: center;
            width: 350px;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        p {
            font-size: 16px;
            color: #555;
            margin-bottom: 30px;
        }
        a.logout-btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: #d9534f;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        a.logout-btn:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>
    <div class="dashboard-box">
        <h2>Welcome, Sadia</h2>
        <p>Cookie set for: <?php echo htmlspecialchars($_COOKIE['user']); ?></p>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</body>
</html>
