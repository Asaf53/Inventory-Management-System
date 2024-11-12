<?php include_once('includes/header.php');
$add_order_errors = [];

// product
$sql_products = "SELECT * FROM `product`";
$stm_products = $pdo->prepare($sql_products);
$stm_products->execute();
$products = $stm_products->fetchAll(PDO::FETCH_ASSOC);

foreach ($products as $product) {
    if ($product['qty'] < 10) {
        $add_order_errors[] = "Qty of product is lower than 10";
    }
}

if (isset($_POST['add_btn'])) {
    $product_id = $_POST['product_id'];
    $client_name = $_POST['client_name'];
    $client_phone = $_POST['client_phone'];
    $price = $_POST['price'];
    $qty = $_POST['qty'];

    if (empty($product_id) || empty($client_name) || empty($client_phone) || empty($price) || empty($qty)) {
        $add_order_errors[] = "Please fill in all the fields.";
    }

    if (count($add_order_errors) === 0) {
        $sql = "INSERT INTO `orders` (`product_id`, `client_name`, `client_phone`, `price`, `qty`) VALUES (?, ?, ?, ?, ?)";
        $stm = $pdo->prepare($sql);
        if ($stm->execute([$product_id, $client_name, $client_phone, $price, $qty])) {
            // Update product quantity
            $sql_update_qty = "UPDATE `product` SET `qty` = `qty` - ? WHERE `id` = ?";
            $stm_update = $pdo->prepare($sql_update_qty);
            $stm_update->execute([$qty, $product_id]);

            $sql_product = "SELECT `qty` FROM `product` WHERE `id` = ?";
            $stm_product = $pdo->prepare($sql_product);
            $stm_product->execute([$product_id]);
            $product = $stm_product->fetch(PDO::FETCH_ASSOC);

            if ($product['qty'] < 10) {
                $add_order_errors[] = "Qty of product is lower than 10";
            }

            header("Location: order.php?action=order_add");
        } else {
            $add_order_errors[] = "Something went wrong!!";
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
                    <h2 class="tm-block-title d-inline-block">Add Order</h2>
                </div>
            </div>
            <?php if (count($add_order_errors) > 0) : ?>
                <ul class="list-group m-3">
                    <?php foreach ($add_order_errors as $error) : ?>
                        <li class="text-danger"><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <div class="row mt-4 tm-edit-product-row">
                <div class="col-xl-7 col-lg-7 col-md-12 mx-auto">
                    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data" class="tm-edit-product-form">
                        <div class="input-group mb-3">
                            <label for="type" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Panel</label>
                            <select class="custom-select col-xl-9 col-lg-8 col-md-8 col-sm-7" name="product_id" id="product_id">
                                <option selected>Choose Panel</option>
                                <?php foreach ($products as $product) : ?>
                                    <option value="<?= $product['id'] ?>"><?= $product['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="input-group mb-3">
                            <label for="client_name" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Client Name</label>
                            <input id="client_name" name="client_name" type="text" class="form-control validate col-xl-9 col-lg-8 col-md-7 col-sm-7">
                        </div>
                        <div class="input-group mb-3">
                            <label for="client_phone" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Client Phone</label>
                            <input id="client_phone" name="client_phone" type="tel" class="form-control validate col-xl-9 col-lg-8 col-md-7 col-sm-7">
                        </div>
                        <!-- <div class="input-group mb-3">
                            <label for="order_date" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Order Date</label>
                            <input id="order_date" name="order_date" type="date" class="form-control validate col-xl-9 col-lg-8 col-md-7 col-sm-7">
                        </div> -->
                        <div class="input-group mb-3">
                            <label for="price" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Price</label>
                            <input id="price" name="price" step="0.1" type="number" class="form-control validate col-xl-9 col-lg-8 col-md-7 col-sm-7">
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