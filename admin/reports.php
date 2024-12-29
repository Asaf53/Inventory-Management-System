<?php include_once('includes/header.php');

// Base SQL query
$sql_sales = "SELECT * 
               FROM `sales` 
               INNER JOIN `products` ON `sales`.`product_id` = `products`.`id`";

// Add sorting based on the 'type' parameter
if (isset($_GET['type'])) {
    switch ($_GET['type']) {
        case 'Day':
            if (isset($_GET['date'])) {
                $date = $_GET['date'];
                $sql_sales .= " WHERE DATE(`sales`.`sale_date`) = :date";
            } else {
                $sql_sales .= " WHERE DATE(`sales`.`sale_date`) = CURDATE()";
            }
            break;
        case 'Month':
            if (isset($_GET['month']) && isset($_GET['year'])) {
                $month = $_GET['month'];
                $year = $_GET['year'];
                $sql_sales .= " WHERE MONTH(`sales`.`sale_date`) = :month AND YEAR(`sales`.`sale_date`) = :year";
            } else {
                $sql_sales .= " WHERE MONTH(`sales`.`sale_date`) = MONTH(CURDATE()) AND YEAR(`sales`.`sale_date`) = YEAR(CURDATE())";
            }
            break;
        case 'Year':
            if (isset($_GET['start_year']) && isset($_GET['end_year'])) {
                $start_year = $_GET['start_year'];
                $end_year = $_GET['end_year'];
                $sql_sales .= " WHERE YEAR(`sales`.`sale_date`) BETWEEN :start_year AND :end_year";
            } else {
                $sql_sales .= " WHERE YEAR(`sales`.`sale_date`) = YEAR(CURDATE())";
            }
            break;
    }
}

$stm_sales = $pdo->prepare($sql_sales);

// Bind parameters if they are set
if (isset($date)) {
    $stm_sales->bindParam(':date', $date);
}
if (isset($month) && isset($year)) {
    $stm_sales->bindParam(':month', $month);
    $stm_sales->bindParam(':year', $year);
}
if (isset($start_year) && isset($end_year)) {
    $stm_sales->bindParam(':start_year', $start_year);
    $stm_sales->bindParam(':end_year', $end_year);
}

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

    <!-- Date selection form -->
    <div class="m-4">
        <form method="GET" action="">
            <input type="hidden" name="type" value="<?= $_GET['type'] ?? 'all' ?>">
            <?php if ($_GET['type'] === 'Day'): ?>
                <div class="form-group">
                    <label for="date">Select Date:</label>
                    <input type="date" id="date" name="date" class="form-control">
                </div>
            <?php elseif ($_GET['type'] === 'Month'): ?>
                <div class="form-group">
                    <label for="month">Select Month:</label>
                    <input type="number" id="month" name="month" class="form-control" min="1" max="12">
                    <label for="year">Select Year:</label>
                    <input type="number" id="year" name="year" class="form-control" min="2000" max="<?= date('Y') ?>">
                </div>
            <?php elseif ($_GET['type'] === 'Year'): ?>
                <div class="form-group">
                    <label for="start_year">Start Year:</label>
                    <input type="number" id="start_year" name="start_year" class="form-control" min="2000" max="<?= date('Y') ?>">
                    <label for="end_year">End Year:</label>
                    <input type="number" id="end_year" name="end_year" class="form-control" min="2000" max="<?= date('Y') ?>">
                </div>
            <?php endif; ?>
            <button type="submit" class="btn btn-primary mt-2">Filter</button>
        </form>
    </div>

    <div class="col-12">
        <?php foreach ($sales as $sale) : ?>
            <div class="card bg-white border-0 rounded-0 border-bottom">
                <div class="card-body d-flex justify-content-between">
                    <div class="d-flex flex-column">
                        <h4><?= $sale['name'] ?> - <?= $sale['length'] ?></h4>
                        <p class="card-text text-secondary mb-0 fw-medium">from <?= $sale['sale_date'] ?></p>
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