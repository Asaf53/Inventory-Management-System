<?php include_once('includes/header.php');

// Process Select Product Request
$category_products = [];
if (isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];
    $sql_category_products =
        "SELECT *, `products`.`name` as `product_name`, `inventorysummary`.`current_qty` as `product_qty` FROM `products`
    INNER JOIN `inventorysummary` ON `products`.`id` = `inventorysummary`.`product_id` WHERE `products`.`category_id` = $category_id";
    $stm_category_products = $pdo->prepare($sql_category_products);
    $stm_category_products->execute();
    $category_products = $stm_category_products->fetchAll(PDO::FETCH_ASSOC);

    $sql_category = "SELECT `categories`.`name` as `category_name`, `categories`.`id` FROM `categories` WHERE `categories`.`id` = $category_id";
    $stm_category = $pdo->prepare($sql_category);
    $stm_category->execute();
    $category = $stm_category->fetch(PDO::FETCH_ASSOC);
}

// Process Delete Category Request
if (isset($_POST['delete_category_btn'], $_POST['category_id'], $_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
    $category_id = (int)$_POST['category_id'];

    if (filter_var($category_id, FILTER_VALIDATE_INT)) {
        $sql_delete_category = "DELETE FROM `categories` WHERE id = ?";
        $stm_delete_category = $pdo->prepare($sql_delete_category);

        if ($stm_delete_category->execute([$category_id])) {
            header('Location: categories.php?action=delete_category&status=success');
            exit;
        } else {
            header('Location: categories.php?action=delete_category&status=error');
        }
    }
}

// Process Add Product Request
$product_errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product_btn'])) {
    $category_id = $_POST['category_id'];
    $product_name = $_POST['product_name'];
    $product_length = $_POST['product_length'];
    $product_qty = $_POST['product_qty'];

    // Validate fields
    if (empty($product_name) || empty($product_length) || empty($category_id) || empty($product_qty)) {
        $product_errors[] = "Please fill in all the fields.";
    }

    if (empty($product_errors)) {
        // Insert product into Products table
        $insertProductSql = "INSERT INTO `products` (name, length, category_id) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($insertProductSql);
        $stmt->execute([$product_name, $product_length, $category_id]);
        $product_id = $pdo->lastInsertId(); // Get the last inserted product ID

        // Insert initial quantity into InventorySummary
        $insertInventorySql = "INSERT INTO `inventorysummary` (product_id, current_qty) VALUES (?, ?)";
        $inventoryStmt = $pdo->prepare($insertInventorySql);
        $inventoryStmt->execute([$product_id, $product_qty]);

        header("Location: product.php?category_id=$category_id&action=add_product&status=success");
        exit;
    } else {
        header("Location: product.php?category_id=$category_id&action=add_product&status=error");
        exit;
    }
}

// Process Edit Product Request
$product_update_errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_product_btn'])) {
    $product_id = $_POST['product_id']; // Assuming the product ID is passed in the form
    $category_id = $_POST['category_id'];
    $product_name = $_POST['product_name'];
    $product_length = $_POST['product_length'];
    $product_qty = $_POST['product_qty'];

    // Validate fields
    if (empty($product_id) || empty($product_name) || empty($product_length) || empty($category_id) || empty($product_qty)) {
        $product_update_errors[] = "Please fill in all the fields.";
    }

    if (empty($product_update_errors)) {
        // Update product in the Products table
        $updateProductSql = "UPDATE `products` SET name = ?, length = ?, category_id = ? WHERE id = ?";
        $stmt = $pdo->prepare($updateProductSql);
        $stmt->execute([$product_name, $product_length, $category_id, $product_id]);

        // Update quantity in InventorySummary
        $updateInventorySql = "UPDATE `inventorysummary` SET current_qty = ? WHERE product_id = ?";
        $inventoryStmt = $pdo->prepare($updateInventorySql);
        $inventoryStmt->execute([$product_qty, $product_id]);

        header("Location: product.php?category_id=$category_id&action=update_product&status=success");
        exit;
    } else {
        header("Location: product.php?category_id=$category_id&action=update_product&status=error");
        exit;
    }
}

// Process Delete Product Request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product_btn'])) {
    $product_id = $_POST['product_id'];
    $category_id = $_POST['category_id'];

    try {
        // Delete from InventorySummary
        $deleteInventorySql = "DELETE FROM `inventorysummary` WHERE product_id = ?";
        $inventoryStmt = $pdo->prepare($deleteInventorySql);
        $inventoryStmt->execute([$product_id]);

        // Delete from Products table
        $deleteProductSql = "DELETE FROM `products` WHERE id = ?";
        $productStmt = $pdo->prepare($deleteProductSql);
        $productStmt->execute([$product_id]);

        header("Location: product.php?category_id=$category_id&action=delete_product&status=success");
        exit;
    } catch (Exception $e) {
        header("Location: product.php?category_id=$category_id&action=delete_product&status=error");
        exit;
    }
}


if (isset($_GET['action']) && isset($_GET['status'])) {
    $alerts = [
        'add_product' => [
            'success' => 'Product Added successfully!',
            'error' => 'Failed to Add Product.',
        ],
        'update_product' => [
            'success' => 'Product Updated successfully!',
            'error' => 'Failed to Update Product.',
        ],
        'delete_product' => [
            'success' => 'Product Deleted successfully!',
            'error' => 'Failed to Delete Product.',
        ],
    ];

    // Get the appropriate alert based on action and status
    $action = $_GET['action'];
    $status = $_GET['status'];

    $alert = $alerts[$action][$status] ?? null;
}
?>
<!-- Row -->

