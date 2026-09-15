<?php

require_once "../components/navbar.php";
require_once "../../classes.php";

session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['admin'] == false) {
    header("Location: /store-project/auth/login.php");
    exit();
}

$products = Product::getAll();

if (isset($_GET['delete'])) {
    $productId = (int) $_GET['delete'];

    $product = Product::find($productId);

    if ($product) {
        $product->delete();
        header("Location: products-table.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet"
          href="../../css/bootstrap.css">

    <title>Online Store - Products</title>

</head>

<body class="bg-light">

    <?php render_navbar(['active' => 'products']); ?>


    <div class="container my-5">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <h2 class="fw-bold m-0">
                Products
            </h2>

            <a
                href="create-product.php"
                class="btn btn-primary rounded-pill px-4"
            >
                + Add Product
            </a>

        </div>


        <div class="card border-0 shadow-sm rounded-3">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead>

                            <tr>

                                <th>ID</th>

                                <th>Image</th>

                                <th>Name</th>

                                <th>Description</th>

                                <th>Price</th>

                                <th>Stock</th>

                                <th>Actions</th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php if (empty($products)): ?>

                                <tr>

                                    <td
                                        colspan="7"
                                        class="text-center py-4"
                                    >
                                        No products found.
                                    </td>

                                </tr>

                            <?php else: ?>

                                <?php foreach ($products as $product): ?>

                                    <tr>

                                        <td>
                                            <?= $product->id; ?>
                                        </td>


                                        <td>

                                            <?php if ($product->image): ?>

                                                <img
                                                    src="<?= htmlspecialchars($product->image); ?>"
                                                    alt="<?= htmlspecialchars($product->name); ?>"
                                                    width="60"
                                                    height="60"
                                                    class="rounded"
                                                    style="object-fit: cover;"
                                                >

                                            <?php else: ?>

                                                <span class="text-muted">
                                                    No Image
                                                </span>

                                            <?php endif; ?>

                                        </td>


                                        <td class="fw-semibold">

                                            <?= htmlspecialchars($product->name); ?>

                                        </td>


                                        <td>

                                            <?= htmlspecialchars(
                                                $product->description ?? ''
                                            ); ?>

                                        </td>


                                        <td>

                                            $<?= number_format(
                                                $product->price,
                                                2
                                            ); ?>

                                        </td>


                                        <td>

                                            <?= $product->stock; ?>

                                        </td>


                                        <td>

                                            <a
                                                href="update-product.php?id=<?= $product->id; ?>"
                                                class="btn btn-sm btn-outline-primary rounded-pill"
                                            >
                                                Edit
                                            </a>


                                            <a
                                                href="products-table.php?delete=<?= $product->id; ?>"
                                                class="btn btn-sm btn-outline-danger rounded-pill"
                                                onclick="return confirm('Are you sure you want to delete this product?');"
                                            >
                                                Delete
                                            </a>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</body>

</html>