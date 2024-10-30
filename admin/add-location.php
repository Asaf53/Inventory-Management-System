<?php include_once('includes/header.php');

$add_location_errors = [];
if (isset($_POST['add_btn'])) {
    $location = $_POST['location'];
    $address = $_POST['address'];

    if ($location === '' || $address === '') {
        $add_location_errors[] = "Please fill in all the fields.";
    }

    if (count($add_location_errors) === 0) {
        $sql = "INSERT INTO `locations` (`name`, `address`) VALUES (?, ?)";
        $stm = $pdo->prepare($sql);
        if ($stm->execute([$location, $address])) {
            header('Location: cars.php?action=location_add');
        } else {
            $add_location_errors[] = "Something went wrong!!";
        }
    }
}
?>


<!-- row -->
<div class="row tm-mt-big">
    <div class="col-xl-8 col-lg-10 col-md-12 col-sm-12 mx-auto">
        <div class="bg-white tm-block">
            <div class="row">
                <div class="col-12">
                    <h2 class="tm-block-title d-inline-block">Add Location</h2>
                </div>
            </div>
            <?php if (!empty($add_location_errors)) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php foreach ($add_location_errors as $errors) : ?>
                        <?= $errors ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div class="row mt-4 tm-edit-product-row">
                <div class="col-xl-7 col-lg-7 col-md-12">
                    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" class="tm-edit-product-form">
                        <div class="input-group mb-3">
                            <label for="location" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Location</label>
                            <input placeholder="Location" id="location" name="location" type="text" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7">
                        </div>
                        <div class="input-group mb-3">
                            <label for="address" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Address</label>
                            <input placeholder="Address" id="address" name="address" type="text" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7">
                        </div>
                        <div class="input-group mb-3">
                            <div class="ml-auto col-xl-8 col-lg-8 col-md-8 col-sm-7 pl-0">
                                <button type="submit" name="add_btn" class="btn btn-primary">Add</button>
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