<a href="categories.php" class="btn btn-transparent d-flex-inline justify-content-start align-items-center"><img src="./assets/icons/back.svg" alt="">Back</a>
<div class="container-fluid mt-3">
    <?php if (!empty($alert)) : ?>
        <div class="alert alert-<?= htmlspecialchars($status) === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show mt-3" role="alert">
            <strong class="text-uppercase me-1"><?= htmlspecialchars($status) ?>!</strong><?= htmlspecialchars($alert) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <div class="col-12">
        <div class="bg-white">
            <div class="row align-items-center justify-content-center">
                <div class="col-12 col-md-8">
                    <h2>Products List - <?= $category['category_name'] ?></h2>
                </div>
                <div class="col-12 col-md-4 d-flex justify-content-between justify-content-md-end align-items-center">
                    <button class="btn btn-small btn-primary" data-bs-toggle="modal" data-bs-target="#productAdd">Add New Product</button>
                    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post"
                        style="display:inline;">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <input type="hidden" name="category_id" value="<?= $category_id ?>">
                        <button type="submit" name="delete_category_btn" class="btn btn-small btn-danger ms-2"
                            onclick="return confirm('Are you sure you want to delete this Category?');">
                            Delete Category
                        </button>
                    </form>
                </div>
            </div>
            <div class="table-responsive text-nowrap mt-3">
                <table class="table table-striped align-middle overflow-scroll col-12" id="table"
                    data-search-align="left" data-pagination="true" data-toggle="table" data-search="true"
                    data-searchable="true">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">No.</th>
                            <!-- <th scope="col" class="text-center">Product Name</th> -->
                            <th scope="col" class="text-center" data-sortable="true">Length(m)</th>
                            <th scope="col" class="text-center" data-sortable="true">Qty</th>
                            <th scope="col" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($category_products as $i => $product): ?>
                            <tr class="<?= $product['product_qty'] < 8 ? 'table-warning' : 'table-white' ?>">
                                <td class="text-center"><?= $i + 1 ?></td>
                                <!-- <td class="text-center"><?= $product['product_name'] ?></td> -->
                                <td class="text-center"><?= $product['length'] ?></td>
                                <td class="text-center"><?= $product['product_qty'] ?></td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST"
                                            style="display:inline;">
                                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                            <input type="hidden" name="category_id" value="<?= $category['id'] ?>">
                                            <button type="submit" name="delete_product_btn" class="btn btn-link p-0"
                                                onclick="return confirm('Are you sure you want to delete this product?');">
                                                <i class="bx bx-trash text-danger h3 m-0"></i>
                                            </button>
                                        </form>
                                        <button
                                            class="btn btn-transparent p-0"
                                            data-bs-toggle="modal"
                                            data-bs-target="#productUpdate"
                                            data-id="<?= $product['id'] ?>"
                                            data-name="<?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>"
                                            data-length="<?= htmlspecialchars($product['length'], ENT_QUOTES, 'UTF-8') ?>"
                                            data-qty="<?= $product['current_qty'] ?>">
                                            <i class="bx bx-edit text-warning h3 m-0"></i>
                                        </button>
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

<!-- Modal Add Product-->
<div class="modal fade" id="productAdd" tabindex="-1" aria-labelledby="productAddLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="productAddLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="productCategory" class="form-label">Product Category</label>
                        <input type="text" class="form-control" value="<?= $category['category_name'] ?>" id="productCategory" disabled readonly>
                        <input type="hidden" name="category_id" value="<?= $category['id'] ?>">
                    </div>
                    <div class="mb-3">
                        <label for="productName" class="form-label">Product Name</label>
                        <input type="text" name="product_name" class="form-control" id="productName">
                    </div>
                    <div class="mb-3">
                        <label for="productLength" class="form-label">Product Length</label>
                        <input type="text" name="product_length" class="form-control" id="productLength">
                    </div>
                    <div class="mb-3">
                        <label for="productQty" class="form-label">Product Qty</label>
                        <input type="number" name="product_qty" class="form-control" id="productQty">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add_product_btn" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal Edit Product -->
<div class="modal fade" id="productUpdate" tabindex="-1" aria-labelledby="productUpdateLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="productUpdateLabel">Edit Product</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="productCategory" class="form-label">Product Category</label>
                        <input type="text" class="form-control" value="<?= $category['category_name'] ?>" id="productCategory" disabled readonly>
                        <input type="hidden" name="category_id" value="<?= $category['id'] ?>">
                        <input type="hidden" name="product_id" id="modalProductId">
                    </div>
                    <div class="mb-3">
                        <label for="productName" class="form-label">Product Name</label>
                        <input type="text" name="product_name" class="form-control" id="productName" required>
                    </div>
                    <div class="mb-3">
                        <label for="productLength" class="form-label">Product Length</label>
                        <input type="text" name="product_length" class="form-control" id="productLength" required>
                    </div>
                    <div class="mb-3">
                        <label for="productQty" class="form-label">Product Qty</label>
                        <input type="number" name="product_qty" class="form-control" id="productQty" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="update_product_btn" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var productUpdateModal = document.getElementById('productUpdate');
        productUpdateModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget; // Button that triggered the modal

            // Extract data-* attributes
            var productId = button.getAttribute('data-id');
            var productName = button.getAttribute('data-name');
            var productLength = button.getAttribute('data-length');
            var productQty = button.getAttribute('data-qty');

            // Populate modal fields
            productUpdateModal.querySelector('#modalProductId').value = productId;
            productUpdateModal.querySelector('#productName').value = productName;
            productUpdateModal.querySelector('#productLength').value = productLength;
            productUpdateModal.querySelector('#productQty').value = productQty;
        });
    });
</script>
<script src="assets/js/jquery-1.11.0.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.5/dist/bootstrap-table.min.js"></script>
</body>

</html>