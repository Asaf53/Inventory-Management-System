<?php include_once('includes/header.php');

// Get the type parameter from the URL
$type = isset($_GET['type']) ? $_GET['type'] : 'all';

// Base SQL query
$sql_orders = "SELECT *, `transactions`.`id` AS `transactions_id` 
               FROM `transactions` 
               INNER JOIN `products` ON `transactions`.`product_id` = `products`.`id`";

// Add condition based on type
if ($type === 'incoming') {
    $sql_orders .= " WHERE `transactions`.`type` = 'incoming'";
} elseif ($type === 'outgoing') {
    $sql_orders .= " WHERE `transactions`.`type` = 'outgoing'";
}

// Add ordering
$sql_orders .= " ORDER BY `transactions`.`date` DESC";

$stm_orders = $pdo->prepare($sql_orders);
$stm_orders->execute();
$orders = $stm_orders->fetchAll(PDO::FETCH_ASSOC);
?>

<a href="index.php" class="btn btn-transparent d-flex-inline justify-content-start align-items-center"><img src="./assets/icons/back.svg" alt="">Back</a>
<div class="container-fluid mt-4">
    <div class="m-4">
        <ul class="nav nav-pills nav-justified col-12 col-md-6 m-auto">
            <li class="nav-item">
                <a class="nav-link <?= !isset($_GET['type']) || $_GET['type'] === 'all' ? 'active bg-primary' : '' ?>" href="?type=all">All</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= isset($_GET['type']) && $_GET['type'] === 'incoming' ? 'active bg-primary' : '' ?>" href="?type=incoming">Incoming</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= isset($_GET['type']) && $_GET['type'] === 'outgoing' ? 'active bg-primary' : '' ?>" href="?type=outgoing">Outgoing</a>
            </li>
        </ul>
    </div>
    <div class="text-start">
        <h3 class="fw-bold">Recent Documents</h3>
    </div>
    <div class="col-12">
        <?php foreach ($orders as $order) : ?>
            <div class="card bg-white border-0 rounded-0 border-bottom">
                <div class="card-body d-flex justify-content-between">
                    <div class="d-flex">
                        <span class="w-10 h-100 bg-<?= htmlspecialchars($order['type']) === 'incoming' ? 'success' : 'danger' ?>"></span>
                        <div class="ms-2">
                            <!-- <a href="#" class="link-primary text-decoration-none card-title"> -->
                                <!-- <h4><?= $order['type'] ?> №<?= $order['transactions_id'] ?></h4> -->
                                <h4><?= $order['name'] ?> - <?= $order['length'] ?></h4>
                            <!-- </a> -->
                            <p class="card-text text-secondary mb-0 fw-medium">from <?= $order['date'] ?></p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center align-items-center">
                        <h3 class="text-secondary"><?= $order['quantity'] ?></h3>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<script src="assets/js/jquery-1.11.0.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.5/dist/bootstrap-table.min.js"></script>
</body>

</html>