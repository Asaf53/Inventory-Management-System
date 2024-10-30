<?php include_once('includes/header.php');

$add_car_errors = [];
if (isset($_POST['add_btn'])) {
    $make = $_POST['make'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $gearbox = $_POST['gearbox'];
    $doors = $_POST['doors'];
    $fuel = $_POST['fuel'];
    $seats = $_POST['seats'];
    $type = $_POST['type'];
    $rental_rate = $_POST['rental'];

    if ($make === '' || $model === '' || $year === '' || $gearbox === '' || $doors === '' || $fuel === '' || $seats === '' || $type === '' || $rental_rate === '') {
        $add_car_errors[] = "Please fill in all the fields.";
    }

    if (empty($add_car_errors)) {
        $sql = "INSERT INTO `cars` (`make`, `model`, `year`, `gearbox`, `doors`, `fuel`, `seats`, `type`, `rental_rate`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stm = $pdo->prepare($sql);
        if ($stm->execute([$make, $model, $year, $gearbox, $doors, $fuel, $seats, $type, $rental_rate])) {
            $last_insert_id = $pdo->lastInsertId();
            header("Location: add-car-images.php?car_id=$last_insert_id");
        } else {
            $add_car_errors[] = "Something went wrong!!";
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
                    <h2 class="tm-block-title d-inline-block">Add Car</h2>
                </div>
            </div>
            <?php if (!empty($add_car_errors)) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php foreach ($add_car_errors as $errors) : ?>
                        <?= $errors ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div class="row mt-4 tm-edit-product-row">
                <div class="col-xl-7 col-lg-7 col-md-12 mx-auto">
                    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data" class="tm-edit-product-form">
                        <div class="input-group mb-3">
                            <label for="make" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Make</label>
                            <input id="make" name="make" type="text" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7">
                        </div>
                        <div class="input-group mb-3">
                            <label for="model" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Model</label>
                            <input id="model" name="model" type="text" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7">
                        </div>
                        <div class="input-group mb-3">
                            <label for="year" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Year</label>
                            <input id="year" name="year" type="text" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7" data-large-mode="true">
                        </div>
                        <div class="input-group mb-3">
                            <label for="gearbox" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Gearbox</label>
                            <select class="custom-select col-xl-9 col-lg-8 col-md-8 col-sm-7" name="gearbox" id="gearbox">
                                <option value="" selected>Select one</option>
                                <option value="automatic">Automatic</option>
                                <option value="manual">Manual</option>
                            </select>
                        </div>
                        <div class="input-group mb-3">
                            <label for="doors" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Doors</label>
                            <input id="doors" name="doors" type="text" class="form-control validate col-xl-9 col-lg-8 col-md-7 col-sm-7">
                        </div>
                        <div class="input-group mb-3">
                            <label for="fuel" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Fuel</label>
                            <input id="fuel" name="fuel" type="text" class="form-control validate col-xl-9 col-lg-8 col-md-7 col-sm-7">
                        </div>
                        <div class="input-group mb-3">
                            <label for="seats" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Seats</label>
                            <input id="seats" name="seats" type="text" class="form-control validate col-xl-9 col-lg-8 col-md-7 col-sm-7">
                        </div>
                        <div class="input-group mb-3">
                            <label for="type" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Type</label>
                            <select class="custom-select col-xl-9 col-lg-8 col-md-8 col-sm-7" name="type" id="type">
                                <option value="" selected>Select one</option>
                                <option value="Hatchback">Hatchback</option>
                                <option value="Sedan">Sedan</option>
                                <option value="SUV">SUV</option>
                                <option value="Coupe">Coupe</option>
                                <option value="Cabriolets/convertibles">Cabriolets/convertibles</option>
                                <option value="Wagon">Wagon</option>
                                <option value="Pickup">Pick-up</option>
                            </select>
                        </div>
                        <div class="input-group mb-3">
                            <label for="rental" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Rental Rate</label>
                            <input id="rental" name="rental" type="number" class="form-control validate col-xl-9 col-lg-8 col-md-7 col-sm-7">
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