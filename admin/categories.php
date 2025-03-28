<?php include_once('includes/header.php');

// Assuming $company_id is obtained from the URL parameters
$company_id = $_GET['company_id'] ?? null;

if ($company_id) {
    $sql_company = "SELECT `name`, `id`, `color` FROM `company` WHERE `id` = :company_id";
    $stmt_company = $pdo->prepare($sql_company);
    $stmt_company->execute(['company_id' => $company_id]);
    $company = $stmt_company->fetch(PDO::FETCH_ASSOC);
}

$sql_categories = "
SELECT `categories`.`name` AS category_name, 
       `categories`.`color` AS category_color, 
       `categories`.`id` AS category_id, 
       COUNT(`products`.`id`) AS product_count
FROM `categories`
LEFT JOIN `products` ON `categories`.`id` = `products`.`category_id`
WHERE `categories`.`company_id` = :company_id
GROUP BY `categories`.`id`, `categories`.`name`";

$stm_categories = $pdo->prepare($sql_categories);
$stm_categories->execute(['company_id' => $company_id]);
$categories = $stm_categories->fetchAll(PDO::FETCH_ASSOC);

$sql_product_m = "SELECT `products`.`length` AS `product_length`, `inventorysummary`.`current_qty` AS `product_qty` FROM `products` INNER JOIN `inventorysummary` ON `products`.`id` = `inventorysummary`.`product_id`";

$stm_product_m = $pdo->prepare($sql_product_m);
$stm_product_m->execute();
$product_m = $stm_product_m->fetchAll(PDO::FETCH_ASSOC);

print_r($product_m);

$categry_errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['category_btn'])) {
    $category_name = $_POST['category_name'];
    $category_color = $_POST['category_color'];
    $company_id = $_POST['company_id'];

    if (empty($category_name)) {
        $categry_errors[] = "Please fill in all the fields.";
    }

    if (empty($categry_errors)) {
        $insertCategorySql = "INSERT INTO `categories` (`name`, `color`, `company_id`) VALUES (?, ?, ?)";
        $categoryStmt = $pdo->prepare($insertCategorySql);
        $categoryStmt->execute([$category_name, $category_color, $company_id]);
        header("Location: categories.php?company_id=$company_id&action=add_category&status=success");
    } else {
        $categry_errors[] = "Error adding category.";
        header("Location: categories.php?company_id=$company_id&action=add_category&status=error");
    }
}

// Process Delete Company Request
if (isset($_POST['delete_company_btn'], $_POST['company_id'], $_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
    $company_id = (int)$_POST['company_id'];

    if (filter_var($company_id, FILTER_VALIDATE_INT)) {
        $sql_delete_company = "DELETE FROM `company` WHERE id = ?";
        $stm_delete_company = $pdo->prepare($sql_delete_company);

        if ($stm_delete_company->execute([$company_id])) {
            header("Location: company.php?action=delete_company&status=success");
            exit;
        } else {
            header("Location: company.php?action=delete_company&status=error");
        }
    }
}

// Process Edit Company Request
$company_update_errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_company_btn'])) {
    $company_id = $_POST['company_id'];
    $company_name = $_POST['company_name'];
    $company_color = $_POST['company_color'];

    // Validate fields
    if (empty($company_name)) {
        $company_update_errors[] = "Please fill in all the fields.";
    }

    if (empty($company_update_errors)) {
        // Update company in the Companys table
        $updateCompanySql = "UPDATE `company` SET name = ?, color = ? WHERE id = ?";
        $stmt = $pdo->prepare($updateCompanySql);
        $stmt->execute([$company_name, $company_color, $company_id]);

        header("Location: company.php?action=update_company&status=success");
        exit;
    } else {
        header("Location: company.php?action=update_company&status=error");
        exit;
    }
}

if (isset($_GET['action']) && isset($_GET['status'])) {
    $alerts = [
        'add_category' => [
            'success' => 'Category Added successfully!',
            'error' => 'Failed to Add Category.',
        ],
        'delete_category' => [
            'success' => 'Category Deleted successfully!',
            'error' => 'Failed to Delete Category.',
        ],
    ];

    // Get the appropriate alert based on action and status
    $action = $_GET['action'];
    $status = $_GET['status'];

    $alert = $alerts[$action][$status] ?? null;
}
?>
<!-- Row -->
<a href="company.php" class="btn btn-transparent d-flex-inline justify-content-start align-items-center"><img src="./assets/icons/back.svg" alt="">Back</a>
<div class="container-fluid">
    <?php if (!empty($alert)) : ?>
        <div class="alert alert-<?= htmlspecialchars($status) === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show mt-3" role="alert">
            <strong class="text-uppercase me-1"><?= htmlspecialchars($status) ?>!</strong><?= htmlspecialchars($alert) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <div class="row mt-4 col-12 justify-content-start p-0 m-0">
        <div class="col-12 d-flex justify-content-between mb-2">
            <button data-bs-toggle="modal" data-bs-target="#category" class="btn btn-small btn-primary d-flex justify-content-between align-items-center">
                <img src="./assets/icons/folder-plus.svg" alt="">Add New Category</button>

            <div class="col-6 col-md-4 d-flex justify-content-between justify-content-md-end align-items-center">
                <button class="btn btn-small btn-warning" data-bs-toggle="modal" data-bs-target="#companyUpdate">Update Company</button>
                <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="hidden" name="company_id" value="<?= $company_id ?>">
                    <button type="submit" name="delete_company_btn" class="btn btn-danger d-flex justify-content-between align-items-center"
                        onclick="return confirm('Are you sure you want to delete this Company?');">
                        Delete Company
                    </button>
                </form>
            </div>
        </div>
        <?php foreach ($categories as $category): ?>
            <div class="col-12 col-md-4 col-xl-2 mb-2">
                <a href="product.php?category_id=<?= $category['category_id'] ?>" class="text-decoration-none">
                    <div class="card" style="background-color: <?= $category['category_color'] ?>">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <h6 class="card-title m-0"><img src="./assets/icons/folder.svg" alt=""></h6>
                                    <h6 class="card-title text-white m-0 ms-1"><?= $category['category_name'] ?></h6>
                                </div>
                                <h6 class="card-text text-white m-0"><?= $category['product_count'] ?></h6>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>


<!-- Modal Add Category -->
<div class="modal fade" id="category" tabindex="-1" aria-labelledby="categoryLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="categoryLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="category_name" class="form-label">Category Name</label>
                        <input type="text" name="category_name" class="form-control">
                        <input type="hidden" name="company_id" value="<?= $company_id ?>">
                    </div>
                    <div class="mb-3">
                        <label for="category_color" class="form-label">Category Color</label>
                        <input type="color" name="category_color" value="#6c757d" class="form-control form-control-color">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="category_btn" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Company -->
<div class="modal fade" id="companyUpdate" tabindex="-1" aria-labelledby="companyUpdateLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="companyUpdateLabel">Edit Company</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="company_name" class="form-label">Company Name</label>
                        <input type="text" class="form-control" name="company_name" value="<?= $company['name'] ?>">
                        <input type="hidden" name="company_id" value="<?= $company['id'] ?>">
                    </div>
                    <div class="mb-3">
                        <label for="company_color" class="form-label">Company Color</label>
                        <input type="color" name="company_color" value="<?= $company['color'] ?>" class="form-control form-control-color">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="update_company_btn" class="btn btn-primary">Save changes</button>
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