<?php include_once('includes/header.php'); ?>

<?php

// Query to fetch categories and products
$query = "
    SELECT 
        company.name AS company_name,
        categories.id AS category_id, 
        categories.name AS category_name, 
        products.id AS product_id, 
        products.name AS product_name,
        products.length AS product_length,
        inventorysummary.current_qty AS product_qty 
    FROM company
    INNER JOIN categories ON company.id = categories.company_id
    LEFT JOIN products ON categories.id = products.category_id
    LEFT JOIN inventorysummary ON products.id = inventorysummary.product_id
    ORDER BY company.name, categories.name, CAST(products.length AS DOUBLE) DESC;
";

// Prepare and execute the query
$stmt = $pdo->prepare($query);
$stmt->execute();

// Fetch all results as an associative array
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Organize data into categories and products
$data = [];
foreach ($results as $row) {
    $company_name = $row['company_name'];
    $category_name = $row['category_name'];
    $product_id = $row['product_id'];
    $product_name = $row['product_name'];
    $product_length = $row['product_length'];
    $product_qty = $row['product_qty'];

    if (!isset($data[$company_name])) {
        $data[$company_name] = [];
    }
    if (!isset($data[$company_name][$category_name])) {
        $data[$company_name][$category_name] = [];
    }
    if ($product_name) {
        $data[$company_name][$category_name][] = [
            'id' => $product_id,
            'name' => $product_name,
            'length' => $product_length,
            'qty' => $product_qty,
        ];
    }
}

// Output $data structure if needed for debugging
// print_r($data);


$transaction_product_errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST' && (isset($_POST['outgoing_btn']) || isset($_POST['incoming_btn']))) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['new_quantity'];
    $transaction_type = $_POST['transaction_type']; // 'incoming' or 'outgoing'

    if (empty($product_id) || empty($new_quantity) || empty($transaction_type)) {
        $transaction_product_errors[] = "Please fill in all the fields.";
    } else {
        // Get the current quantity from InventorySummary
        $currentQtySql = "SELECT current_qty, `products`.`name` as `product_name` FROM `inventorysummary` INNER JOIN `products` ON `inventorysummary`.`product_id` = `products`.`id` WHERE `inventorysummary`.`product_id` = ?";
        $stmt = $pdo->prepare($currentQtySql);
        $stmt->execute([$product_id]);

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $current_qty = $row['current_qty'];
            $product_name = $row['product_name'];

            // Update the quantity based on transaction type
            switch ($transaction_type) {
                case 'incoming':
                    $updated_qty = $current_qty + $new_quantity;
                    break;
                case 'outgoing':
                    if ($new_quantity > $current_qty) {
                        $transaction_product_errors[] = "Error: Insufficient stock for this operation.";
                    } else {
                        $updated_qty = $current_qty - $new_quantity;

                        // Insert the sale record into the sales table
                        $insertSaleQuery = "
                            INSERT INTO sales (product_id, quantity, sale_date)
                            VALUES (:product_id, :quantity, CURDATE());";
                        $stmt = $pdo->prepare($insertSaleQuery);
                        $stmt->execute([
                            ':product_id' => $product_id,
                            ':quantity' => $new_quantity,
                        ]);

                        // Check if the updated quantity is below the threshold
                        if ($updated_qty < 8) {
                            // WhatsApp Notification
                            $sql_access_token = "SELECT `bearer` FROM `system`";
                            $sql_access_token = $pdo->prepare($sql_access_token);
                            $sql_access_token->execute();
                            $access_token = $sql_access_token->fetch(PDO::FETCH_ASSOC);
                            // API URL
                            $api_url = "https://graph.facebook.com/v21.0/482636938268759/messages";

                            // Access token
                            $access_token = $access_token['bearer'];

                            // Recipient's phone number
                            $to = "38970832727";

                            // Message payload for the custom template
                            $data = [
                                "messaging_product" => "whatsapp",
                                "to" => $to,
                                "type" => "template",
                                "template" => [
                                    "name" => "low_stock_alert_titan_cink", // Replace with your approved template name
                                    "language" => [
                                        "code" => "en_US"
                                    ],
                                    "components" => [
                                        [
                                            "type" => "body",
                                            "parameters" => [
                                                ["type" => "text", "text" => $product_name], // Placeholder {{1}}
                                                ["type" => "text", "text" => (string)$updated_qty] // Placeholder {{2}}
                                            ]
                                        ]
                                    ]
                                ]
                            ];

                            // Initialize cURL
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $api_url);
                            curl_setopt($ch, CURLOPT_POST, true);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                "Authorization: Bearer $access_token",
                                "Content-Type: application/json"
                            ]);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

                            // Execute the request
                            $response = curl_exec($ch);
                            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                            // Check response
                            if ($http_code !== 200) {
                                echo "Failed to send WhatsApp alert message. Response: " . $response;
                            }

                            curl_close($ch);
                        }
                    }
                    break;
                default:
                    $transaction_product_errors[] = "Invalid transaction type.";
            }

            // Proceed only if there are no errors
            if (empty($transaction_product_errors)) {
                // Update InventorySummary
                $updateQtySql = "UPDATE inventorysummary SET current_qty = ? WHERE product_id = ?";
                $updateStmt = $pdo->prepare($updateQtySql);

                if ($updateStmt->execute([$updated_qty, $product_id])) {
                    // Log the transaction in Transactions table
                    $insertTransactionSql = "INSERT INTO transactions (product_id, type, quantity) VALUES (?, ?, ?)";
                    $transactionStmt = $pdo->prepare($insertTransactionSql);
                    $transactionStmt->execute([$product_id, $transaction_type, $new_quantity]);

                    echo "Product quantity updated successfully!";
                } else {
                    $transaction_product_errors[] = "Error updating quantity.";
                }
            }
        } else {
            $transaction_product_errors[] = "Product not found.";
        }
    }

    // Redirect to the appropriate page after all operations are done
    if (empty($transaction_product_errors)) {
        header("Location: index.php?action=product_qty&status=success");
    } else {
        print_r($transaction_product_errors);
        // Optionally handle errors by redirecting or displaying error messages
        // header("Location: index.php?action=product_qty&status=error");
    }
    exit();
}

