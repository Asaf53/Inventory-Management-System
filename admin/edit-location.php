<?php include_once('includes/header.php');

if (isset($_GET['location_id'])) {
    $location_id = $_GET['location_id'];
    $sql_location = "SELECT * FROM `locations` WHERE id = ?";
    $stm_location = $pdo->prepare($sql_location);
    $stm_location->execute([$location_id]);
    $location = $stm_location->fetchAll(PDO::FETCH_ASSOC);
}
if (isset($_POST['edit_btn'])) {
    $name = $_POST['location'];
    $address = $_POST['address'];
    $id = $_POST['location_id'];
    $sql_edit_location = "UPDATE `locations` SET `name` = ?, `address` = ? WHERE id = ?";
    $stm_edit_location = $pdo->prepare($sql_edit_location);
    if ($stm_edit_location->execute([$name, $address, $id])) {
        header('Location: cars.php?action=location_edit');
    }
}
?>


<!-- row -->
<div class="row tm-mt-big">
    <div class="col-xl-8 col-lg-10 col-md-12 col-sm-12 mx-auto">
        <div class="bg-white tm-block">
            <div class="row">
                <div class="col-12">
                    <h2 class="tm-block-title d-inline-block">Edit Location</h2>
                </div>
            </div>
            <div class="row mt-4 tm-edit-product-row">
                <div class="col-xl-7 col-lg-7 col-md-12">
                    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" class="tm-edit-product-form">
                        <?php foreach ($location as $l) : ?>
                            <div class="input-group mb-3">
                                <label for="location" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Location</label>
                                <input value="<?= $l['id'] ?>" name="location_id" type="text" hidden>
                                <input value="<?= $l['name'] ?>" id="location" name="location" type="text" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7">
                            </div>
                            <div class="input-group mb-3">
                                <label for="address" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Address</label>
                                <input value="<?= $l['address'] ?>" id="address" name="address" type="text" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7">
                            </div>
                            <div class="input-group mb-3">
                                <div class="ml-auto col-xl-8 col-lg-8 col-md-8 col-sm-7 pl-0">
                                    <button type="submit" name="edit_btn" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </form>
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