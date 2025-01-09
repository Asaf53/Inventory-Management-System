<?php include_once('includes/header.php');

// $sql_categories = "
// SELECT `categories`.`name` AS category_name, `categories`.`color` AS category_color, `categories`.`id` AS category_id, COUNT(`products`.`id`) AS product_count
// FROM  `categories`
// LEFT JOIN `products` ON 
// `categories`.`id` = `products`.`category_id`
// GROUP BY `categories`.`id`, `categories`.`name`
// ";
$sql_companies = "SELECT * FROM `company`";
$stm_companies = $pdo->prepare($sql_companies);
$stm_companies->execute();
$companies = $stm_companies->fetchAll(PDO::FETCH_ASSOC);


$company_errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['company_btn'])) {
    $company_name = $_POST['company_name'];
    $company_color = $_POST['company_color'];

    if (empty($company_name)) {
        $company_errors[] = "Please fill in all the fields.";
    }

    if (empty($company_errors)) {
        $insertCompanySql = "INSERT INTO `company` (`name`, `color`) VALUES (?, ?)";
        $companyStmt = $pdo->prepare($insertCompanySql);
        $companyStmt->execute([$company_name, $company_color]);
        header("Location: company.php?action=add_company&status=success");
    } else {
        $company_errors[] = "Error adding company.";
        header("Location: company.php?action=add_company&status=error");
    }
}

if (isset($_GET['action']) && isset($_GET['status'])) {
    $alerts = [
        'add_company' => [
            'success' => 'Company Added successfully!',
            'error' => 'Failed to Add Company.',
        ],
        'delete_company' => [
            'success' => 'Company Deleted successfully!',
            'error' => 'Failed to Delete Company.',
        ],
    ];

    // Get the appropriate alert based on action and status
    $action = $_GET['action'];
    $status = $_GET['status'];

    $alert = $alerts[$action][$status] ?? null;
}
?>
<!-- Row -->
<a href="index.php" class="btn btn-transparent d-flex-inline justify-content-start align-items-center"><img src="./assets/icons/back.svg" alt="">Back</a>
<div class="container-fluid">
    <?php if (!empty($alert)) : ?>
        <div class="alert alert-<?= htmlspecialchars($status) === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show mt-3" role="alert">
            <strong class="text-uppercase me-1"><?= htmlspecialchars($status) ?>!</strong><?= htmlspecialchars($alert) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <div class="row mt-4 col-12 justify-content-start p-0 m-0">
        <div class="col-12 d-flex justify-content-start mb-2">
            <button data-bs-toggle="modal" data-bs-target="#company" class="btn btn-small btn-primary d-flex justify-content-between align-items-center">
                <img src="./assets/icons/company-plus.svg" alt="" class="me-1">Add New Company</button>
        </div>
        <?php foreach ($companies as $company): ?>
            <div class="col-12 col-md-4 col-xl-4 mb-2">
                <a href="categories.php?company_id=<?= $company['id'] ?>" class="text-decoration-none">
                    <div class="card" style="background-color: <?= $company['color'] ?>">
                        <div class="card-body text-center">
                            <h5 class="card-title"><img src="./assets/icons/company.svg" alt=""></h5>
                            <h5 class="card-title text-white"><?= $company['name'] ?></h5>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>


<div class="modal fade" id="company" tabindex="-1" aria-labelledby="companyLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="companyLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="company_name" class="form-label">Company Name</label>
                        <input type="text" name="company_name" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="company_color" class="form-label">Company Color</label>
                        <input type="color" name="company_color" class="form-control form-control-color">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="company_btn" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="assets/js/jquery-1.11.0.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.5/dist/bootstrap-table.min.js"></script>
</body>

</html>