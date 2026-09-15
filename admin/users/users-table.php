<?php

require_once "../components/navbar.php";
require_once "../../classes.php";

session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['admin'] == false) {
    header("Location: /store-project/auth/login.php");
    exit();
}

$users = User::getAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../../css/bootstrap.css">

    <title>Online Store - Users Table</title>

</head>

<body class="bg-light">

    <?php render_navbar(['active' => 'users']); ?>


    <div class="container my-5">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <h2 class="fw-bold m-0">
                Users
            </h2>

            <a href="create-user.php"
               class="btn btn-primary rounded-pill px-4">

                + Create User

            </a>

        </div>


        <div class="card border-0 shadow-sm rounded-3">

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="px-4">ID</th>

                                <th>Name</th>

                                <th>Email</th>

                                <th>Phone</th>

                                <th>Admin</th>

                                <th class="text-end px-4">Actions</th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php if (empty($users)): ?>

                                <tr>

                                    <td colspan="6"
                                        class="text-center py-5 text-muted">

                                        No users found.

                                    </td>

                                </tr>

                            <?php else: ?>

                                <?php foreach ($users as $user): ?>

                                    <tr>

                                        <td class="px-4">
                                            <?= (int) $user->id; ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($user->name); ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($user->email); ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($user->phone ?? '-'); ?>
                                        </td>

                                        <td>

                                            <?php if ($user->admin): ?>

                                                <span class="badge bg-success">
                                                    Yes
                                                </span>

                                            <?php else: ?>

                                                <span class="badge bg-secondary">
                                                    No
                                                </span>

                                            <?php endif; ?>

                                        </td>

                                        <td class="text-end px-4">

                                            <a
                                                href="update-user.php?id=<?= (int) $user->id; ?>"
                                                class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                Edit
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


    <script src="../../css/bootstrap.min.js"></script>

</body>

</html>