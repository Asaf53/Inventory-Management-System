<?php
session_start();
include_once('database.php');
if (isset($_GET['csrf_token']) && hash_equals($_SESSION['csrf_token'], $_GET['csrf_token'])) {
    unset($_SESSION['is_loggedin']);
    unset($_SESSION['user_id']);
    unset($_SESSION['email']);
    unset($_SESSION['fullname']);
    unset($_SESSION['csrf_token']);
    header("Location: ../index.php");
}
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    die();
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.5/dist/bootstrap-table.min.css">
</head>


<body>
    <?php if (isset($_SESSION['role']) && ($_SESSION['role'] !== 'admin')) {
        header('Location: error.html');
        die();
    } else { ?>
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
                        <a class="btn btn-outline-light d-flex align-items-center"
                            href="?csrf_token=<?php echo $_SESSION['csrf_token']; ?>">
                            <i class='bx bx-user me-1 h4 p-0 m-0'></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <?php }; ?>