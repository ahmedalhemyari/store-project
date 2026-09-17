<?php

require_once "../components/navbar.php";
require_once "../../classes.php";

session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['admin'] == false) {
    header("Location: /auth/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: users-table.php");
    exit();
}

$userId = (int) $_GET['id'];

$user = User::find($userId);

if (!$user) {
    header("Location: users-table.php");
    exit();
}

$errors = [];
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';

    if (empty($name)) {
        $errors[] = "Name is required.";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid email address is required.";
    }

    if (!empty($newPassword) && empty($currentPassword)) {
        $errors[] = "Current password is required to change the password.";
    }

    if (empty($errors)) {

        $success = $user->update(
            $name,
            $email,
            $phone,
            $currentPassword ?: null,
            $newPassword ?: null
        );

        if ($success) {
            $successMessage = "User updated successfully!";
            $user = User::find($userId);
        } else {
            $errors[] = "Error updating user.";
        }
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

    <title>Online Store - Update User</title>

</head>

<body class="bg-light">

    <?php render_navbar(['active' => 'users']); ?>


    <div class="container my-5">

        <div class="row justify-content-center">

            <div class="col-lg-8">

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <h2 class="fw-bold m-0">
                        Update User
                    </h2>

                    <a href="users-table.php"
                       class="btn btn-outline-secondary rounded-pill px-4">

                        &larr; Back to Users

                    </a>

                </div>


                <?php if (!empty($successMessage)): ?>

                    <div class="alert alert-success rounded-3 mb-4">

                        <?= htmlspecialchars($successMessage); ?>

                    </div>

                <?php endif; ?>


                <?php if (!empty($errors)): ?>

                    <div class="alert alert-danger rounded-3 mb-4">

                        <ul class="mb-0 ps-3">

                            <?php foreach ($errors as $error): ?>

                                <li>
                                    <?= htmlspecialchars($error); ?>
                                </li>

                            <?php endforeach; ?>

                        </ul>

                    </div>

                <?php endif; ?>


                <div class="card border-0 shadow-sm rounded-3 p-4 p-md-5">

                    <form action="" method="POST">

                        <h5 class="fw-bold text-primary mb-3">
                            User Information
                        </h5>


                        <div class="mb-3">

                            <label for="name"
                                   class="form-label fw-semibold">

                                Full Name

                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="name"
                                name="name"
                                value="<?= htmlspecialchars($user->name); ?>"
                                required
                            >

                        </div>


                        <div class="row g-3 mb-4">

                            <div class="col-md-6">

                                <label for="email"
                                       class="form-label fw-semibold">

                                    Email Address

                                </label>

                                <input
                                    type="email"
                                    class="form-control"
                                    id="email"
                                    name="email"
                                    value="<?= htmlspecialchars($user->email); ?>"
                                    required
                                >

                            </div>


                            <div class="col-md-6">

                                <label for="phone"
                                       class="form-label fw-semibold">

                                    Phone Number

                                </label>

                                <input
                                    type="tel"
                                    class="form-control"
                                    id="phone"
                                    name="phone"
                                    value="<?= htmlspecialchars($user->phone ?? ''); ?>"
                                >

                            </div>

                        </div>


                        <hr class="my-4">


                        <h5 class="fw-bold text-primary mb-3">
                            Change Password
                        </h5>


                        <div class="row g-3 mb-4">

                            <div class="col-md-6">

                                <label for="current_password"
                                       class="form-label fw-semibold">

                                    Current Password

                                </label>

                                <input
                                    type="password"
                                    class="form-control"
                                    id="current_password"
                                    name="current_password"
                                >

                            </div>


                            <div class="col-md-6">

                                <label for="new_password"
                                       class="form-label fw-semibold">

                                    New Password

                                </label>

                                <input
                                    type="password"
                                    class="form-control"
                                    id="new_password"
                                    name="new_password"
                                >

                            </div>

                        </div>


                        <div class="d-flex justify-content-end gap-2 pt-2">

                            <a href="users-table.php"
                               class="btn btn-light rounded-pill px-4">

                                Cancel

                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary rounded-pill px-4 fw-semibold">

                                Update User

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>


    <script src="../../css/bootstrap.min.js"></script>

</body>

</html>