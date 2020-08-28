<?php
include "config.php";
$user_id =  $_SESSION['sess_user_id'];
$res = fetch_user($conn, 'user',0, 0,  0, $user_id);
?>
<div class="nk-sidebar">
    <div class="nk-nav-scroll">
        <ul class="metismenu" id="menu">
            <li>
                <a class="has-arrow" href="#" aria-expanded="false">
                    <i class="icon-speedometer menu-icon"></i><span class="nav-text">Dashboard</span>
                </a>
                <ul aria-expanded="false">
                    <?php if($res['role_id'] == 1 || $res['role_id'] == 2){ ?>
                    <li><a href="principal.php">principals</a></li>
                    <li><a href="teacher.php">Teachers</a></li>
                    <li><a href="student.php">Students</a></li>
                    <?php
                       }
                     if($res['role_id'] == 3){ ?>
                        <li><a href="teacher.php">Teachers</a></li>
                        <li><a href="student.php">Students</a></li>
                        <li><a href="class.php">Classes</a></li>
                        <li><a href="subject.php">Subjects</a></li>
                    <?php
                         }
                    if($res['role_id'] == 4){ ?>
                        <li><a href="my_class.php">My Classess</a></li>
                        <li><a href="my_subject.php">My Subjects</a></li>
                   <?php
                    }
                    ?>

                </ul>
            </li>
        </ul>
    </div>
</div>