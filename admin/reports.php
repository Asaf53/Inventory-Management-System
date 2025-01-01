<?php include_once('includes/header.php');

// Base SQL query
// $sql_sales = "SELECT `sales`.`quantity`, `sales`.`sale_date`, `products`.`length`, `products`.`name` FROM `sales` INNER JOIN `products` ON `sales`.`product_id` = `products`.`id`";
$sql_sales = "SELECT `sales`.`quantity`, `sales`.`sale_date`, `products`.`length`, `categories`.`name` FROM `sales` INNER JOIN `products` ON `sales`.`product_id` = `products`.`id` INNER JOIN `categories` ON `products`.`category_id` = `categories`.`id`";

// Add filtering based on the 'daterange' parameter
if (isset($_GET['daterange'])) {
    // Extract start and end dates from the 'daterange' parameter
    $daterange = $_GET['daterange'];
    list($start_date, $end_date) = explode(' - ', $daterange);

    // Convert dates to Y-m-d format (compatible with SQL)
    $start_date = DateTime::createFromFormat('m/d/Y', trim($start_date))->format('Y-m-d');
    $end_date = DateTime::createFromFormat('m/d/Y', trim($end_date))->format('Y-m-d');

    // Append WHERE clause to filter by the date range
    $sql_sales .= " WHERE `sales`.`sale_date` BETWEEN :start_date AND :end_date";
}

// Prepare the SQL statement
$stm_sales = $pdo->prepare($sql_sales);

// Bind parameters if they are set
if (isset($start_date) && isset($end_date)) {
    $stm_sales->bindParam(':start_date', $start_date);
    $stm_sales->bindParam(':end_date', $end_date);
}

// Execute the query and fetch results
$stm_sales->execute();
$sales = $stm_sales->fetchAll(PDO::FETCH_ASSOC);
?>

<a href="index.php" class="btn btn-transparent d-flex-inline justify-content-start align-items-center"><img src="./assets/icons/back.svg" alt="">Back</a>
<div class="container-fluid mt-4">
    <div class="text-start">
        <h3 class="fw-bold">Reports</h3>
    </div>

    <!-- Single Date Input Form -->
    <div class="my-4">
        <?php if (isset($start_date, $end_date)) : ?>
            <div class="d-flex align-items-center">
                <span class="fw-bold">Selected Date:</span>
                <p class="text-secondary mb-0 ms-2"><?= $start_date . " - " . $end_date ?></p>
            </div>
        <?php endif; ?>
        <form method="GET" action="<?php $_SERVER['PHP_SELF']; ?>">
            <div class="d-flex justify-content-center justify-content-md-start">
                <div class="form-floating">
                    <input type="text" name="daterange" class="form-control rounded-0 rounded-start" id="sort_date">
                    <label for="sort_date" class="form-label">Checkin / Checkout</label>
                </div>

                <button type="submit" class="btn btn-primary ms-2 rounded-0 rounded-end">Filter</button>
            </div>
        </form>
    </div>
    <!-- <div class="col-12 col-md-6 col-lg-3 mt-4 mt-lg-0">
        <div class="form-floating">
            <input type="date" class="form-control rounded-0" id="pickupDate" name="pickupDate">
            <label for="pickupDate">Pickup Date:</label>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3 mt-4 mt-lg-0">
        <div class="form-floating">
            <input type="date" class="form-control rounded-0" id="returnDate" name="returnDate">
            <label for="returnDate">Return Date:</label>
        </div>
    </div> -->



    <div class="col-12">
        <?php foreach ($sales as $sale) : ?>
            <div class="card bg-white border-0 rounded-0 border-bottom">
                <div class="card-body d-flex justify-content-between">
                    <div class="d-flex flex-column">
                        <h4><?= $sale['name'] ?> - <?= $sale['length'] ?></h4>
                        <p class="card-text text-secondary mb-0 fw-medium">from <?= $sale['sale_date'] ?></p>
                    </div>
                    <div class="d-flex justify-content-center align-items-center">
                        <h3 class="text-secondary"><?= $sale['quantity'] * $sale['length'] ?>m<sup>2</sup></h3>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<script src="assets/js/jquery-1.11.0.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.5/dist/bootstrap-table.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
    $('input[name="daterange"]').daterangepicker({
        showDropdowns: true,
    });
</script>
</body>

</html>