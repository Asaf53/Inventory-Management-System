<?php
session_start();
include_once('database.php');

// Check for persistent login
if (!isset($_SESSION['is_loggedin']) && isset($_COOKIE['login_token'])) {
    $loginToken = $_COOKIE['login_token'];

    try {
        // Secure query to fetch user by login token
        $stmt = $conn->prepare("SELECT id, role, login_token FROM `user` WHERE login_token = ?");
        $stmt->bind_param("s", $loginToken);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            // Restore session variables
            $_SESSION['is_loggedin'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            if ($_SESSION['role'] === 'admin') {
                header('Location: /admin/index.php');
                exit();
            } else {
                header('Location: /admin/error.html');
                exit();
            }
        }
    } catch (Exception $e) {
        error_log("Error verifying login token: " . $e->getMessage());
    }

    // If no valid session or token, redirect to login page
    header('Location: ../../index.php');
    exit();
}

// Regular session-based role check for logged-in users
if (!isset($_SESSION['is_loggedin']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../index.php');
    exit();
}

// Handle logout request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'logout') {
    if (isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        // Securely clear login cookie
        if (isset($_COOKIE['login_token'])) {
            setcookie('login_token', '', time() - 3600, "/", "", true, true);
        }

        // Clear session variables
        session_unset();
        session_destroy();

        // Redirect to the homepage
        header("Location: ../index.php?logout=success");
        exit();
    } else {
        // Invalid CSRF token
        header("Location: ../index.php?error=csrf");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard Admin</title>

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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav m-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link text-light <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>"
                            href="index.php">Home</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">

                        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                            <input type="hidden" name="action" value="logout">
                            <button type="submit" class="btn btn-outline-light">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>