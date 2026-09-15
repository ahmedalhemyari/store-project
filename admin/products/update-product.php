<?php

require_once "../components/navbar.php";
require_once "../../classes.php";

session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['admin'] == false) {
    header("Location: /store-project/auth/login.php");
    exit();
}


/*
 * Get Product ID
 */

if (!isset($_GET['id'])) {
    header("Location: products-table.php");
    exit();
}

$productId = (int) $_GET['id'];

$product = Product::find($productId);

if (!$product) {
    header("Location: products-table.php");
    exit();
}


$errors = [];
$success = "";


/*
 * Update Product
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');

    $description = trim($_POST['description'] ?? '');

    $price = (float) ($_POST['price'] ?? 0);

    $stock = (int) ($_POST['stock'] ?? 0);


    /*
     * Validation
     */

    if (empty($name)) {
        $errors[] = "Product name is required.";
    }

    if ($price < 0) {
        $errors[] = "Price cannot be negative.";
    }

    if ($stock < 0) {
        $errors[] = "Stock cannot be negative.";
    }


    /*
     * Image
     */

    $image = $product->image;

    if (
        isset($_FILES['image']) &&
        $_FILES['image']['error'] === UPLOAD_ERR_OK
    ) {

        $uploadDirectory = "../../images/";

        $fileName = basename($_FILES['image']['name']);

        $extension = strtolower(
            pathinfo($fileName, PATHINFO_EXTENSION)
        );

        $allowedExtensions = [
            'jpg',
            'jpeg',
            'png',
            'webp'
        ];


        if (!in_array($extension, $allowedExtensions)) {

            $errors[] =
                "Only JPG, JPEG, PNG and WEBP images are allowed.";

        } else {

            $newFileName =
                uniqid('product_', true) .
                '.' .
                $extension;

            $imagePath =
                $uploadDirectory .
                $newFileName;


            if (
                move_uploaded_file(
                    $_FILES['image']['tmp_name'],
                    $imagePath
                )
            ) {

                $image =
                    "/store-project/images/" .
                    $newFileName;

            } else {

                $errors[] =
                    "Failed to upload image.";
            }
        }
    }


    /*
     * Save Changes
     */

    if (empty($errors)) {

        $updated = $product->update(
            $name,
            $description ?: null,
            $price,
            $image,
            $stock
        );


        if ($updated) {

            $success =
                "Product updated successfully!";

            $product =
                Product::find($productId);

        } else {

            $errors[] =
                "Failed to update product.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <link
        rel="stylesheet"
        href="../../css/bootstrap.css"
    >

    <title>
        Online Store - Update Product
    </title>

</head>


<body class="bg-light">


    <?php render_navbar(['active' => 'products']); ?>


    <div class="container my-5">

        <div class="row justify-content-center">

            <div class="col-lg-8">


                <div
                    class="d-flex justify-content-between align-items-center mb-4"
                >

                    <h2 class="fw-bold m-0">
                        Update Product
                    </h2>


                    <a
                        href="products-table.php"
                        class="btn btn-outline-secondary rounded-pill px-4"
                    >

                        &larr; Back to Products

                    </a>

                </div>


                <?php if (!empty($success)): ?>

                    <div class="alert alert-success rounded-3">

                        <?= htmlspecialchars($success); ?>

                    </div>

                <?php endif; ?>


                <?php if (!empty($errors)): ?>

                    <div class="alert alert-danger rounded-3">

                        <ul class="mb-0">

                            <?php foreach ($errors as $error): ?>

                                <li>
                                    <?= htmlspecialchars($error); ?>
                                </li>

                            <?php endforeach; ?>

                        </ul>

                    </div>

                <?php endif; ?>


                <div
                    class="card border-0 shadow-sm rounded-3 p-4 p-md-5"
                >


                    <form
                        action=""
                        method="POST"
                        enctype="multipart/form-data"
                    >


                        <div class="mb-3">

                            <label
                                for="name"
                                class="form-label fw-semibold"
                            >
                                Product Name
                            </label>


                            <input
                                type="text"
                                class="form-control"
                                id="name"
                                name="name"
                                value="<?= htmlspecialchars($product->name); ?>"
                                required
                            >

                        </div>


                        <div class="mb-3">

                            <label
                                for="description"
                                class="form-label fw-semibold"
                            >
                                Description
                            </label>


                            <textarea
                                class="form-control"
                                id="description"
                                name="description"
                                rows="4"
                            ><?= htmlspecialchars($product->description ?? ''); ?></textarea>

                        </div>


                        <div class="row g-3">


                            <div class="col-md-6">

                                <label
                                    for="price"
                                    class="form-label fw-semibold"
                                >
                                    Price
                                </label>


                                <input
                                    type="number"
                                    class="form-control"
                                    id="price"
                                    name="price"
                                    value="<?= htmlspecialchars($product->price); ?>"
                                    min="0"
                                    step="0.01"
                                    required
                                >

                            </div>


                            <div class="col-md-6">

                                <label
                                    for="stock"
                                    class="form-label fw-semibold"
                                >
                                    Stock
                                </label>


                                <input
                                    type="number"
                                    class="form-control"
                                    id="stock"
                                    name="stock"
                                    value="<?= htmlspecialchars($product->stock); ?>"
                                    min="0"
                                    required
                                >

                            </div>

                        </div>


                        <div class="mb-4 mt-3">

                            <label
                                for="image"
                                class="form-label fw-semibold"
                            >
                                Product Image
                            </label>


                            <input
                                type="file"
                                class="form-control"
                                id="image"
                                name="image"
                                accept=".jpg,.jpeg,.png,.webp"
                            >


                            <?php if ($product->image): ?>

                                <div class="mt-3">

                                    <p class="text-muted mb-2">
                                        Current Image:
                                    </p>


                                    <img
                                        src="<?= htmlspecialchars($product->image); ?>"
                                        alt="<?= htmlspecialchars($product->name); ?>"
                                        width="100"
                                        height="100"
                                        class="rounded"
                                        style="object-fit: cover;"
                                    >

                                </div>

                            <?php endif; ?>

                        </div>


                        <div
                            class="d-flex justify-content-end gap-2"
                        >

                            <a
                                href="products-table.php"
                                class="btn btn-light rounded-pill px-4"
                            >
                                Cancel
                            </a>


                            <button
                                type="submit"
                                class="btn btn-primary rounded-pill px-4 fw-semibold"
                            >
                                Update Product
                            </button>

                        </div>


                    </form>


                </div>

            </div>

        </div>

    </div>


</body>

</html>