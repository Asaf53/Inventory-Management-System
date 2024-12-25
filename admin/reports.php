<?php include_once('includes/header.php');

// Base SQL query
$sql_sales = "SELECT * 
               FROM `sales` 
               INNER JOIN `products` ON `sales`.`product_id` = `products`.`id`";

// Add sorting based on the 'type' parameter
if (isset($_GET['type'])) {
    switch ($_GET['type']) {
        case 'Day':
            $sql_sales .= " WHERE DATE(`sales`.`sale_date`) = CURDATE()";
            break;
        case 'Month':
            $sql_sales .= " WHERE MONTH(`sales`.`sale_date`) = MONTH(CURDATE()) AND YEAR(`sales`.`sale_date`) = YEAR(CURDATE())";
            break;
        case 'Year':
            $sql_sales .= " WHERE YEAR(`sales`.`sale_date`) = YEAR(CURDATE())";
            break;
    }
}

$stm_sales = $pdo->prepare($sql_sales);
$stm_sales->execute();
$sales = $stm_sales->fetchAll(PDO::FETCH_ASSOC);
?>

<a href="index.php" class="btn btn-transparent d-flex-inline justify-content-start align-items-center"><img src="./assets/icons/back.svg" alt="">Back</a>
<div class="container-fluid mt-4">
    <div class="text-start">
        <h3 class="fw-bold">Reports</h3>
    </div>

    <div class="m-4">
        <ul class="nav nav-pills nav-justified col-12 col-md-6 m-auto">
            <li class="nav-item">
                <a class="nav-link <?= !isset($_GET['type']) || $_GET['type'] === 'all' ? 'active bg-primary' : '' ?>" href="?type=all">All</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= isset($_GET['type']) && $_GET['type'] === 'Day' ? 'active bg-primary' : '' ?>" href="?type=Day">Day</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= isset($_GET['type']) && $_GET['type'] === 'Month' ? 'active bg-primary' : '' ?>" href="?type=Month">Month</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= isset($_GET['type']) && $_GET['type'] === 'Year' ? 'active bg-primary' : '' ?>" href="?type=Year">Year</a>
            </li>
        </ul>
    </div>
    <div class="col-12">
        <?php foreach ($sales as $sale) : ?>
            <div class="card bg-white border-0 rounded-0 border-bottom">
                <div class="card-body d-flex justify-content-between">
                    <div class="d-flex flex-column">
                        <!-- <div class=""> -->
                            <h4><?= $sale['name'] ?> - <?= $sale['length'] ?></h4>
                            <p class="card-text text-secondary mb-0 fw-medium">from <?= $sale['sale_date'] ?></p>
                        <!-- </div> -->
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