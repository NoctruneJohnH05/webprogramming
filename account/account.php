<?php
session_start();

if (isset($_SESSION['account'])) {
    if (!$_SESSION['account']['is_staff']) {
        header('location: login.php');
        exit;
    }
} else {
    header('location: login.php');
    exit;
}

require_once '../classes/account.class.php';
$accountObj = new Account();

// Fetch user data
$accounts = $accountObj->fetchAccounts();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accounts</title>
    <link rel="stylesheet" href="path/to/your/styles.css"> <!-- Link to your CSS -->
    <style>
        /* Additional styling if needed */
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Accounts</h4>
                </div>
            </div>
        </div>

        <div class="modal-container"></div>
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table-accounts" class="table table-centered table-nowrap mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Username</th>
                                        <th>Role</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($accounts)): ?>
                                        <tr>
                                            <td colspan="5" class="text-center">No accounts found.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($accounts as $account): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($account['first_name']) ?></td>
                                                <td><?= htmlspecialchars($account['last_name']) ?></td>
                                                <td><?= htmlspecialchars($account['username']) ?></td>
                                                <td><?= htmlspecialchars($account['role']) ?></td>
                                                <td>
                                                    <a href="editaccount.php?id=<?= $account['id'] ?>" class="btn btn-warning">Edit</a>
                                                    <?php if ($_SESSION['account']['is_admin']): ?>
                                                        <a href="#" class="deleteBtn btn btn-danger" data-id="<?= $account['id'] ?>" data-name="<?= $account['username'] ?>">Delete</a>
                                                    <?php endif; ?>
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
        </div>
    </div>

    <script src="./account.js"></script>
</body>
</html>
