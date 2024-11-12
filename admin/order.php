<?php include_once('includes/header.php');

$sql_orders = "SELECT *, `orders`.`qty` AS `order_qty` FROM `orders` INNER JOIN `product` ON `orders`.`product_id` = `product`.`id`";
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

<div class="row tm-content-row mt-3">
    <div class="col-12">
        <div class="bg-white tm-block h-100">
            <div class="row">
                <div class="col-8">
                    <h2 class="tm-block-title d-inline-block">Order List</h2>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table align-middle overflow-scroll" data-search-align="left" id="table" data-pagination="true" data-toggle="table" data-search="true" data-searchable="true">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Product</th>
                            <th scope="col">Client Name</th>
                            <th scope="col">Client Phone</th>
                            <th scope="col">Qty</th>
                            <th scope="col">Total</th>
                            <th scope="col">Created at</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($orders as $i => $order) : ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= $order['name'] ?></td>
                                <td><?= $order['client_name'] ?></td>
                                <td><?= $order['client_phone'] ?></td>
                                <td><?= $order['order_qty'] ?></td>
                                <td><?= $order['price'] ?></td>
                                <td><?= $order['order_date'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.22.4/dist/bootstrap-table.min.js"></script>
</body>

</html>