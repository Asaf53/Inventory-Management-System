<?php
include_once('includes/database.php');
include_once('includes/header.php'); // Ensure database connection

// Redirect if already logged in
if (isset($_SESSION['is_loggedin']) && $_SESSION['role'] === 'admin') {
    header('Location: /admin/index.php');
    exit();
}

// Process login
$login_errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_btn'])) {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $login_errors[] = "Please enter a valid email.";
    }
    if (empty($password)) {
        $login_errors[] = "Password is required.";
    }

    if (empty($login_errors)) {
        try {
            $sql = "SELECT * FROM `user` WHERE email = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['is_loggedin'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['fullname'] = $user['fullname'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

                // Generate a secure persistent login token
                $token = bin2hex(random_bytes(32));
                setcookie('login_token', $token, time() + (30 * 24 * 60 * 60), "/", "", false, true);

                // Store hashed token in database
                $hashedToken = password_hash($token, PASSWORD_DEFAULT);
                $pdo->prepare("UPDATE `user` SET login_token = ? WHERE id = ?")
                    ->execute([$hashedToken, $user['id']]);

                header('Location: ' . ($user['role'] === 'admin' ? 'admin/index.php' : 'index.php'));
                exit();
            } else {
                $login_errors[] = "Invalid email or password.";
            }
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            $login_errors[] = "An error occurred. Please try again later.";
        }
    }
}

// Auto-login via persistent token
if (!isset($_SESSION['is_loggedin']) && isset($_COOKIE['login_token'])) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM `user` WHERE login_token IS NOT NULL");
        $stmt->execute();
        while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (password_verify($_COOKIE['login_token'], $user['login_token'])) {
                $_SESSION['is_loggedin'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

                header('Location: ' . ($user['role'] === 'admin' ? 'admin/index.php' : 'index.php'));
                exit();
            }
        }
    } catch (PDOException $e) {
        error_log("Auto-login error: " . $e->getMessage());
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
            <!-- <a class="text-secondary-emphasis" href="register.php">Sign in</a> -->
        </form>
    </main>
</div>
<?php
include_once('includes/footer.php');
?>