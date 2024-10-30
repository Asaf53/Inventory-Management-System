<?php include_once('includes/header.php');
$car_image_errors = [];
$car_id = $_GET['car_id'];
if (isset($_POST['upload_btn'])) {
    $car_id = $_POST['car_id'];
    $image = $_FILES['images'];
    if ($car_id && isset($_FILES['images'])) {
        for ($i = 0; $i < count($image['name']); $i++) {
            $filename = time() . "_" . $image['name'][$i];
            move_uploaded_file($image['tmp_name'][$i], '../assets/img/cars/' . $filename);

            if (empty($car_image_errors)) {
                $sql = "INSERT INTO `cars_images`(`car_id`, `images`) VALUES (?, ?)";
                $stm = $pdo->prepare($sql);
                if ($stm->execute([$car_id, $filename])) {
                    header("Location: cars.php?action=car_images");
                } else {
                    $car_image_errors[] = "Something went wrong!!";
                }
            }
        }
    }
}
?>

<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
    <div class="frame-upload">
        <div class="center-upload">
            <?php if (!empty($car_image_errors)) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php foreach ($car_image_errors as $errors) : ?>
                        <?= $errors ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div class="title-upload">
                <h1 class="h1-upload">Drop file to upload</h1>
            </div>
            <div class="dropzone-upload">
                <img src="http://100dayscss.com/codepen/upload.svg" class="upload-icon" />
                <input type="file" name="images[]" class="upload-input" multiple>
                <input type="text" hidden name="car_id" value="<?= $car_id ?>">
            </div>
            <button type="submit" class="btn btn-primary" name="upload_btn">Upload file</button>
        </div>
    </div>
</form>