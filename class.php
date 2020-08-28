<?php
session_start();
include "includes/config.php";
include "functions.php";

if(isset($_GET['type']) && $_GET['type'] == 'edit'){
if(isset($_GET['id'])) {
    $class_id = $_GET['id'];
    $result3 = fetch_user($conn, 'class', 0, $class_id);
    }
}
    if(isset($_POST['edit'])){
        unset($_POST['edit']);
        unset($_POST['submitClass']);
        $data['data'] = $_POST;
        $data['where'] = [
            'id' => $_POST['class_id'],
//            'email' => $_POST['email']
        ];
        unset($data['data']['class_id']);
        $final = update($conn, 'class', $data);
        if($final){
            header('location: class.php');
            exit;
        }
    }elseif(isset($_POST['submitClass'])){
        $regex_name = "/^[a-zA-Z\s\d]+$/";
        $name = $_POST['name'];
        if(preg_match($regex_name, $name)) {
        $number = $_POST['number'];
        $data = [
            'name'=> $name,
            'number'=> $number,
        ];
        $columns = ['name','number'];
        $values = [':name', ':number'];
        $result = insert_user($conn, 'class', $columns, $values, $data);
        if($result){
            header('location: class.php');
            exit;
    }
        }else{
            $output_name = "<span style='color: red'>Enter a valid Name</span>";
        }
}
if(isset($_GET['type']) && $_GET['type'] == 'delete') {
    if (isset($_GET['id'])) {
        delete_user($conn, 'class');
        header('location: class.php');
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

<!--*******************
    Preloader start
********************-->
<div id="preloader">
    <div class="loader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10" />
        </svg>
    </div>
</div>
<!--*******************
    Preloader end
********************-->


<!--**********************************
    Main wrapper start
***********************************-->
<div id="main-wrapper">

    <!--**********************************
        Nav header start
    ***********************************-->
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
                            <a class="text-center" href="home.php"> <h4>Fill the <?php echo isset($result3['name'])? $result3['name'] : ""; ?> Detail</h4></a>
                            <form method="post" action="class.php" class="mt-5 mb-5 login-input">
                                <div class="row">
                                    <div class="col-6">
                                        <input type="hidden" name="class_id" value="<?php echo isset($result3['id'])? $result3['id'] : ""; ?>" />
                                        <div class="form-group">
                                            <label class="card-title">Class Name</label>
                                            <input type="text" class="form-control" name="name" placeholder="Enter Name" value="<?php echo isset($result3['name'])? $result3['name'] : ""; ?>" required>
                                            <?php if(isset($output_name)) echo $output_name; ?>
                                        </div>
                                        <div class="form-group">
                                            <label class="card-title">Class No</label>
                                            <input type="text" class="form-control" name="number" placeholder="123..." value="<?php echo isset($result3['number'])? $result3['number'] : ""; ?>" required>
                                        </div>
                                        <?php
                                        if(isset($_GET['id']) ) {
                                            ?>
                                            <input type="hidden" value="true" name="edit">
                                            <?php
                                        }
                                        ?>
                                        <div class="mt-4">
                                            <button type="submit" name="submitClass" class="btn login-form__btn submit w-100">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Table start-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4 text-left mt-2">
                                    <span class="card-title text-black font-weight-semi-bold ">Classes Detail</span>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered zero-configuration">
                                        <thead>
                                        <tr>
                                            <th>Class Name</th>
                                            <th>Classs Number</th>
                                            <th>Action</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $result = fetch_user($conn, 'class');
                                        foreach ($result as $row){
                                            ?>
                                            <tr>
                                                <td><?= $row['name'] ?></td>
                                                <td><?= $row['number'] ?></td>

                                                <td>
                                                    <a  href="class.php?type=delete&id=<?php echo $row["id"]; ?>" type="button" class="btn btn-danger mb-1 btn-rounded btn-info btn-sm">delete</a>
                                                    <a href="class.php?type=edit&id=<?php echo $row["id"] ?>" type="button" class="btn btn-warning mb-1 btn-rounded btn-info btn-sm">edit</a>

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