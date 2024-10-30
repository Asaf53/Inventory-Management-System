<?php include_once('includes/header.php');

$bookings = [];
$sql_bookings = "SELECT *, 
pickup_location.name AS pickup_location_name, 
pickup_location.address AS pickup_location_address, 
return_location.name AS return_location_name, 
return_location.address AS return_location_address, 
rental.id as rent_id FROM `rental` 
INNER JOIN `users` ON `rental`.`user_id` = `users`.`id` 
INNER JOIN `cars` ON `rental`.`car_id` = `cars`.`id`
INNER JOIN `locations` AS `pickup_location` ON `rental`.`pickup_location_id` = `pickup_location`.`id`
INNER JOIN `locations` AS `return_location` ON `rental`.`return_location_id` = `return_location`.`id`;";
$stm_bookings = $pdo->prepare($sql_bookings);
$stm_bookings->execute();
$bookings = $stm_bookings->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['action']) && ($_GET['action'] === 'delete') && isset($_GET['type']) && ($_GET['type'] === 'rent')) {
    if (isset($_GET['rent_id'])) {
        $rent_id = $_GET['rent_id'];
        $sql_delete_rent = "DELETE FROM `rental` WHERE id = ?";
        $stm_delete_rent = $pdo->prepare($sql_delete_rent);
        if ($stm_delete_rent->execute([$rent_id])) {
            header('Location: index.php');
        }
    }
}

if (isset($_GET['action']) && ($_GET['action'] === 'status') && isset($_GET['type']) && ($_GET['type'] === 'rent')) {
    if (isset($_GET['rent_id'])) {
        $rent_id = $_GET['rent_id'];
        $sql_update_rent = "UPDATE `rental` SET `status` = 'Confirmed' WHERE id = ?";
        $stm_update_rent = $pdo->prepare($sql_update_rent);
        if ($stm_update_rent->execute([$rent_id])) {
            header('Location: index.php');
        }
    }
}
?>
<!-- row -->
<div class="row tm-content-row mt-3">
    <div class="col-12">
        <div class="bg-white tm-block h-100">
            <div class="row">
                <div class="col-8">
                    <h2 class="tm-block-title d-inline-block">Bookings List</h2>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table align-middle overflow-scroll" data-search-align="left" id="table" data-pagination="true" data-toggle="table" data-search="true" data-searchable="true">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Book Number</th>
                            <th scope="col">Customer</th>
                            <th scope="col">Customer Email</th>
                            <th scope="col">Customer Phone</th>
                            <th scope="col">Car</th>
                            <th scope="col">Pick Up Location</th>
                            <th scope="col">Return Location</th>
                            <th scope="col">Pick Up Date</th>
                            <th scope="col">Return Date</th>
                            <th scope="col">Total</th>
                            <th scope="col">Status</th>
                            <th scope="col">Created at</th>
                            <th scope="col" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1;
                        foreach ($bookings as $book) : ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><b><?= $book['rental_number'] ?></b></td>
                                <td><?= $book['fullname'] ?></td>
                                <td><?= $book['email'] ?></td>
                                <td><?= $book['phone'] ?></td>
                                <td><?= $book['make'] . " " . $book['model'] ?></td>
                                <td><?= $book['pickup_location_name'] . " " . $book['pickup_location_address'] ?></td>
                                <td><?= $book['return_location_name'] . " " . $book['return_location_address'] ?></td>
                                <td><?= $book['start_date'] ?></td>
                                <td><?= $book['end_date'] ?></td>
                                <td><?= $book['total_cost'] ?>&euro;</td>
                                <td><?= $book['status'] ?></td>
                                <td><?= $book['created_at'] ?></td>
                                <td class="text-center">
                                    <a href="?action=status&type=rent&rent_id=<?= $book['rent_id'] ?>"><i class="fa fa-check text-success"></i></a>
                                    <a href="?action=delete&type=rent&rent_id=<?= $book['rent_id'] ?>"><i class="fas fa-trash-alt text-danger"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- <div class="tm-col tm-col-big">
        <div class="bg-white tm-block h-100">
            <div class="row">
                <div class="col-8">
                    <h2 class="tm-block-title d-inline-block">Top Product List</h2>

                </div>
                <div class="col-4 text-right">
                    <a href="products.php" class="tm-link-black">View All</a>
                </div>
            </div>
            <ol class="tm-list-group tm-list-group-alternate-color tm-list-group-pad-big">
                <li class="tm-list-group-item">
                    Donec eget libero
                </li>
                <li class="tm-list-group-item">
                    Nunc luctus suscipit elementum
                </li>
                <li class="tm-list-group-item">
                    Maecenas eu justo maximus
                </li>
                <li class="tm-list-group-item">
                    Pellentesque auctor urna nunc
                </li>
                <li class="tm-list-group-item">
                    Sit amet aliquam lorem efficitur
                </li>
                <li class="tm-list-group-item">
                    Pellentesque auctor urna nunc
                </li>
                <li class="tm-list-group-item">
                    Sit amet aliquam lorem efficitur
                </li>
            </ol>
        </div>
    </div> -->
    <!-- <div class="tm-col tm-col-small">
        <div class="bg-white tm-block h-100">
            <h2 class="tm-block-title">Upcoming Tasks</h2>
            <ol class="tm-list-group">
                <li class="tm-list-group-item">List of tasks</li>
                <li class="tm-list-group-item">Lorem ipsum doloe</li>
                <li class="tm-list-group-item">Read reports</li>
                <li class="tm-list-group-item">Write email</li>

                <li class="tm-list-group-item">Call customers</li>
                <li class="tm-list-group-item">Go to meeting</li>
                <li class="tm-list-group-item">Weekly plan</li>
                <li class="tm-list-group-item">Ask for feedback</li>

                <li class="tm-list-group-item">Meet Supervisor</li>
                <li class="tm-list-group-item">Company trip</li>
            </ol>
        </div>
    </div> -->
</div>
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.22.4/dist/bootstrap-table.min.js"></script>
</body>

</html>