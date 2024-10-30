<?php include_once('includes/header.php');
if (isset($_GET['car_id'])) {
    $car_id = $_GET['car_id'];
    $sql_car = "SELECT * FROM `cars` WHERE id = ?";
    $stm_car = $pdo->prepare($sql_car);
    $stm_car->execute([$car_id]);
    $car = $stm_car->fetchAll(PDO::FETCH_ASSOC);


    $car_images = [];
    $sql_car_images = "SELECT * FROM `cars_images` WHERE car_id = ?";
    $stm_car_images = $pdo->prepare($sql_car_images);
    $stm_car_images->execute([$car_id]);
    $car_images = $stm_car_images->fetchAll(PDO::FETCH_ASSOC);
}

if (isset($_POST['edit_btn'])) {
    $make = $_POST['make'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $gearbox = $_POST['gearbox'];
    $doors = $_POST['doors'];
    $fuel = $_POST['fuel'];
    $seats = $_POST['seats'];
    $type = $_POST['type'];
    $rental_rate = $_POST['rental'];
    $id = $_POST['car_id'];

    $sql_edit_car = "UPDATE `cars` SET `make` = ?, `model` = ?, `year` = ?, `gearbox` = ?, `doors` = ?, `fuel` = ?, `seats` = ?, `type` = ?, `rental_rate` = ? WHERE id = ?";
    $stm_edit_car = $pdo->prepare($sql_edit_car);
    if ($stm_edit_car->execute([$make, $model, $year, $gearbox, $doors, $fuel, $seats, $type, $rental_rate, $id])) {
        header('Location: cars.php?action=car_edit&type=car');
    }
}

if (isset($_GET['image_id'])) {
    $image_id = $_GET['image_id'];
    $car_id = $_GET['car_id'];

    $folder = '../assets/img/cars/';
    $fileToDelete = '';
    $images = [];
    $sql_images = "SELECT `images` FROM `cars_images` WHERE car_id = ? AND id = ?";
    $stm_images = $pdo->prepare($sql_images);
    $stm_images->execute([$car_id, $image_id]);
    $images = $stm_images->fetchAll(PDO::FETCH_ASSOC);
    foreach ($images as $image) {
        $fileToDelete = $image['images'];
        $filePath = $folder . $fileToDelete;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
    $sql_delete_image = "DELETE FROM `cars_images` WHERE id = ?";
    $stm_delete_image = $pdo->prepare($sql_delete_image);
    if ($stm_delete_image->execute([$image_id])) {
        header("Location: edit-car.php?car_id=$car_id");
    }
}
?>


<!-- row -->
<div class="row tm-mt-big mt-5">
    <div class="col-xl-6 col-lg-10 col-md-12 col-sm-12 mb-md-5">
        <div class="bg-white tm-block">
            <div class="row">
                <div class="col-12">
                    <h2 class="tm-block-title d-inline-block">Edit Product</h2>
                </div>
            </div>
            <div class="row mt-4 tm-edit-product-row">
                <div class="col-md-12">
                    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" class="tm-edit-product-form">
                        <?php foreach ($car as $c) : ?>
                            <div class="input-group mb-3">
                                <label for="make" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Make</label>
                                <input value="<?= $c['id'] ?>" name="car_id" type="text" hidden>
                                <input value="<?= $c['make'] ?>" id="make" name="make" type="text" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7">
                            </div>
                            <div class="input-group mb-3">
                                <label for="model" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Model</label>
                                <input value="<?= $c['model'] ?>" id="model" name="model" type="text" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7">
                            </div>
                            <div class="input-group mb-3">
                                <label for="year" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Year</label>
                                <input value="<?= $c['year'] ?>" id="year" name="year" type="text" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7" data-large-mode="true">
                            </div>
                            <div class="input-group mb-3">
                                <label for="gearbox" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Gearbox</label>
                                <select class="custom-select col-xl-9 col-lg-8 col-md-8 col-sm-7" name="gearbox" id="gearbox">
                                    <option value="<?= $c['gearbox'] ?>" selected><?= $c['gearbox'] ?></option>
                                    <option value="automatic">Automatic</option>
                                    <option value="manual">Manual</option>
                                </select>
                            </div>
                            <div class="input-group mb-3">
                                <label for="doors" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Doors</label>
                                <input value="<?= $c['doors'] ?>" id="doors" name="doors" type="text" class="form-control validate col-xl-9 col-lg-8 col-md-7 col-sm-7">
                            </div>
                            <div class="input-group mb-3">
                                <label for="fuel" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Fuel</label>
                                <input value="<?= $c['fuel'] ?>" id="fuel" name="fuel" type="text" class="form-control validate col-xl-9 col-lg-8 col-md-7 col-sm-7">
                            </div>
                            <div class="input-group mb-3">
                                <label for="seats" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Seats</label>
                                <input value="<?= $c['seats'] ?>" id="seats" name="seats" type="text" class="form-control validate col-xl-9 col-lg-8 col-md-7 col-sm-7">
                            </div>
                            <div class="input-group mb-3">
                                <label for="type" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Type</label>
                                <select class="custom-select col-xl-9 col-lg-8 col-md-8 col-sm-7" name="type" id="type">
                                    <option value="<?= $c['type'] ?>" selected><?= $c['type'] ?></option>
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
                                <input value="<?= $c['rental_rate'] ?>" id="rental" name="rental" type="number" class="form-control validate col-xl-9 col-lg-8 col-md-7 col-sm-7">
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
    <div class="col-12 col-md-6 mb-4">
        <div class="bg-white tm-block">
            <?php foreach ($car as $c) : ?>
                <div id="carsCarousel<?= $c['id'] ?>" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <?php foreach ($car_images as $index => $image) : ?>
                            <div class="carousel-item <?= ($index === 0) ? 'active' : '' ?>">
                                <div class="text-center">
                                    <a href="?car_id=<?= $c['id'] ?>&image_id=<?= $image['id'] ?>"><i class="fas fa-trash-alt tm-trash-icon"></i></a>
                                </div>
                                <img src="../assets/img/cars/<?= $image['images'] ?>" class="d-block w-100" alt="cars-<?= $c['id'] ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <a class="carousel-control-prev" href="#carsCarousel<?= $c['id'] ?>" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carsCarousel<?= $c['id'] ?>" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>

                <div class="text-center mt-3">
                    <a href="add-car-images.php?car_id=<?= $c['id'] ?>" class="btn btn-primary">Upload Image</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>

</html>