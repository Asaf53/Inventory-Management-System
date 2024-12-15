<?php include_once('includes/header.php');

$sql_orders = "SELECT *, `transactions`.`id` AS `transactions_id` FROM `transactions` INNER JOIN `products` ON `transactions`.`product_id` = `products`.`id`";
$stm_orders = $pdo->prepare($sql_orders);
$stm_orders->execute();
$orders = $stm_orders->fetchAll(PDO::FETCH_ASSOC);
?>

<a href="index.php" class="btn btn-transparent d-flex-inline justify-content-start align-items-center"><img src="./assets/icons/back.svg" alt="">Back</a>
<div class="container-fluid mt-4">
    <div class="text-start">
        <h3 class="fw-bold">Reports</h3>
    </div>
    <div class="col-12">
        <div class="card bg-white shadow mb-2">
            <a href="#" class="text-decoration-none">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <h4 class="m-0">Sales by Month</h4>
                    <h3 class="text-secondary d-flex justify-content-center align-items-center m-0">View <img src="./assets/icons/reports-view.svg" alt=""></h3>
                </div>
            </a>
        </div>
        <div class="card bg-white shadow mb-2">
            <a href="#" class="text-decoration-none">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <h4 class="m-0">Sales by Date</h4>
                    <h3 class="text-secondary d-flex justify-content-center align-items-center m-0">View <img src="./assets/icons/reports-view.svg" alt=""></h3>
                </div>
            </a>
        </div>
    </div>
</div>
<script src="assets/js/jquery-1.11.0.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.5/dist/bootstrap-table.min.js"></script>
</body>

</html>