<?php include_once('includes/header.php');

$cars = [];
$sql = "SELECT * FROM `cars`";
$stm = $pdo->prepare($sql);
$stm->execute();
$cars = $stm->fetchAll(PDO::FETCH_ASSOC);

$locations = [];
$sql_location = "SELECT * FROM `locations`";
$stm_location = $pdo->prepare($sql_location);
$stm_location->execute();
$locations = $stm_location->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['action']) && ($_GET['action'] === 'delete') && isset($_GET['type']) && ($_GET['type'] === 'car')) {
    if (isset($_GET['car_id'])) {
        $car_id = $_GET['car_id'];
        $sql_delete_car = "DELETE FROM `cars` WHERE id = ?";
        $stm_delete_car = $pdo->prepare($sql_delete_car);
        if ($stm_delete_car->execute([$car_id])) {
            header('Location: cars.php?action=car_delete');
        }
    }
}
if (isset($_GET['action']) && ($_GET['action'] === 'delete') && isset($_GET['type']) && ($_GET['type'] === 'location')) {
    if (isset($_GET['location_id'])) {
        $location_id = $_GET['location_id'];
        $sql_delete_location = "DELETE FROM `locations` WHERE id = ?";
        $stm_delete_location = $pdo->prepare($sql_delete_location);
        if ($stm_delete_location->execute([$location_id])) {
            header('Location: cars.php?action=location_delete');
        }
    }
}
?>



<!-- row -->
<?php
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'car_delete':
            $alert = 'Car delete successfully';
            break;
        case 'location_delete':
            $alert = 'Location delete successfully';
            break;
        case 'car_edit':
            $alert = 'Car edit successfully';
            break;
        case 'location_edit':
            $alert = 'Location edit successfully';
            break;
        case 'location_add':
            $alert = 'Location added successfully';
            break;
        case 'car_images':
            $alert = 'Car images uploaded successfully';
            break;
    }
}
?>
<?php if (isset($_GET['action'])) : ?>
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        <?= $alert; ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>
<div class="row tm-content-row tm-mt-big mt-3">
    <div class="col-xl-8 col-lg-12 tm-md-12 tm-sm-12 tm-col">
        <div class="bg-white tm-block h-100">
            <div class="row align-items-center justify-content-between">
                <div class="col-md-8 col-4">
                    <h2 class="tm-block-title d-inline-block mb-0">Cars</h2>
                </div>
                <div class="col-md-4 col-8 d-flex justify-content-end">
                    <a href="add-car.php" class="btn btn-small btn-primary">Add New Car</a>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped tm-table-striped-even mt-3">
                    <thead>
                        <tr class="tm-bg-gray">
                            <th scope="col">No.</th>
                            <th scope="col">Make Model</th>
                            <th scope="col" class="text-center">Year</th>
                            <th scope="col" class="text-center">Gearbox</th>
                            <th scope="col">Doors</th>
                            <th scope="col">Fuel</th>
                            <th scope="col">Seats</th>
                            <th scope="col">Type</th>
                            <th scope="col">Rental Rate</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1;
                        foreach ($cars as $car) : ?>
                            <tr>
                                <td class="text-center"><?= $i++ ?></td>
                                <td class="tm-car-name"><?= $car['make'] . " " . $car['model'] ?></td>
                                <td class="text-center"><?= $car['year'] ?></td>
                                <td class="text-center"><?= $car['gearbox'] ?></td>
                                <td class="text-center"><?= $car['doors'] ?></td>
                                <td><?= $car['fuel'] ?></td>
                                <td class="text-center"><?= $car['seats'] ?></td>
                                <td><?= $car['type'] ?></td>
                                <td><?= $car['rental_rate'] ?></td>
                                <td class="text-center">
                                    <a href="?action=delete&type=car&car_id=<?= $car['id'] ?>"><i class="fas fa-trash-alt tm-trash-icon"></i></a>
                                    <a href="edit-car.php?car_id=<?= $car['id'] ?>"><i class="fas fa-edit tm-trash-icon"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-12 tm-md-12 tm-sm-12 tm-col">
        <div class="bg-white tm-block h-100">
            <div class="row align-items-center justify-content-between">
                <div class="col-4">
                    <h2 class="tm-block-title d-inline-block mb-0">Location</h2>
                </div>
                <div class="col-8 d-flex justify-content-end">
                    <a href="add-location.php" class="btn btn-small btn-primary">Add New Location</a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mt-3 text-nowrap">
                    <thead>
                        <tr class="tm-bg-gray">
                            <th scope="col">No.</th>
                            <th scope="col">Location</th>
                            <th scope="col" class="text-center">Address</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1;
                        foreach ($locations as $location) : ?>
                            <tr>
                                <td class="text-center"><?= $i++ ?></td>
                                <td class="tm-location-name"><?= $location['name'] ?></td>
                                <td><?= $location['address'] ?></td>
                                <td class="text-center">
                                    <a href="?action=delete&type=location&location_id=<?= $location['id'] ?>"><i class="fas fa-trash-alt tm-trash-icon"></i></a>
                                    <a href="edit-location.php?location_id=<?= $location['id'] ?>"><i class="fas fa-edit tm-trash-icon"></i></a>
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