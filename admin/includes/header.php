<?php
session_start();
include_once('database.php'); // Ensure DB connection

// Persistent login handling
if (!isset($_SESSION['is_loggedin']) && isset($_COOKIE['login_token'])) {
    try {
        $stmt = $pdo->prepare("SELECT id, role, login_token FROM `user` WHERE login_token IS NOT NULL");
        $stmt->execute();

        while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (password_verify($_COOKIE['login_token'], $user['login_token'])) {
                $_SESSION['is_loggedin'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                break;
            }
        }
    } catch (PDOException $e) {
        error_log("Persistent login error: " . $e->getMessage());
    }
}

// Redirect if not admin
if (!isset($_SESSION['is_loggedin']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

// Logout handling
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'logout') {
    if (isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        setcookie('login_token', '', time() - 3600, "/", "", true, true);
        session_unset();
        session_destroy();
        header("Location: ../index.php?logout=success");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/boxicons.min.css">
    <link rel="stylesheet" href="assets/css/daterangepicker.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.5/dist/bootstrap-table.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-primary">
        <div class="container-fluid py-2">
            <a class="navbar-brand text-light" href="index.php">Dashboard</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav m-auto">
                    <li class="nav-item">
                        <a class="nav-link text-light <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" href="index.php">Home</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <form action="" method="POST">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                            <input type="hidden" name="action" value="logout">
                            <button type="submit" class="btn btn-outline-light">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</body>
</html>
