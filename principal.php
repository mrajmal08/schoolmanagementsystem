<?php
session_start();
include "includes/config.php";
include 'function/functions.php';
include 'validation/validation.php';

//global variables for for validation errors
$output_name = '';
$output_email = '';
$output_password = '';
$output_contact = '';
$check_validation = 1;

//geting session id for defining the role for adding user
$admin_id = $_SESSION['sess_user_id'];
$session_role = $_SESSION['role'];
if ($session_role == 1) {
    $status = 1;
} else {
    $status = 0;
}
//Prncipal add code with validation
if (isset($_GET['type']) && $_GET['type'] == 'edit') {
    if (isset($_GET['id'])) {
        $user_id = $_GET['id'];
        $where = [
            'id' => $user_id,
        ];
        $user = show($conn, 'user', $where);
    }
}
if (isset($_POST['edit'])) {
    unset($_POST['edit']);
    unset($_POST['submitPrincipal']);
    $data['data'] = $_POST;
    $data['where'] = [
        'id' => $_POST['id'],
//            'email' => $_POST['email']
    ];
    unset($data['data']['id']);

    $datap = update($conn, 'user', $data);
    if ($datap) {
        header('location: principal.php');
        exit;
    }
} elseif (isset($_POST['submitPrincipal'])) {
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
    $role = 2;
    $data = [
        'name' => $name,
        'email' => $email,
        'password' => $password,
        'address' => $address,
        'contact' => $contact,
        'gender' => $gender,
        'role' => $role,
        'status' => $status
    ];
    $columns = ['name', 'email', 'password', 'address', 'contact', 'gender', 'role_id', 'status'];
    $values = [':name', ':email', ':password', ':address', ':contact', ':gender', ':role', ':status'];
    $final = '';
    if ($check_validation == 1) {
        $final = insert($conn, 'user', $columns, $values, $data);
    }
    if ($final) {
        header('location: teacher.php');
        exit;
    }
}
//Principal delete code
if (isset($_GET['type']) && $_GET['type'] == 'delete') {
    if (isset($_GET['id'])) {
        $user_id = $_GET['id'];
        $values = [
            'id' => $user_id
        ];
        delete($conn, 'user', $values);
        header('location: principal.php');
        exit;
    }
}
?>
<!--Include file contain head, header, and side bar-->
<?php include 'includes/include.php'; ?>

<div class="content-body">
    <div class="login-form-bg mt-3 mb-3 ">
        <div class="row ml-3 mr-3">
            <div class="form-input-content col-12">
                <div class="card login-form mb-0">
                    <div class="card-body pt-5">
                        <h4>Fill Up The <?php echo isset($user[0]['name']) ? $user[0]['name'] : ""; ?>
                            Details</h4>
                        <!--Principal ad and principal edit form -->
                        <form method="post" action="" class="mt-5 mb-5 login-input">
                            <div class="row">
                                <div class="col-6">
                                    <input type="hidden" name="id"
                                           value="<?php echo isset($user[0]['id']) ?
                                               $user[0]['id'] : ""; ?>"/>
                                    <div class="form-group">
                                        <label class="card-title">Name</label>
                                        <input type="text" class="form-control" name="name"
                                               placeholder="Enter Name"
                                               value="<?php echo isset($user[0]['name']) ?
                                                   $user[0]['name'] : ""; ?>"
                                               required>
                                        <?php if (isset($output_name)) echo $output_name; ?>
                                    </div>
                                    <div class="form-group">
                                        <label class="card-title">Email</label>
                                        <input type="text" class="form-control" name="email"
                                               value="<?php echo isset($user[0]['email']) ?
                                                   $user[0]['email'] : ""; ?>"
                                               placeholder="test@test.com" required>
                                        <?php if (isset($output_email)) echo $output_email; ?>
                                    </div>

                                    <div class="form-group">
                                        <label class="card-title">Address</label>
                                        <input type="text" class="form-control" name="address"
                                               value="<?php echo isset($user[0]['address']) ?
                                                   $user[0]['address'] : ""; ?>"
                                               placeholder="Enter Address">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="card-title">Contact</label>
                                        <input type="number" class="form-control" name="contact"
                                               value="<?php echo isset($user[0]['contact']) ?
                                                   $user[0]['contact'] : ""; ?>"
                                               placeholder="000-0000-0000">
                                        <?php if (isset($output_contact))
                                            echo $output_contact; ?>
                                    </div>
                                    <div class="form-group">
                                        <label class="card-title">Password</label>
                                        <input type="password" class="form-control" name="password"
                                               value="<?php echo isset($user[0]['password']) ?
                                                   $user[0]['password'] : ""; ?>"
                                               placeholder="******" required>
                                        <?php if (isset($output_password)) echo $output_password; ?>
                                    </div>
                                    <label class="card-title">Gender</label>
                                    <div class="form-group">
                                        <label class="radio-inline mr-3" data-children-count="1">
                                            <input type="radio" class="" value="male"
                                                <?php if (isset($user[0]['gender']) &&
                                                    $user[0]['gender'] == 'male')
                                                    echo 'checked="checked"'; ?>
                                                   required name="gender">
                                            Male</label>
                                        <label class="radio-inline mr-3" data-children-count="1">
                                            <input type="radio" value="female"
                                                <?php if (isset($user[0]['gender'])
                                                    && $user[0]['gender'] == 'female')
                                                    echo 'checked="checked"'; ?>
                                                   required name="gender"> Female</label>
                                    </div>
                                    <?php
                                    if (isset($_GET['id'])) {
                                        ?>
                                        <input type="hidden" value="true" name="edit">
                                        <?php
                                    }
                                    ?>
                                    <div class="mt-4">
                                        <button type="submit" name="submitPrincipal"
                                                class="btn login-form__btn submit w-100">Submit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4 text-left mt-2">
                                <span class="card-title text-black font-weight-semi-bold ">
                                    Principals Detail</span>
                            </div>
                            <!-- Principal table code-->
                            <?php
                            $thead = ['Name', 'Email', 'Address', 'Contact', 'Gender', 'Action'];
                            $where = [
                                'status' => 1,
                                'role_id' => 2
                            ];
                            $tbody = show($conn, 'user', $where);

                            $action = [
                                'button1' => [
                                    'value' => 'delete',
                                    'url' => 'principal',
                                    'require' => ['id'],
                                    'class' => 'btn btn-danger btn-sm'
                                ],
                                'button2' => [
                                    'value' => 'edit',
                                    'url' => 'principal',
                                    'require' => ['id'],
                                    'class' => 'btn btn-warning btn-sm'
                                ],
                            ];
                            datatable($conn, $thead, $tbody, $action);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- #/ container -->
    </div>
    <?php include 'includes/footer.php'; ?>
</div>
<!--**********************************
    Main wrapper end
***********************************-->

<!--**********************************
    Scripts
***********************************-->
<script src="js/custom.min.js"></script>
<script src="js/settings.js"></script>
<script src="js/gleek.js"></script>
<script src="js/styleSwitcher.js"></script>

<script src="plugins/tables/js/jquery.dataTables.min.js"></script>
<script src="plugins/tables/js/datatable/dataTables.bootstrap4.min.js"></script>
<script src="plugins/tables/js/datatable-init/datatable-basic.min.js"></script>

</body>

</html>