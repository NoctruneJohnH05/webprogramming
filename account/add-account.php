<?php

require_once('../tools/functions.php');
require_once('../classes/account.class.php');

$first_name = $last_name = $username = $password = $role = '';
$first_nameErr = $last_nameErr = $usernameErr = $passwordErr = $roleErr = '';

$accountObj = new Account();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = clean_input($_POST['first_name']);
    $last_name = clean_input($_POST['last_name']);
    $username = clean_input($_POST['username']);
    $password = clean_input($_POST['password']);
    $role = clean_input($_POST['role']);

    if (empty($first_name)) {
        $first_nameErr = 'First name is required.';
    }

    if (empty($last_name)) {
        $last_nameErr = 'Last name is required.';
    }

    if (empty($username)) {
        $usernameErr = 'Username is required.';
    } elseif ($accountObj->usernameExists($username)) {
        $usernameErr = 'Username already exists.';
    }

    if (empty($password)) {
        $passwordErr = 'Password is required.';
    } elseif (strlen($password) < 8) {
        $passwordErr = 'Password must be at least 8 characters long.';
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $passwordErr = 'Password must contain at least one uppercase letter.';
    } elseif (!preg_match('/[a-z]/', $password)) {
        $passwordErr = 'Password must contain at least one lowercase letter.';
    } elseif (stripos($password, $first_name) !== false || 
              stripos($password, $last_name) !== false || 
              stripos($password, $username) !== false) {
        $passwordErr = 'Password must not be similar to the registration info.';
    }

    if (empty($role)) {
        $roleErr = 'Role is required.';
    }

    // If there are validation errors, return them as JSON
    if (!empty($first_nameErr) || !empty($last_nameErr) || !empty($usernameErr) || !empty($passwordErr) || !empty($roleErr)) {
        echo json_encode([
            'status' => 'error',
            'first_nameErr' => $first_nameErr,
            'last_nameErr' => $last_nameErr,
            'usernameErr' => $usernameErr,
            'passwordErr' => $passwordErr,
            'roleErr' => $roleErr,
        ]);
        exit;
    }

    // If there are no errors, proceed to add the account
    $accountObj->first_name = $first_name;
    $accountObj->last_name = $last_name;
    $accountObj->username = $username;
    $accountObj->password = password_hash($password, PASSWORD_BCRYPT); // Hash the password
    $accountObj->role = $role;

    if ($accountObj->add()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Something went wrong when adding the new account.']);
    }
    exit;
}
?>
