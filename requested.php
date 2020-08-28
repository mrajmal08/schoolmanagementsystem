<?php
session_start();
include "includes/config.php";
include "functions.php";

if(isset($_GET['type']) && $_GET['type'] == 'approve'){
    $user_id = $_GET['id'];
    approve_req($conn, $user_id);
    header('location: requested.php');
    exit;
}elseif(isset($_GET['id'])){
    delete_user($conn, 'user');
    header('location: requested.php');
    exit;
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

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4 text-left mt-2">
                                    <span class="card-title text-black font-weight-semi-bold ">Requested Users</span>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered zero-configuration">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Address</th>
                                                <th>Contact</th>
                                                <th>Role</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                            <tbody>
                                            <?php
                                            $result = fetch_requested_data($conn);
                                            foreach ($result as $row){
                                                ?>
                                        <tr>
                                            <td><?= $row['username'] ?></td>
                                            <td><?= $row['email'] ?></td>
                                            <td><?= $row['address'] ?></td>
                                            <td><?= $row['contact'] ?></td>
                                            <td><?= $row['rolename'] ?></td>
                                            <td>
                                                <a href="requested.php?id=<?php echo $row["id"]; ?>" type="button" class="btn btn-danger mb-1 btn-rounded btn-info btn-sm">delete</a>
                                                <a href="requested.php?&type=approve&id=<?php echo $row["id"]; ?>" type="button" class="btn btn-warning mb-1 btn-rounded btn-info btn-sm">Approve</a>

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
            <div class="modal fade" id="basicModal" style="display: none;" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add New Principal</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>×</span>
                            </button>
                        </div>
                        <form method="post" action="insertP.php" >
                            <div class="ml-2 mr-2 mt-2">
                                <div class="form-group">
                                    <input type="text" name="name" class="form-control" placeholder="enter name" required>
                                </div>
                            </div>
                            <div class="ml-2 mr-2">
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control" placeholder="enter email" required>
                                </div>
                            </div>
                            <div class="ml-2 mr-2">
                                <div class="form-group">
                                    <input type="password" name="password" class="form-control" placeholder="enter password" required>
                                </div>
                            </div>
                            <div class="ml-2 mr-2">
                                <div class="form-group">
                                    <input type="text" name="address" class="form-control" placeholder="enter address">
                                </div>
                            </div>
                            <div class="ml-2 mr-2">
                                <div class="form-group ">
                                    <input type="text" name="contact" class="form-control" placeholder="enter contact">
                                </div>
                            </div>
                            <div class="form-group ml-2 mr-2">
                                <label class="radio-inline mr-3" data-children-count="1">
                                    <input type="radio" class="" value="1" name="gender"> Male</label>
                                <label class="radio-inline mr-3" data-children-count="1">
                                    <input type="radio" value="2" name="gender"> Female</label>
                            </div>
                            <div class="ml-2 mr-2 text-right mb-3">
                                <button type="submit" class="btn btn-primary">Submit </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- #/ container -->
        </div>
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