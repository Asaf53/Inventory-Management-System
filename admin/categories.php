<?php include_once('includes/header.php');

$category_products = [];
if (isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];
    $sql_category_products =
        "SELECT *, `products`.`name` as `product_name`, `inventorysummary`.`current_qty` as `product_qty` FROM `products` 
    INNER JOIN `categories` ON `products`.`category_id` = `categories`.`id`
    INNER JOIN `inventorysummary` ON `products`.`id` = `inventorysummary`.`product_id` WHERE `products`.`category_id` = $category_id";
    $stm_category_products = $pdo->prepare($sql_category_products);
    $stm_category_products->execute();
    $category_products = $stm_category_products->fetchAll(PDO::FETCH_ASSOC);
}

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
<div class="container-fluid mt-3">
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
                <table class="table table-striped align-middle overflow-scroll col-12" id="table"
                    data-search-align="left" data-pagination="true" data-toggle="table" data-search="true"
                    data-searchable="true">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">No.</th>
                            <th scope="col" class="text-center">Product Name</th>
                            <th scope="col" class="text-center" data-sortable="true">Length(m)</th>
                            <th scope="col" class="text-center" data-sortable="true">Qty</th>
                            <th scope="col" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($category_products as $i => $product): ?>
                            <tr class="<?= $product['product_qty'] < 10 ? 'bg-warning' : 'bg-white' ?>">
                                <td class="text-center"><?= $i + 1 ?></td>
                                <td class="text-center"><?= $product['product_name'] ?></td>
                                <td class="text-center"><?= $product['length'] ?></td>
                                <td class="text-center"><?= $product['product_qty'] ?></td>
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
</div>
<script src="assets/js/jquery-1.11.0.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.5/dist/bootstrap-table.min.js"></script>
</body>

</html>