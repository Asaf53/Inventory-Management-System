<?php include_once('includes/header.php');

$sql_categories = "
SELECT `categories`.`name` AS category_name, `categories`.`id` AS category_id, COUNT(`products`.`id`) AS product_count
FROM  `categories`
LEFT JOIN `products` ON 
`categories`.`id` = `products`.`category_id`
GROUP BY `categories`.`id`, `categories`.`name`";
$stm_categories = $pdo->prepare($sql_categories);
$stm_categories->execute();
$categories = $stm_categories->fetchAll(PDO::FETCH_ASSOC);


$categry_errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['category_btn'])) {
    $category_name = $_POST['category_name'];

    if (empty($category_name)) {
        $categry_errors[] = "Please fill in all the fields.";
    }

    if (empty($categry_errors)) {
        $insertCategorySql = "INSERT INTO `categories` (`name`) VALUES (?)";
        $categoryStmt = $pdo->prepare($insertCategorySql);
        $categoryStmt->execute([$category_name]);
        header("Location: categories.php?action=add_category&status=success");
    } else {
        $categry_errors[] = "Error adding category.";
        header("Location: categories.php?action=add_category&status=error");
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
<button onclick="history.back()" class="btn btn-transparent d-flex justify-content-between align-items-center"><img src="./assets/icons/back.svg" alt=""> Back</button>
<div class="container-fluid">
    <?php if (!empty($alert)) : ?>
        <div class="alert alert-<?= htmlspecialchars($status) === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show mt-3" role="alert">
            <strong class="text-uppercase me-1"><?= htmlspecialchars($status) ?>!</strong><?= htmlspecialchars($alert) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <div class="row mt-4 col-12 justify-content-start p-0 m-0">
        <div class="col-12 d-flex justify-content-start mb-2">
            <button data-bs-toggle="modal" data-bs-target="#category" class="btn btn-small btn-primary d-flex justify-content-between align-items-center">
                <img src="./assets/icons/folder-plus.svg" alt="">Add New Category</button>
        </div>
        <?php foreach ($categories as $category): ?>
            <div class="col-12 col-md-4 col-xl-2 mb-2">
                <a href="product.php?category_id=<?= $category['category_id'] ?>" class="text-decoration-none">
                    <div class="card bg-secondary">
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
<script src="assets/js/jquery-1.11.0.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.5/dist/bootstrap-table.min.js"></script>
</body>

</html>