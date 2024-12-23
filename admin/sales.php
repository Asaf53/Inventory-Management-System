<?php include_once('includes/header.php');

// Base SQL query
$sql_sales = "SELECT * 
               FROM `sales` 
               INNER JOIN `products` ON `sales`.`product_id` = `products`.`id`";

$stm_sales = $pdo->prepare($sql_sales);
$stm_sales->execute();
$sales = $stm_sales->fetchAll(PDO::FETCH_ASSOC);
?>

<a href="index.php" class="btn btn-transparent d-flex-inline justify-content-start align-items-center"><img src="./assets/icons/back.svg" alt="">Back</a>
<div class="container-fluid mt-4">
    <div class="text-start">
        <h3 class="fw-bold">Recent Sales</h3>
    </div>
    <div class="col-12">
        <?php foreach ($sales as $sale) : ?>
            <div class="card bg-white border-0 rounded-0 border-bottom">
                <div class="card-body d-flex justify-content-between">
                    <div class="d-flex">
                        <div class="ms-2">
                            <!-- <a href="#" class="link-primary text-decoration-none card-title"> -->
                            <h4><?= $sale['name'] ?> - <?= $sale['length'] ?></h4>
                            <!-- </a> -->
                            <p class="card-text text-secondary mb-0 fw-medium">from <?= $sale['sale_date'] ?></p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center align-items-center">
                        <h3 class="text-secondary"><?= $sale['quantity'] ?></h3>
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