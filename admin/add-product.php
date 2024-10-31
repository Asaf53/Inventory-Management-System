<?php include_once('includes/header.php');

$add_product_errors = [];
if (isset($_POST['add_btn'])) {
    $name = $_POST['name'];
    $type = $_POST['type'];
    $length = $_POST['length'];
    $height = $_POST['height'];
    $width = $_POST['width'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $qty = $_POST['qty'];

    if (empty($name) || empty($type) || empty($length) || empty($height) || empty($width) || empty($description) || empty($price) || empty($qty)) {
        $add_product_errors[] = "Please fill in all the fields.";
    }

    if (count($add_product_errors) === 0) {
        $sql = "INSERT INTO `product` (`name`, `type`, `length`, `height`, `width`, `description`, `price`, `qty`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stm = $pdo->prepare($sql);
        if ($stm->execute([$name, $type, $length, $height, $width, $description, $price, $qty])) {
            header("Location: product.php?action=product_add");
        } else {
            $add_product_errors[] = "Something went wrong!!";
        }
    }
}
?>


<!-- row -->
<div class="row tm-mt-big mt-5">
    <div class="col-xl-8 col-lg-10 col-md-12 col-sm-12 mb-5 mx-auto">
        <div class="bg-white tm-block">
            <div class="row text-center">
                <div class="col-12">
                    <h2 class="tm-block-title d-inline-block">Add Product</h2>
                </div>
            </div>
            <?php if (count($add_product_errors) > 0) : ?>
                <ul class="list-group m-3">
                    <?php foreach ($add_product_errors as $error) : ?>
                        <li class="text-danger"><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <div class="row mt-4 tm-edit-product-row">
                <div class="col-xl-7 col-lg-7 col-md-12 mx-auto">
                    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data" class="tm-edit-product-form">
                        <div class="input-group mb-3">
                            <label for="name" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Name</label>
                            <input id="name" name="name" type="text" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7">
                        </div>
                        <div class="input-group mb-3">
                            <label for="type" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Type</label>
                            <select class="custom-select col-xl-9 col-lg-8 col-md-8 col-sm-7" name="type" id="type">
                                <option selected>Choose Panel</option>
                                <option value="Roof Panel">Roof panel</option>
                                <option value="Sandwich Panel">Sandwich Panel</option>
                            </select>
                        </div>
                        <div class="input-group mb-3">
                            <label for="length" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Length(m)</label>
                            <input id="length" name="length" type="number" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7">
                        </div>
                        <div class="input-group mb-3">
                            <label for="height" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Height(m)</label>
                            <input id="height" name="height" type="number" step="0.1" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7" data-large-mode="true">
                        </div>
                        <div class="input-group mb-3">
                            <label for="width" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Width(m)</label>
                            <input id="width" name="width" type="number" class="form-control validate col-xl-9 col-lg-8 col-md-7 col-sm-7">
                        </div>
                        <div class="input-group mb-3">
                            <label for="description" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Description</label>
                            <textarea name="description" id="description" class="form-control validate col-xl-9 col-lg-8 col-md-7 col-sm-7"></textarea>
                        </div>
                        <div class="input-group mb-3">
                            <label for="price" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Price</label>
                            <input id="price" name="price" type="number" class="form-control validate col-xl-9 col-lg-8 col-md-7 col-sm-7">
                        </div>
                        <div class="input-group mb-3">
                            <label for="qty" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Qty</label>
                            <input id="qty" name="qty" type="number" class="form-control validate col-xl-9 col-lg-8 col-md-7 col-sm-7">
                        </div>
                        <div class="input-group mb-3">
                            <div class="ml-auto col-xl-8 col-lg-8 col-md-8 col-sm-7 pl-0">
                                <button type="submit" name="add_btn" class="btn btn-primary">Save</button>
                            </div>
                        </div>
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