<?php
include "includes/config.php";
include "function/functions.php";
include 'validation/validation.php';

$output_name = '';
$output_email = '';
$output_password = '';
$output_contact = '';
$check_validation = 1;

if (isset($_POST['submitForm'])) {
    $name = $_POST['name'];
    if (!name_validation($name)) {
        $output_name = "<span style='color: red'>Enter a valid Name</span>";
        $check_validation = 0;
    }
    $email = $_POST['email'];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $output_email = "<span style='color: red'>Enter a valid email address</span>";
        $check_validation = 0;
    }
    $password = $_POST['password'];
    if (!password_validation($password)) {
        $output_password = "<span style='color: red'>Atleast 8 ch</span>";
        $check_validation = 0;
    }
    $address = $_POST['address'];
    $contact = $_POST['contact'];
    if (!contact_validation($contact)) {
        $output_contact = "<span style='color: red'>Enter a valid contact 000-0000-0000</span>";
        $check_validation = 0;
    }
    $gender = $_POST['gender'];
    $role = $_POST['role'];
    $data = [
        'name' => $name,
        'email' => $email,
        'password' => $password,
        'address' => $address,
        'contact' => $contact,
        'gender' => $gender,
        'role' => $role,

    ];
    $columns = ['name', 'email', 'password', 'address', 'contact', 'gender', 'role_id'];
    $values = [':name', ':email', ':password', ':address', ':contact', ':gender', ':role'];
    $final = insert($conn, 'user', $columns, $values, $data);
    if ($final) {
        header('location: page-login');
        exit;
    }

}


?>
<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <?php
    include "includes/head.php";
    ?>
</head>
<body class="h-100">
<div id="preloader">
    <div class="loader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10"/>
        </svg>
    </div>
</div>
<div class="login-form-bg h-100">
    <div class="container h-100">
        <div class="row justify-content-center h-100">
            <div class="col-xl-6">
                <div class="form-input-content">
                    <div class="card login-form mb-0">
                        <div class="card-body pt-5">
                            <a class="text-center" href="home.php"><h4>School Management System</h4></a>
                            <form method="post" action="" class="mt-5 mb-5 login-input">
                                <div class="form-group">
                                    <label class="card-title">Name</label>
                                    <input type="text" class="form-control" name="name"
                                           placeholder="Enter Name" required>
                                    <?php if (isset($output_name)) echo $output_name; ?>
                                </div>
                                <div class="form-group">
                                    <label class="card-title">Email</label>
                                    <input type="text" class="form-control" name="email"
                                           placeholder="test@test.com" required>
                                    <?php if (isset($output_email)) echo $output_email; ?>
                                </div>
                                <div class="form-group">
                                    <label class="card-title">Password</label>
                                    <input type="password" class="form-control" name="password"
                                           placeholder="******" required>
                                    <?php if (isset($output_password)) echo $output_password; ?>
                                </div>
                                <div class="form-group">
                                    <label class="card-title">Address</label>
                                    <input type="text" class="form-control" name="address"
                                           placeholder="Enter Address">
                                </div>
                                <div class="form-group">
                                    <label class="card-title">Contact</label>
                                    <input type="number" class="form-control" name="contact"
                                           placeholder="123456789">
                                    <?php if (isset($output_contact)) echo $output_contact; ?>
                                </div>
                                <label class="card-title">Gender</label>
                                <div class="form-group">
                                    <label class="radio-inline mr-3" data-children-count="1">
                                        <input type="radio" class="" value="male" name="gender"> Male</label>
                                    <label class="radio-inline mr-3" data-children-count="1">
                                        <input type="radio" value="female" name="gender"> Female</label>
                                </div>
                                <div class="mb-2">
                                    <select class="form-control form-control-lg" name="role" required>
                                        <option disabled selected>--Select Role--</option>
                                        <?php
                                        $result = show($conn, 'role', '');
                                        foreach ($result as $row) {
                                            ?>
                                            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>

                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <button type="submit" name="submitForm" class="btn login-form__btn
                                    submit w-100">Sign in
                                </button>
                            </form>
                            <p class="mt-5 login-form__footer">Have account <a href="login.php"
                                  class="text-primary">Sign In </a> now</p>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!--**********************************
    Scripts
***********************************-->

<script src="js/custom.min.js"></script>
<script src="js/settings.js"></script>
<script src="js/gleek.js"></script>
<script src="js/styleSwitcher.js"></script>
</body>
</html>
