<?php include_once('includes/header.php'); ?>

<div class="container-fluid">
    <div class="row mt-4 col-12 justify-content-center p-0 m-0">
        <div class="col-12 col-md-4 col-xl-4 m-2">
            <a href="product.php" class="text-decoration-none">
                <div class="card bg-primary">
                    <div class="card-body text-center">
                        <h5 class="card-title"><img src="./assets/icons/package.svg" alt=""></h5>
                        <h5 class="card-title text-white">Goods</h5>
                        <p class="card-text text-white">776 / 6,340.63</p>
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
                        <p class="card-text text-white">776</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-md-4 col-xl-4 m-2">
            <button data-bs-toggle="modal" data-bs-target="#packagePlus" class="w-100 p-0 border border-0 bg-transparent">
                <div class="card bg-primary">
                    <div class="card-body text-center">
                        <h5 class="card-title"><img src="./assets/icons/package-plus.svg" alt=""></h5>
                    </div>
                </div>
            </button>
        </div>
        <div class="col-12 col-md-4 col-xl-4 m-2">
            <button data-bs-toggle="modal" data-bs-target="#packageMinus" class="w-100 p-0 border border-0 bg-transparent">
                <div class="card bg-primary">
                    <div class="card-body text-center">
                        <h5 class="card-title"><img src="./assets/icons/package-minus.svg" alt=""></h5>
                    </div>
                </div>
            </button>
        </div>
        <div class="col-12 col-md-4 col-xl-4 m-2">
            <a href="#" class="text-decoration-none">
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
                <form>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="categoryProductSelect" class="form-label">Select Product</label>
                            <select class="form-select" id="categoryProductSelect">
                                <?php foreach ($data as $category => $products): ?>
                                <optgroup label="<?= htmlspecialchars($category) ?>">
                                    <?php foreach ($products as $product): ?>
                                    <option value="<?= htmlspecialchars($product) ?>"><?= htmlspecialchars($product) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </optgroup>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="productQuantity" class="form-label">Set Quantity</label>
                            <input type="number" class="form-control" id="productQuantity" placeholder="0.0">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
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
                <form>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="categoryProductSelect" class="form-label">Select Product</label>
                            <select class="form-select" id="categoryProductSelect">
                                <?php foreach ($data as $category => $products): ?>
                                <optgroup label="<?= htmlspecialchars($category) ?>">
                                    <?php foreach ($products as $product): ?>
                                    <option value="<?= htmlspecialchars($product) ?>"><?= htmlspecialchars($product) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </optgroup>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="productQuantity" class="form-label">Set Quantity</label>
                            <input type="number" class="form-control" id="productQuantity" placeholder="0.0">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="assets/js/jquery-1.11.0.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.22.4/dist/bootstrap-table.min.js"></script>
</body>

</html>