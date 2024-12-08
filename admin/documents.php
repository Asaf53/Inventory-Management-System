<?php include_once('includes/header.php');

$sql_orders = "SELECT *, `transactions`.`id` AS `transactions_id` FROM `transactions` INNER JOIN `products` ON `transactions`.`product_id` = `products`.`id`;";
$stm_orders = $pdo->prepare($sql_orders);
$stm_orders->execute();
$orders = $stm_orders->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- row -->
<?php
if (isset($_GET['action'])) {
    $alerts = [
        'order_add' => 'Order added successfully',
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

<button onclick="history.back()" class="btn btn-transparent d-flex justify-content-between align-items-center"><img src="./assets/icons/back.svg" alt=""> Back</button>
<div class="container-fluid mt-4">
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
                            <a href="#" class="link-primary text-decoration-none card-title">
                                <h4><?= $order['type'] ?> №<?= $order['transactions_id'] ?></h4>
                            </a>
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