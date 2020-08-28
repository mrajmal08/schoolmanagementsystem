<?php
session_start();
include "includes/config.php";
include "functions.php";

$admin_id = $_SESSION['sess_user_id'];
$session_role = $_SESSION['role'];
if($session_role == 1) {
    $status = 1;
}
else{
    $status = 0;
}

if(isset($_GET['type']) && $_GET['type'] == 'edit'){
    if(isset($_GET['id'])) {
        $user_id = $_GET['id'];
        $user = fetch_user($conn, 'user',0, 0,  0, $user_id);
    }
}
if(isset($_POST['edit'])){
    unset($_POST['edit']);
    unset($_POST['submitStudent']);
    $data['data'] = $_POST;
    $data['where'] = [
        'id' => $_POST['id'],
//            'email' => $_POST['email']
    ];
    unset($data['data']['id']);

    $datap =  update($conn, 'user', $data);
    if($datap){
        header('location: student.php');
        exit;
    }
}
elseif(isset($_POST['submitStudent'])) {
        $name = $_POST['name'];
        if(validation($name)) {
        $email = $_POST['email'];
        if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $password = $_POST['password'];
        if(validation('', $password)){
        $address = $_POST['address'];
        $contact = $_POST['contact'];
        if(filter_var($contact, FILTER_SANITIZE_NUMBER_INT)) {
        $gender = $_POST['gender'];
        $role = 4;
        $data = [
            'name'=> $name,
            'email'=> $email,
            'password'=>$password,
            'address'=> $address,
            'contact'=> $contact,
            'gender'=>$gender,
            'role' => $role,
            'status' => $status
        ];
        $columns = ['name', 'email', 'password', 'address', 'contact', 'gender', 'role_id', 'status'];
        $values = [':name', ':email', ':password', ':address',':contact', ':gender', ':role', ':status'];
        $final = insert_user($conn,'user', $columns, $values, $data);
    if($final){
        echo 'hello';
        header('location: student.php');
        exit;
    }
        }else{
            $output_contact = "<span style='color: red'>Enter a valid contact 000-0000-0000</span>";
        }
        }else{
            $output_password = "<span style='color: red'>Atleast 8 ch</span>";
        }
        }else{
            $output_email = "<span style='color: red'>Enter a valid email address</span>";
        }
        }else{
            $output_name = "<span style='color: red'>Enter a valid Name</span>";
        }
}
if(isset($_GET['type']) && $_GET['type'] == 'delete') {
    if (isset($_GET['id'])) {
        delete_user($conn, 'user');
        header('location: student.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include "includes/head.php";
    ?>

</head>
<body>
<div id="preloader">
    <div class="loader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10" />
        </svg>
    </div>
</div>
<div id="main-wrapper">
    <div class="nav-header">
        <div class="brand-logo">
            <a href="home.php">
                <b class="logo-abbr"><img src="images/logo.png" alt=""> </b>
                <span class="logo-compact"><img src="./images/logo-compact.png" alt=""></span>
                <span class="brand-title text-white">
                       School Management System
                </span>
            </a>
        </div>
    </div>
    <?php include 'includes/header.php' ?>
    <?php  include "includes/sidebar.php"; ?>
    <div class="content-body">

        <div class="login-form-bg mt-3 mb-3 ">
            <div class="row ml-3 mr-3">
                <div class="form-input-content col-12">
                    <div class="card login-form mb-0">
                        <div class="card-body pt-5">
                            <h4>Fill Up The <?php echo isset($user['name'])? $user['name'] : ""; ?> Details</h4>
                            <form method="post" action="" class="mt-5 mb-5 login-input">
                                <div class="row">
                                    <div class="col-6">
                                        <input type="hidden" name="id" value="<?php echo isset($user['id'])? $user['id'] : ""; ?>" />
                                        <div class="form-group">
                                            <label class="card-title">Name</label>
                                            <input type="text" class="form-control" name="name" placeholder="Enter Name" value="<?php echo isset($user['name'])? $user['name'] : ""; ?>" required>
                                            <?php if(isset($output_name)) echo $output_name;?>
                                        </div>
                                        <div class="form-group">
                                            <label class="card-title">Email</label>
                                            <input type="text" class="form-control" name="email" value="<?php echo isset($user['email'])? $user['email'] : ""; ?>"  placeholder="test@test.com" required>
                                            <?php if(isset($output_email)) echo $output_email; ?>
                                        </div>

                                        <div class="form-group">
                                            <label class="card-title">Address</label>
                                            <input type="text" class="form-control" name="address" value="<?php echo isset($user['address'])? $user['address'] : ""; ?>"  placeholder="Enter Address">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="card-title">Contact</label>
                                            <input type="number" class="form-control" name="contact" value="<?php echo isset($user['contact'])? $user['contact'] : ""; ?>"  placeholder="000-0000-0000">
                                            <?php if(isset($output_contact)) echo $output_contact; ?>
                                        </div>
                                        <div class="form-group">
                                            <label class="card-title">Password</label>
                                            <input type="password" class="form-control" name="password" value="<?php echo isset($user['password'])? $user['password'] : ""; ?>"  placeholder="******" required>
                                            <?php if(isset($output_password)) echo $output_password; ?>
                                        </div>
                                        <label class="card-title">Gender</label>
                                        <div class="form-group">
                                            <label class="radio-inline mr-3" data-children-count="1">
                                                <input type="radio" class="" value="male" <?php if ( isset($user['gender']) && $user['gender'] == 'male') echo 'checked="checked"'; ?> required name="gender"> Male</label>
                                            <label class="radio-inline mr-3" data-children-count="1">
                                                <input type="radio" value="female" <?php if ( isset($user['gender']) && $user['gender'] == 'female') echo 'checked="checked"'; ?> required name="gender"> Female</label>
                                        </div>
                                        <?php
                                        if(isset($_GET['id']) ) {
                                            ?>
                                            <input type="hidden" value="true" name="edit">
                                            <?php
                                        }
                                        ?>
                                        <div class="mt-4">
                                            <button type="submit" name="submitStudent" class="btn login-form__btn submit w-100">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--Table Start-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4 text-left mt-2">
                                    <span class="card-title text-black font-weight-semi-bold ">Student Detail</span>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered zero-configuration">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Address</th>
                                            <th>Contact</th>
                                            <th>Gender</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $result = fetch_user($conn, 'user', 4);
                                        foreach ($result as $row){
                                            ?>
                                            <tr>
                                                <td><?= $row['name'] ?></td>
                                                <td><?= $row['email'] ?></td>
                                                <td><?= $row['address'] ?></td>
                                                <td><?= $row['contact'] ?></td>
                                                <td><?= $row['gender'] ?></td>
                                                <td>
                                                    <a  href="student.php?type=delete&id=<?php echo $row["id"]; ?>" type="button" class="btn btn-danger mb-1 btn-rounded btn-info btn-sm">delete</a>
                                                    <a href="student.php?type=edit&id=<?php echo $row["id"] ?>" type="button" class="btn btn-warning mb-1 btn-rounded btn-info btn-sm">edit</a>

                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #/ container -->


        </div>
        <!--**********************************
            Content body end
        ***********************************-->


        <!--**********************************
            Footer start
        ***********************************-->
        <?php include 'includes/footer.php'; ?>
        <!--**********************************
            Footer end
        ***********************************-->
    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <script src="plugins/common/common.min.js"></script>
    <script src="js/custom.min.js"></script>
    <script src="js/settings.js"></script>
    <script src="js/gleek.js"></script>
    <script src="js/styleSwitcher.js"></script>

    <script src="./plugins/tables/js/jquery.dataTables.min.js"></script>
    <script src="./plugins/tables/js/datatable/dataTables.bootstrap4.min.js"></script>
    <script src="./plugins/tables/js/datatable-init/datatable-basic.min.js"></script>

</body>

</html>