<?php include_once('includes/header.php');

// $bookings = [];
// $sql_bookings = "SELECT *, 
// pickup_location.name AS pickup_location_name, 
// pickup_location.address AS pickup_location_address, 
// return_location.name AS return_location_name, 
// return_location.address AS return_location_address, 
// rental.id as rent_id FROM `rental` 
// INNER JOIN `users` ON `rental`.`user_id` = `users`.`id` 
// INNER JOIN `cars` ON `rental`.`car_id` = `cars`.`id`
// INNER JOIN `locations` AS `pickup_location` ON `rental`.`pickup_location_id` = `pickup_location`.`id`
// INNER JOIN `locations` AS `return_location` ON `rental`.`return_location_id` = `return_location`.`id`;";
// $stm_bookings = $pdo->prepare($sql_bookings);
// $stm_bookings->execute();
// $bookings = $stm_bookings->fetchAll(PDO::FETCH_ASSOC);

$sql_orders = "SELECT * FROM `orders` INNER JOIN `product` ON `orders`.`product_id` = `product`.`id`";
$stm_orders = $pdo->prepare($sql_orders);
$stm_orders->execute();
$orders = $stm_orders->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- row -->
<div class="row tm-content-row mt-3">
    <div class="col-12">
        <div class="bg-white tm-block h-100">
            <div class="row">
                <div class="col-8">
                    <h2 class="tm-block-title d-inline-block">Order List</h2>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table align-middle overflow-scroll" data-search-align="left" id="table" data-pagination="true" data-toggle="table" data-search="true" data-searchable="true">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Product</th>
                            <th scope="col">Client Name</th>
                            <th scope="col">Client Phone</th>
                            <th scope="col">Qty</th>
                            <th scope="col">Total</th>
                            <th scope="col">Created at</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($orders as $i => $order) : ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= $order['name'] ?></td>
                                <td><?= $order['client_name'] ?></td>
                                <td><?= $order['client_phone'] ?></td>
                                <td><?= $order['order_date'] ?></td>
                                <td><?= $order['price'] ?></td>
                                <td><?= $order['qty'] ?></td>
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