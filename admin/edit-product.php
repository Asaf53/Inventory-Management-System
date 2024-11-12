<?php include_once('includes/header.php');

// Process Update Request
$edit_product_errors = [];
$product = null;
if (isset($_GET['product_id'], $_GET['token']) && $_GET['token'] === $_SESSION['csrf_token']) {
    $product_id = $_GET['product_id'];
    $csrf_token = $_GET['token'];
    $sql_products = "SELECT * FROM `product` WHERE id = ?";
    $stm_products = $pdo->prepare($sql_products);
    $stm_products->execute([$product_id]);
    $product = $stm_products->fetch(PDO::FETCH_ASSOC);
}

if (isset($_POST['edit_btn'], $_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
    if (isset($_POST['product_id']) && filter_var($_POST['product_id'], FILTER_VALIDATE_INT)) {
        $product_id = (int)$_POST['product_id'];
        $name = $_POST['name'];
        $type = $_POST['type'];
        $length = $_POST['length'];
        $height = $_POST['height'];
        $width = $_POST['width'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $qty = $_POST['qty'];

        if (empty($name) || empty($type) || empty($length) || empty($height) || empty($width) || empty($description) || empty($price) || empty($qty)) {
            $edit_product_errors[] = "Please fill in all the fields.";
        }

        if (count($edit_product_errors) === 0) {
            $sql_edit_product = "UPDATE `product` SET `name` = ?, `type` = ?, `length` = ?, `height` = ?, `width` = ?, `description` = ?, `price` = ?, `qty` = ? WHERE id = ?";
            $stm_edit_product = $pdo->prepare($sql_edit_product);
            if ($stm_edit_product->execute([$name, $type, $length, $height, $width, $description, $price, $qty, $product_id])) {
                header('Location: product.php?action=product_edit');
            } else {
                $edit_product_errors[] = "Something went wrong!!";
            }
        }
    }
}
?>


<!-- row -->
<div class="row tm-mt-big mt-5">
    <div class="col-xl-8 col-lg-10 col-md-12 col-sm-12 mb-5 mx-auto">
        <div class="bg-white tm-block">
            <div class="row">
                <div class="col-12">
                    <h2 class="tm-block-title d-inline-block">Edit Product</h2>
                </div>
            </div>
            <?php if (count($edit_product_errors) > 0) : ?>
                <ul class="list-group m-3">
                    <?php foreach ($edit_product_errors as $error) : ?>
                        <li class="text-danger"><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <div class="row mt-4 tm-edit-product-row">
                <div class="col-md-12">
                    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data" class="tm-edit-product-form">
                        <?php if ($product) : ?>
                            <div class="input-group mb-3">
                                <label for="name" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Name</label>
                                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <input value="<?= $product['name'] ?>" id="name" name="name" type="text" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7">
                            </div>
                            <div class="input-group mb-3">
                                <label for="type" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Type</label>
                                <select class="custom-select col-xl-9 col-lg-8 col-md-8 col-sm-7" name="type" id="type">
                                    <option value="<?= $product['type'] ?>" selected><?= $product['type'] ?></option>
                                    <option value="Roof Panel">Roof panel</option>
                                    <option value="Sandwich Panel">Sandwich Panel</option>
                                </select>
                            </div>
                            <div class="input-group mb-3">
                                <label for="length" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Length(m)</label>
                                <input value="<?= $product['length'] ?>" id="length" name="length" type="number" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7">
                            </div>
                            <div class="input-group mb-3">
                                <label for="height" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Height(m)</label>
                                <input value="<?= $product['height'] ?>" id="height" name="height" type="number" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7" data-large-mode="true">
                            </div>
                            <div class="input-group mb-3">
                                <label for="width" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Width(m)</label>
                                <input value="<?= $product['width'] ?>" id="width" name="width" type="number" class="form-control validate col-xl-9 col-lg-8 col-md-7 col-sm-7">
                            </div>
                            <div class="input-group mb-3">
                                <label for="description" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Description</label>
                                <textarea name="description" id="description" class="form-control validate col-xl-9 col-lg-8 col-md-7 col-sm-7"><?= $product['description'] ?></textarea>
                            </div>
                            <div class="input-group mb-3">
                                <label for="price" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Price</label>
                                <input value="<?= $product['price'] ?>" id="price" name="price" type="number" class="form-control validate col-xl-9 col-lg-8 col-md-7 col-sm-7">
                            </div>
                            <div class="input-group mb-3">
                                <label for="qty" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Qty</label>
                                <input value="<?= $product['qty'] ?>" id="qty" name="qty" type="number" class="form-control validate col-xl-9 col-lg-8 col-md-7 col-sm-7">
                            </div>
                            <div class="input-group mb-3">
                                <div class="ml-auto col-xl-8 col-lg-8 col-md-8 col-sm-7 pl-0">
                                    <button type="submit" name="edit_btn" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>

</html>