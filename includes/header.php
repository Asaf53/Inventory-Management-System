<?php
session_start();
include_once('database.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'logout') {
    if (isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        // Clear cookies (if login persistence was implemented)
        if (isset($_COOKIE['login_token'])) {
            setcookie('login_token', '', time() - 3600, "/");
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
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/boxicons.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">

    <link rel="shortcut icon" href="assets/img/logo/logo.png" type="image/png">
    <title>Titan Cink</title>
</head>

<body>
    <nav class="navbar navbar-expand-md px-lg-5 p-3 bg-white text-danger">
        <div class="container">
            <a class="navbar-brand" href="index.php">Titan Cink</a>
            <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-collapse collapse" id="navbarToggler">
                <div class="ms-auto">
                    <?php if (!isset($_SESSION['is_loggedin'])) : ?>
                        <ul class="navbar-nav mb-lg-0 d-flex flex-row align-items-center justify-content-center">
                            <li class="nav-item me-3"><a class="nav-link" href="index.php">Login</a></li>
                            <li class="nav-item"><a class="nav-link border border-outline-primary rounded-0 py-2 px-3"
                                    href="register.php">Sign In</a></li>
                        </ul>
                    <?php else : ?>
                        <div class="dropdown-center bg-white shadow-sm p-2 rounded-5">
                            <button class="btn btn-transparent m-0 p-0 border-0 d-flex align-items-center" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bx bx-user mb-0 pb-0 h3"></i>
                            </button>
                            <ul class="dropdown-menu mb-0 pb-0">
                                <li>
                                    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                                        <input type="hidden" name="action" value="logout">
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>