<?php
include_once('includes/header.php');
$register_errors = [];

if (isset($_POST['register_btn'])) {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $repeatPassword = $_POST['repeatPassword'];

    if (empty($fullname) || empty($email) || empty($phone) || empty($password) || empty($repeatPassword)) {
        $register_errors[] = "Please fill in all the fields.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $register_errors[] = "Please enter a valid email address.";
    }

    if ($password !== $repeatPassword) {
        $register_errors[] = "Passwords do not match.";
    }

    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
    if (!preg_match($pattern, $password)) {
        $register_errors[] = "At least one uppercase letter <br> At least one lowercase letter<br> At least one digit <br> At least one special character <br> Minimum length 8 characters";
    }

    if (count($register_errors) === 0) {
        $sql = "INSERT INTO `user` (`fullname`, `email`, `phone`, `password`) VALUES (?, ?, ?, ?)";
        $stm = $pdo->prepare($sql);
        if ($stm->execute([$fullname, $email, $phone, password_hash($password, PASSWORD_BCRYPT)])) {
            header('Location: index.php?action=register&type=success');
        } else {
            $register_errors[] = "Something went wrong!!";
        }
    }
}
?>
<div class="container col-md-3 col-12 my-5">
    <main class="m-auto">
        <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
            <h4 class="text-center">Create Account</h4>
            <?php if (count($register_errors) > 0) : ?>
                <ul class="list-group m-3">
                    <?php foreach ($register_errors as $error) : ?>
                        <li class="text-danger"><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="fullname" name="fullname" placeholder="name@example.com">
                <label for="fullname">Fullname</label>
            </div>
            <div class="form-floating mb-2">
                <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com">
                <label for="email">Email address</label>
            </div>
            <div class="form-floating mb-2">
                <input type="tel" class="form-control" id="phone" name="phone" placeholder="name@example.com">
                <label for="phone">Phone</label>
            </div>
            <div class="form-floating mb-2">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                <label for="password">Password</label>
            </div>
            <div class="form-floating mt-2 mb-2">
                <input type="password" class="form-control" id="repeatPassword" name="repeatPassword" placeholder="Password">
                <label for="repeatPassword">Repeat Password</label>
            </div>
            <button class="btn border-outline-primary w-100 py-2 mb-2" name="register_btn" type="submit">Sign in</button>
            <a class="text-secondary-emphasis" href="login.php">Log in</a>
        </form>
    </main>
</div>
<?php
include_once('includes/footer.php');
?>