?>
<?php
if (isset($_GET['action']) && isset($_GET['status'])) {
    $alerts = [
        'product_qty' => [
            'success' => 'Product quantity updated successfully!',
            'error' => 'Failed to update product quantity.',
        ],
    ];

    // Get the appropriate alert based on action and status
    $action = $_GET['action'];
    $status = $_GET['status'];

    $alert = $alerts[$action][$status] ?? null;
}
?>
<div class="container-fluid">
    <?php if (!empty($alert)) : ?>
        <div class="alert alert-<?= htmlspecialchars($status) === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show mt-3" role="alert">
            <strong class="text-uppercase me-1"><?= htmlspecialchars($status) ?>!</strong><?= htmlspecialchars($alert) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <div class="row mt-4 col-12 justify-content-center p-0 m-0">
        <div class="col-12 col-md-4 col-xl-4 m-2">
            <a href="company.php" class="text-decoration-none">
                <div class="card bg-primary">
                    <div class="card-body text-center">
                        <h5 class="card-title"><img src="./assets/icons/package.svg" alt=""></h5>
                        <h5 class="card-title text-white">Goods</h5>
                        <!-- <p class="card-text text-white">776 / 6,340.63</p> -->
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-md-4 col-xl-4 m-2">
            <a href="documents.php" class="text-decoration-none">
                <div class="card bg-primary">
                    <div class="card-body text-center">
                        <h5 class="card-title"><img src="./assets/icons/documents.svg" alt=""></h5>
                        <h5 class="card-title text-white">Documents</h5>
                        <!-- <p class="card-text text-white">776</p> -->
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-md-4 col-xl-4 m-2">
            <button data-bs-toggle="modal" data-bs-target="#packagePlus" class="w-100 p-0 border border-0 bg-transparent">
                <div class="card bg-primary">
                    <div class="card-body text-center">
                        <h5 class="card-title"><img src="./assets/icons/package-plus.svg" alt=""></h5>
                        <h5 class="card-title text-white">Incoming</h5>
                    </div>
                </div>
            </button>
        </div>
        <div class="col-12 col-md-4 col-xl-4 m-2">
            <button data-bs-toggle="modal" data-bs-target="#packageMinus" class="w-100 p-0 border border-0 bg-transparent">
                <div class="card bg-primary">
                    <div class="card-body text-center">
                        <h5 class="card-title"><img src="./assets/icons/package-minus.svg" alt=""></h5>
                        <h5 class="card-title text-white">Outgoing</h5>
                    </div>
                </div>
            </button>
        </div>
        <div class="col-12 col-md-4 col-xl-4 m-2">
            <a href="reports.php" class="text-decoration-none">
                <div class="card bg-primary">
                    <div class="card-body text-center">
                        <h5 class="card-title"><img src="./assets/icons/reports.svg" alt=""></h5>
                        <h5 class="card-title text-white">Reports</h5>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="packageMinus" tabindex="-1" aria-labelledby="packageMinusLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="packageMinusLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="categoryProductSelect" class="form-label">Select Product</label>
                            <div class="d-flex flex-column">
                                <select id="categorySelectOutgoing" class="form-select">
                                    <option value="">Select a category</option>
                                    <?php foreach ($data as $company => $categories): ?>
                                        <optgroup label="<?= htmlspecialchars($company) ?>">
                                            <?php foreach ($categories as $category => $products): ?>
                                                <option value="<?= htmlspecialchars($category) ?>" data-company="<?= htmlspecialchars($company) ?>">
                                                    <?= htmlspecialchars($category) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </optgroup>
                                    <?php endforeach; ?>
                                </select>

                                <select id="productSelectOutgoing" name="product_id" class="form-select mt-2" disabled>
                                    <option value="">Select a product</option>
                                </select>

                                <div id="qtyDisplay" class="mt-2 text-dark"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="productQuantity" class="form-label">Set Quantity</label>
                            <input type="number" name="new_quantity" class="form-control">
                            <input type="hidden" name="transaction_type" value="outgoing">
                        </div>
                        <div class="mb-3">
                            <p id="qtyDisplayOutgoing"></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="outgoing_btn" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="packagePlus" tabindex="-1" aria-labelledby="packagePlusLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="packagePlusLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="categoryProductSelect" class="form-label">Select Product</label>
                            <div class="d-flex flex-column">
                                <select id="categorySelectIncoming" class="form-select">
                                    <option value="">Select a category</option>
                                    <?php foreach ($data as $company => $categories): ?>
                                        <optgroup label="<?= htmlspecialchars($company) ?>">
                                            <?php foreach ($categories as $category => $products): ?>
                                                <option value="<?= htmlspecialchars($category) ?>" data-company="<?= htmlspecialchars($company) ?>">
                                                    <?= htmlspecialchars($category) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </optgroup>
                                    <?php endforeach; ?>
                                </select>

                                <select id="productSelectIncoming" name="product_id" class="form-select mt-2" disabled>
                                    <option value="">Select a product</option>
                                </select>

                                <div id="qtyDisplay" class="mt-2 text-dark"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="productQuantity" class="form-label">Set Quantity</label>
                            <input type="number" name="new_quantity" class="form-control">
                            <input type="hidden" name="transaction_type" value="incoming">
                        </div>
                        <div class="mb-3">
                            <p id="qtyDisplayIncoming"></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="incoming_btn" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="assets/js/jquery-1.11.0.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.22.4/dist/bootstrap-table.min.js"></script>

