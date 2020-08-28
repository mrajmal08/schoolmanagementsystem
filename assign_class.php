<?php
session_start();
include "includes/config.php";
include "functions.php";

if(isset($_GET['type']) && $_GET['type']  == 'class') {
    if (isset($_GET['class_id'])) {
        $us_id = $_GET['user_id'];
        $class_id = $_GET['class_id'];
        unAssign_class_suject($conn, 'user_has_class', $us_id, $class_id);

        $user_id = $_GET['user_id'];
        $row1 = fetch_user($conn, 'user',0, 0,  0, $user_id);

    }
}
else{
    $user_id = $_GET['id'];
    $row1 = fetch_user($conn, 'user',0, 0,  0, $user_id);
    }

// Assign Class to user code:
if (isset($_POST['submit'])) {
    if (isset($_POST['class'])) {
        $u_id = $_POST['user_id'];
        $c_id = $_POST['class'];
        assign_class_subject($conn, $u_id, $c_id);
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
                            <a href="student.php" class="btn btn-success float-left text-white"><span class="fa fa-backward "> All Students</span> </a>
                            <a class="text-center" href="home.php"> <h4>Assign Class to <?= $row1['name']; ?></h4></a>
                            <form method="post" action="assign_class.php?id=<?= $row1['id'] ?>" class="mt-5 mb-5 login-input">
                                <div class="row">
                                    <div class="col-6">
                                        <input type="hidden" name="user_id" value="<?php echo $row1['id']; ?>" />
                                        <div class="mb-2 form-group">
                                            <select class="form-control form-control-lg" name="class" required>
                                                <option disabled selected>--Select class--</option>
                                                <?php
                                                $result = fetch_user($conn, 'class');
                                                foreach ($result as $row){
                                                    ?>
                                                    <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>

                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="mt-4">
                                            <button type="submit" name="submit" class="btn login-form__btn submit w-100">Assign</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--**********************************
            Content body end
        ***********************************-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4 text-left mt-2">
                                    <span class="card-title text-black font-weight-semi-bold ">Assigned Class of <?= $row1['name']; ?> </span>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered zero-configuration">
                                        <thead>
                                        <tr>
                                            <th>Class Name</th>
                                            <th>Class Number</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $user_id = $row1['id'];
                                        $result3 = user_class_subject($conn, $user_id, 'class');
                                        foreach ($result3 as $row3){
                                            ?>
                                            <tr>
                                                <td><?= $row3['name'] ?></td>
                                                <td><?= $row3['number'] ?></td>
                                                <td>
                                                    <a href="assign_class.php?type=class&class_id=<?php echo $row3['id'] ?>&user_id=<?php echo $row1['id']?>" type="button" class="btn btn-danger mb-1 btn-rounded btn-info btn-sm">Un Assign</a>
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


    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->
    <?php include 'includes/footer.php'; ?>
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