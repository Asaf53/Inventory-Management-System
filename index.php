<?php
include_once('includes/header.php');

if (isset($_SESSION['is_loggedin']) && $_SESSION['role'] === 'admin') {
    header('Location: /admin/index.php');
    exit();
}

if (isset($_SESSION['is_loggedin']) || $_SESSION['role'] !== 'admin') {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit();
}

$login_errors = [];
if (isset($_POST['login_btn'])) {
    // Sanitize and trim input
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validate email and password
    if (empty($email)) {
        $login_errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $login_errors[] = "Please enter a valid email address.";
    }

    if (empty($password)) {
        $login_errors[] = "Password is required.";
    }

    // Proceed only if there are no validation errors
    if (count($login_errors) === 0) {
        try {
            $sql = "SELECT * FROM `user` WHERE email = ?";
            $stm = $pdo->prepare($sql);
            $stm->setFetchMode(PDO::FETCH_ASSOC); // Fetch as an associative array
            
            if ($stm->execute([$email])) {
                $user = $stm->fetch();

                // Verify user exists and password is correct
                if ($user && password_verify($password, $user['password'])) {
                    session_start(); // Ensure session is started
                    $_SESSION['is_loggedin'] = true;
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['fullname'] = $user['fullname'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

                    // Generate a persistent login token
                    $token = bin2hex(random_bytes(32));

                    // Set cookie for 30 days
                    setcookie('login_token', $token, time() + (30 * 24 * 60 * 60), "/", "", false, true);

                    // Save the token in the database
                    $hashedToken = password_hash($token, PASSWORD_DEFAULT);
                    $updateSql = "UPDATE `user` SET login_token = ? WHERE id = ?";
                    $updateStmt = $pdo->prepare($updateSql);
                    $updateStmt->execute([$hashedToken, $user['id']]);

                    // Redirect based on role
                    if ($user['role'] === 'admin') {
                        header('Location: admin/index.php');
                    } else {
                        header('Location: index.php?action=login');
                    }
                    exit(); // Stop script execution after redirect
                } else {
                    $login_errors[] = "Invalid email or password.";
                }
            } else {
                $login_errors[] = "An error occurred while processing your request. Please try again later.";
            }
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            $login_errors[] = "An internal server error occurred. Please try again later.";
        }
    }
}

// Check for persistent login
if (!isset($_SESSION['is_loggedin']) && isset($_COOKIE['login_token'])) {
    $token = $_COOKIE['login_token'];

    try {
        $sql = "SELECT id, login_token, role FROM `user` WHERE login_token IS NOT NULL";
        $stm = $pdo->prepare($sql);
        $stm->setFetchMode(PDO::FETCH_ASSOC);
        $stm->execute();

        while ($user = $stm->fetch()) {
            if (password_verify($token, $user['login_token'])) {
                session_start(); // Start session if not already started
                $_SESSION['is_loggedin'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['fullname'] = $user['fullname'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

                // Redirect based on role
                if ($user['role'] === 'admin') {
                    header('Location: admin/index.php');
                } else {
                    header('Location: index.php?action=login');
                }
                exit();
            }
        }
    } catch (PDOException $e) {
        error_log("Persistent login error: " . $e->getMessage());
    }
}


if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'register':
            $alert = 'Thank you for signing up! Please login with your new credentials.';
            break;
    }
}
?>
<div class="container col-12 col-md-4 my-5">
    <?php if (isset($_GET['action'])) : ?>
    <div class="alert alert-<?= ($_GET['type'] === 'warning') ? 'warning' : 'success'  ?> alert-dismissible fade show mt-3"
        role="alert">
        <?= $alert; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>
    <main class="form-signin w-100 m-auto">
        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
            <?php if (count($login_errors) > 0) : ?>
            <ul class="list-group m-3">
                <?php foreach ($login_errors as $error) : ?>
                <li class="text-danger"><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
            <h4 class="text-center">Login</h4>
            <div class="form-floating mb-2">
                <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com"
                    autocomplete="false">
                <label for="email">Email address</label>
            </div>
            <div class="form-floating mb-2">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password"
                    autocomplete="false">
                <label for="password">Password</label>
            </div>
            <button class="btn border-outline-primary w-100 py-2 mb-2" name="login_btn" type="submit">Log in</button>
            <a class="text-secondary-emphasis" href="register.php">Sign in</a>
        </form>
    </main>
</div>
<?php
include_once('includes/footer.php');
?>