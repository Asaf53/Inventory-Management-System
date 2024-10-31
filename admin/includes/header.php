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

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600">
    <link rel="stylesheet" href="css/fontawesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/tooplate.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.22.4/dist/bootstrap-table.min.css">
</head>


<body id="reportsPage">
    <?php if (isset($_SESSION['role']) && ($_SESSION['role'] !== 'admin')) {
        header('Location: error.html');
        die();
    } else { ?>
        <div class="" id="home">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <nav class="navbar navbar-expand-xl navbar-light bg-light">
                            <a class="navbar-brand" href="index.php">
                                <h1 class="tm-site-title mb-0">Dashboard</h1>
                            </a>
                            <button class="navbar-toggler ml-auto mr-0" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>

                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul class="navbar-nav mx-auto">
                                    <li class="nav-item">
                                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" href="index.php" href="index.php">Dashboard</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'product.php' ? 'active' : ''; ?>" href="product.php" href="cars.php">Product / Type</a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'accounts.php' ? 'active' : ''; ?>" href="accounts.php" href="accounts.php">Accounts</a>
                                    </li>
                                </ul>
                                <ul class="navbar-nav">
                                    <li class="nav-item">
                                        <a class="nav-link d-flex" href="?csrf_token=<?php echo $_SESSION['csrf_token']; ?>">
                                            <i class="far fa-user mr-2 tm-logout-icon"></i>
                                            <span>Logout</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
            <?php }; ?>