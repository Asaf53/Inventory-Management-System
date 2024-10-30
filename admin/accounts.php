<?php 
include_once('includes/header.php');


$u = null;
if (isset($_GET['user_id']) && filter_var($_GET['user_id'], FILTER_VALIDATE_INT)) {
    $userId = (int)$_GET['user_id'];
    $sql_user = "SELECT * FROM `user` WHERE id = ?";
    $stm_user = $pdo->prepare($sql_user);
    $stm_user->execute([$userId]);
    $u = $stm_user->fetch(PDO::FETCH_ASSOC);  // Fetch single user directly
}

// Process Update Request
if (isset($_POST['edit_btn'], $_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
    if (isset($_POST['user_id']) && filter_var($_POST['user_id'], FILTER_VALIDATE_INT)) {
        $user_id = (int)$_POST['user_id'];
        $fullname = $_POST['fullname'];
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ? $_POST['email'] : null;
        $phone = $_POST['phone'];
        $role = $_POST['role'];

        if ($email) {  // Only proceed if email is valid
            $sql_edit_user = "UPDATE `user` SET `fullname` = ?, `email` = ?, `phone` = ?, `role` = ? WHERE id = ?";
            $stm_edit_user = $pdo->prepare($sql_edit_user);
            if ($stm_edit_user->execute([$fullname, $email, $phone, $role, $user_id])) {
                header('Location: accounts.php?action=user_edit');
                exit;
            }
        } else {
            $error_message = "Invalid email address.";
        }
    }
}

// Process Delete Request
if (isset($_POST['delete_btn'], $_POST['user_id'], $_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
    $user_id = (int)$_POST['user_id'];
    
    if (filter_var($user_id, FILTER_VALIDATE_INT)) {
        $sql_delete_user = "DELETE FROM `user` WHERE id = ?";
        $stm_delete_user = $pdo->prepare($sql_delete_user);
        
        if ($stm_delete_user->execute([$user_id])) {
            header('Location: accounts.php?action=user_delete');
            exit;
        } else {
            header('Location: accounts.php?action=user_delete_fail');
        }
    }
}
// Fetch All Users
$sql_users = "SELECT * FROM `user`";
$stm_users = $pdo->prepare($sql_users);
$stm_users->execute();
$users = $stm_users->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Row -->
<?php
if (isset($_GET['action'])) {
    $alerts = [
        'user_delete' => 'User deleted successfully',
        'user_edit' => 'User edited successfully',
        'user_delete_fail' => 'User delete fail',

    ];
    $alert = $alerts[$_GET['action']] ?? null;
}
?>
<?php if (!empty($alert)) : ?>
    <div class="alert alert-info alert-dismissible fade show mt-3" role="alert">
        <?= htmlspecialchars($alert) ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<div class="row tm-content-row tm-mt-big mt-3">
    <div class="tm-col tm-col-big col-md-7 col-12">
        <div class="bg-white tm-block">
            <div class="row">
                <div class="col-12">
                    <h2 class="tm-block-title d-inline-block">Accounts</h2>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mt-3">
                    <thead>
                        <tr class="tm-bg-gray">
                            <th scope="col">No.</th>
                            <th scope="col">Fullname</th>
                            <th scope="col">Email</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $i => $user) : ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td class="tm-user-name"><?= htmlspecialchars($user['fullname']) ?></td>
                                <td class="d-none"><?= htmlspecialchars($user['id']) ?></td>
                                <td class="tm-user-name"><?= htmlspecialchars($user['email']) ?></td>
                                <td class="tm-user-name"><?= htmlspecialchars($user['phone']) ?></td>
                                <td class="text-center">
                                    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" style="display:inline;">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                        <button type="submit" name="delete_btn" class="btn btn-link p-0" onclick="return confirm('Are you sure you want to delete this user?');">
                                            <i class="fas fa-trash-alt tm-trash-icon"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="tm-col tm-col-big col-md-5 col-12">
        <div class="bg-white tm-block">
            <div class="row">
                <div class="col-12">
                    <h2 class="tm-block-title">Edit Account</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST" class="tm-signup-form">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <?php if ($u) : ?>
                            <div class="form-group">
                                <label for="name">Account Name</label>
                                <input value="<?= htmlspecialchars($u['fullname']) ?>" id="name" name="fullname" type="text" class="form-control validate">
                                <input value="<?= htmlspecialchars($u['id']) ?>" name="user_id" type="hidden">
                            </div>
                            <div class="form-group">
                                <label for="email">Account Email</label>
                                <input value="<?= htmlspecialchars($u['email']) ?>" id="email" name="email" type="email" class="form-control validate">
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input value="<?= htmlspecialchars($u['phone']) ?>" id="phone" name="phone" type="tel" class="form-control validate">
                            </div>
                            <div class="form-group">
                                <label for="role">Account Role</label>
                                <select name="role" id="role" class="custom-select">
                                    <option value="<?= htmlspecialchars($u['role']) ?>" selected><?= htmlspecialchars($u['role']) ?></option>
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-5 col-md-4">
                                    <button class="btn btn-primary" name="edit_btn">Update</button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>
    $(function() {
        $('.tm-user-name').on('click', function() {
            var userId = $(this).siblings('.d-none').text();
            localStorage.setItem('selectedUserId', userId);
            var itemId = localStorage.getItem('selectedUserId');
            var url = "accounts.php?user_id=" + itemId;
            window.location.href = url;
        });
    });
</script>
</body>
</html>
