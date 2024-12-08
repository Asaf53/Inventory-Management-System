<?php include_once('includes/header.php');

$sql_categories = "SELECT * FROM `categories`";
$stm_categories = $pdo->prepare($sql_categories);
$stm_categories->execute();
$categories = $stm_categories->fetchAll(PDO::FETCH_ASSOC);

// Process Delete Request
// if (isset($_POST['delete_btn'], $_POST['product_id'], $_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
//     $product_id = (int)$_POST['product_id'];

//     if (filter_var($product_id, FILTER_VALIDATE_INT)) {
//         $sql_delete_product = "DELETE FROM `products` WHERE id = ?";
//         $stm_delete_product = $pdo->prepare($sql_delete_product);

//         if ($stm_delete_product->execute([$product_id])) {
//             header('Location: product.php?action=product_delete');
//             exit;
//         } else {
//             header('Location: product.php?action=product_delete_fail');
//         }
//     }
// }
?>
<!-- Row -->
<?php
if (isset($_GET['action'])) {
    $alerts = [
        'product_add' => 'Product added successfully',
        'product_delete' => 'Product deleted successfully',
        'product_edit' => 'Product edited successfully',
        'product_delete_fail' => 'Product delete fail',

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
<div class="container-fluid">
    <div class="row mt-4 col-12 justify-content-start p-0 m-0">
        <div class="col-12 d-flex justify-content-start mb-2">
            <a href="#" class="btn btn-small btn-primary d-flex justify-content-between align-items-center"> <img
                    src="./assets/icons/folder-plus.svg" alt="">Add New Category</a>
        </div>
        <?php foreach ($categories as $category): ?>
        <div class="col-12 col-md-4 col-xl-2 mb-2">
            <a href="categories.php?category_id=<?= $category['id'] ?>" class="text-decoration-none">
                <div class="card bg-secondary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <h6 class="card-title m-0"><img src="./assets/icons/folder.svg" alt=""></h6>
                                <h6 class="card-title text-white m-0 ms-1"><?= $category['name'] ?></h6>
                            </div>
                            <h6 class="card-text text-white m-0">776</h6>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- <div class="container-fluid mt-3">
    <div class="col-12">
        <div class="bg-white">
            <div class="row align-items-center justify-content-center">
                <div class="col-6 col-md-8">
                    <h2>Products List</h2>
                </div>
                <div class="col-6 col-md-4 d-flex justify-content-end align-items-center">
                    <a href="add-product.php" class="btn btn-small btn-primary">Add New Product</a>
                </div>
            </div>
            <div class="table-responsive text-nowrap mt-3">
                <table class="table table-striped align-middle overflow-scroll col-12" id="table" data-search-align="left"
                    data-pagination="true" data-toggle="table" data-search="true" data-searchable="true">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">No.</th>
                            <th scope="col" class="text-center">Product Name</th>
                            <th scope="col" class="text-center" data-sortable="true">Type</th>
                            <th scope="col" class="text-center" data-sortable="true">Length(m)</th>
                            <th scope="col" class="text-center" data-sortable="true">Qty</th>
                            <th scope="col" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $i => $product) : ?>
                        <tr class="<?= $product['qty'] < 10 ? 'bg-warning' : 'bg-white' ?>">
                            <td class="text-center"><?= $i + 1 ?></td>
                            <td class="text-center"><?= $product['name'] ?></td>
                            <td class="text-center"><?= $product['type'] ?></td>
                            <td class="text-center"><?= $product['length'] ?></td>
                            <td class="text-center"><?= $product['qty'] ?></td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center">
                                    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post"
                                        style="display:inline;">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                        <button type="submit" name="delete_btn" class="btn btn-link p-0"
                                            onclick="return confirm('Are you sure you want to delete this product?');">
                                            <i class="bx bx-trash text-danger h3 m-0"></i>
                                        </button>
                                    </form>
                                    <a
                                        href="edit-product.php?product_id=<?= $product['id'] ?>&token=<?= $_SESSION['csrf_token'] ?>"><i
                                            class="bx bx-edit text-warning h3 m-0"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div> -->
<script src="assets/js/jquery-1.11.0.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.5/dist/bootstrap-table.min.js"></script>
</body>

</html>