<script>
    // Data passed from PHP to JavaScript
    const data = <?= json_encode($data) ?>;

    // Function to handle category selection and update products
    function setupDropdowns(categorySelectId, productSelectId, qtyDisplayId) {
        const categorySelect = document.getElementById(categorySelectId);
        const productSelect = document.getElementById(productSelectId);
        const qtyDisplay = document.getElementById(qtyDisplayId);

        categorySelect.addEventListener("change", function() {
            const selectedCategory = this.value;
            const selectedOption = this.options[this.selectedIndex];
            const company = selectedOption.getAttribute("data-company");

            // Clear previous products
            productSelect.innerHTML = '<option value="">Select a product</option>';
            productSelect.disabled = true;
            qtyDisplay.textContent = "";

            if (selectedCategory && company && data[company][selectedCategory]) {
                data[company][selectedCategory].forEach(product => {
                    const option = document.createElement("option");
                    option.value = product.id;
                    option.textContent = `${product.name} (${product.length})`;
                    productSelect.appendChild(option);
                });

                productSelect.disabled = false;
            }
        });

        productSelect.addEventListener("change", function() {
            const selectedCategory = categorySelect.value;
            const selectedProductId = this.value;
            const selectedOption = categorySelect.options[categorySelect.selectedIndex];
            const company = selectedOption.getAttribute("data-company");

            if (selectedProductId) {
                const selectedProduct = data[company][selectedCategory].find(product => product.id == selectedProductId);
                const qty = selectedProduct ? selectedProduct.qty : 0;
                qtyDisplay.textContent = `Qty: ${qty}`;
                qtyDisplay.classList.remove("text-danger", "text-dark");
                qtyDisplay.classList.add(qty < 8 ? "text-danger" : "text-dark");
            } else {
                qtyDisplay.textContent = "";
                qtyDisplay.classList.remove("text-danger", "text-dark");
                qtyDisplay.classList.add("text-dark");
            }
        });
    }

    // Setup dropdowns for both modals
    setupDropdowns("categorySelectIncoming", "productSelectIncoming", "qtyDisplayIncoming");
    setupDropdowns("categorySelectOutgoing", "productSelectOutgoing", "qtyDisplayOutgoing");
</script>


</body>

</html>