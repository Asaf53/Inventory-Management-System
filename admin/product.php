<?php include_once('includes/header.php');

$sql_products = "SELECT * FROM `product`";
$stm_products = $pdo->prepare($sql_products);
$stm_products->execute();
$products = $stm_products->fetchAll(PDO::FETCH_ASSOC);

// Process Delete Request
if (isset($_POST['delete_btn'], $_POST['product_id'], $_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
    $product_id = (int)$_POST['product_id'];

    if (filter_var($product_id, FILTER_VALIDATE_INT)) {
        $sql_delete_product = "DELETE FROM `product` WHERE id = ?";
        $stm_delete_product = $pdo->prepare($sql_delete_product);

        if ($stm_delete_product->execute([$product_id])) {
            header('Location: product.php?action=product_delete');
            exit;
        } else {
            header('Location: product.php?action=product_delete_fail');
        }
    }
}
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
<div class="row tm-content-row tm-mt-big mt-3">
    <div class="col-xl-12 col-lg-12 tm-md-12 tm-sm-12 tm-col">
        <div class="bg-white tm-block h-100">
            <div class="row align-items-center justify-content-between">
                <div class="col-md-8 col-4">
                    <h2 class="tm-block-title d-inline-block mb-0">Products</h2>
                </div>
                <div class="col-md-4 col-8 d-flex justify-content-end">
                    <a href="add-product.php" class="btn btn-small btn-primary">Add New Product</a>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped tm-table-striped-even mt-3">
                    <thead>
                        <tr class="tm-bg-gray">
                            <th scope="col" class="text-center">No.</th>
                            <th scope="col">Product Name</th>
                            <th scope="col">Type</th>
                            <th scope="col" class="text-center">Length</th>
                            <th scope="col" class="text-center">Height</th>
                            <th scope="col" class="text-center">Width</th>
                            <th scope="col">Description</th>
                            <th scope="col" class="text-center">Price</th>
                            <th scope="col" class="text-center">Qty</th>
                            <th scope="col" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $i => $product) : ?>
                            <tr>
                                <td class="text-center"><?= $i + 1 ?></td>
                                <td class="tm-car-name"><?= $product['name'] ?></td>
                                <td><?= $product['type'] ?></td>
                                <td class="text-center"><?= $product['length'] ?></td>
                                <td class="text-center"><?= $product['height'] ?></td>
                                <td class="text-center"><?= $product['width'] ?></td>
                                <td><?= $product['description'] ?></td>
                                <td class="text-center"><?= $product['price'] ?></td>
                                <td class="text-center"><?= $product['qty'] ?></td>
                                <td class="text-center">
                                    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" style="display:inline;">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                        <button type="submit" name="delete_btn" class="btn btn-link p-0" onclick="return confirm('Are you sure you want to delete this product?');">
                                            <i class="fas fa-trash-alt tm-trash-icon"></i>
                                        </button>
                                    </form>
                                    <a href="edit-product.php?product_id=<?= $product['id'] ?>&token=<?= $_SESSION['csrf_token'] ?>"><i class="fas fa-edit tm-trash-icon"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>

</html>