<?php
include "config.php";
$user_id =  $_SESSION['sess_user_id'];
$ress = fetch_user($conn, 'user',0, 0,  0, $user_id);
?>
<div class="header">
    <div class="header-content clearfix">
        <div class="nav-control">
            <div class="hamburger">
                <span class="toggle-icon"><i class="icon-menu"></i></span>
            </div>
            <?php if($ress['role_id'] == 1){ ?>
            <a href="requested.php" type="button" class="btn mb-1 btn-rounded btn-success text-white">Requested User</a>
        <?php } ?>
        </div>
        <div class="header-right">
            <ul class="clearfix">
                <li class="icons dropdown">
                    <?php echo '<h5>Welcome '.$_SESSION['sess_name'].'</h5>'; ?>
                    <div class="user-img c-pointer position-relative"   data-toggle="dropdown">
                        <span class="activity active"></span>
                        <img src="images/user/1.png" height="40" width="40" alt="">
                    </div>
                    <div class="drop-down dropdown-profile animated fadeIn dropdown-menu">
                        <div class="dropdown-content-body">
                            <ul>
                                <li>
                                    <a href="app-profile.php"><i class="icon-user"></i> <span>Profile</span></a>
                                </li>
                                <hr class="my-2">
                                <li><a href="logout.php"><i class="icon-key"></i> <span>Logout</span></a></li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
