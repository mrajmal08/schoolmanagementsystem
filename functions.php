<?php
include "includes/config.php";

function fetch_user($conn, $table, $role = 0, $class_id = 0, $subject_id = 0, $user_id = 0){
  $query = '';
    if($class_id != 0){
        $qry = "SELECT * FROM {$table} WHERE id = $class_id";
        $dat = $conn->query($qry);
        $result3 = $dat->fetch(PDO::FETCH_ASSOC);
        return $result3;

    }elseif ($subject_id != 0) {
        $qry = "SELECT * FROM {$table} WHERE id = $subject_id";
        $dat = $conn->query($qry);
        $result3 = $dat->fetch(PDO::FETCH_ASSOC);
        return $result3;

    }elseif ($user_id != 0){
        $qry = "SELECT * FROM {$table} WHERE id = $user_id";
        $dat = $conn->query($qry);
        $user = $dat->fetch(PDO::FETCH_ASSOC);
        return $user;

    }elseif($role == 0){
      $query = "SELECT * FROM {$table}";

    }else {
      $query = "SELECT * FROM {$table} where status = 1 and role_id = {$role}";
  }
    $data = $conn->query($query);
    $result = $data->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}
function insert_user($conn, $table, $columns, $values, $data){
    $query = "INSERT INTO {$table} (". implode(',', $columns). ") VALUES (". implode(',', $values) .")";
    $exe = $conn->prepare($query);
    $exe->execute($data);
    return true;
}
function delete_user($conn , $table){
        $user_id = $_GET['id'];
        $query = "DELETE FROM {$table} where id = $user_id";
        $exe = $conn->prepare($query);
        $exe->execute();
        return true;
}
function update($conn, $table, $data){
    $where = $data['where'];
    $original = $data['data'];
    $query = "UPDATE {$table} set " ;
    $count = 0;
    foreach ($original as $key => $value){
        if($count == count($original) -1){
            $query .= $key. " = '{$value}'";
        }else{
            $query .= $key. " = '{$value}', ";
        }
        $count++;
    }
    $count = 0;
    $query .= " WHERE ";
    foreach($where as $key => $value){
        if($count == count($where) -1){
            $query .= $key . " = '{$value}' ";
        }else{
            $query .= $key . " = '{$value}' AND ";
        }
        $count++;

    }
//    echo $query; die;
    $exe = $conn->prepare($query);
    $exe->execute();
    return true;

}
function approve_req($conn, $user_id){
    $query = "UPDATE user set status = 1 where id = $user_id ";
    $exe = $conn->prepare($query);
    $exe->execute();
    return true;
}
function user_class_subject($conn, $user_id, $type = ''){
    if($type == 'class'){
        $query = "SELECT * FROM `user_has_class` INNER JOIN user ON user_has_class.user_id = user.id INNER JOIN class ON user_has_class.class_id = class.id WHERE user_id = $user_id";
        $data = $conn->query($query);
        $result = $data->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    elseif ($type == 'subject'){
        $query = "SELECT * FROM `user_has_subject` INNER JOIN user ON user_has_subject.user_id = user.id INNER JOIN subject ON user_has_subject.subject_id = subject.id WHERE user_id = $user_id";
        $data = $conn->query($query);
        $result = $data->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


}
function fetch_requested_data($conn){
    $query = "SELECT user.id, user.name as username, user.email, user.address, user.contact, user.status, role.name as rolename FROM user INNER JOIN role ON user.role_id = role.id WHERE user.status = 0";
    $data = $conn->query($query);
    $result = $data->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}
function assign_class_subject($conn, $u_id, $c_id = 0, $s_id= 0){
    if($c_id != 0){
        $data2 = [
            'u_id' => $u_id,
            'c_id' => $c_id,
        ];
        if ($u_id != "" && $c_id != "") {
            $sql = "SELECT * FROM user_has_class WHERE user_id = $u_id AND class_id = $c_id";
            $sqlexe = $conn->query($sql);
            $sqlresult = $sqlexe->fetch(PDO::FETCH_ASSOC);
            if($sqlresult['user_id'] > 1 && $sqlresult['class_id'] > 1){
                echo 'data already exist';
            }else {
                $query2 = "INSERT INTO user_has_class (user_id,class_id) VALUES (:u_id, :c_id)";
                $exe2 = $conn->prepare($query2);
                $exe2->execute($data2);
            }
        }

    }elseif ($s_id != 0){
        $data2 = [
            'u_id' => $u_id,
            's_id' => $s_id,
        ];

        if ($u_id != "" && $s_id != "") {
            $sql = "SELECT * FROM user_has_subject WHERE user_id = $u_id AND subject_id = $s_id";
            $sqlexe = $conn->query($sql);
            $sqlresult = $sqlexe->fetch(PDO::FETCH_ASSOC);
            if($sqlresult['user_id'] > 1 && $sqlresult['subject_id'] > 1) {

            }else {
                $query2 = "INSERT INTO user_has_subject (user_id,subject_id) VALUES (:u_id, :s_id)";
                $exe2 = $conn->prepare($query2);
                $exe2->execute($data2);
            }
        }

    }

}
function unAssign_class_suject($conn, $table, $us_id, $class_id= 0, $subject_id= 0){
    if($class_id!= 0){
        $q = "DELETE FROM {$table} where user_id = $us_id and class_id = $class_id";
        $ex = $conn->prepare($q);
        $ex->execute();
        return true;
    }elseif ($subject_id != 0){
        $q = "DELETE FROM {$table} where user_id = $us_id and subject_id = $subject_id";
        $ex = $conn->prepare($q);
        $ex->execute();
        return true;
    }

}

function loginUser($conn, $email, $password){
    if($email != "" && $password != "") {
        try {
            $query = "select * from user where email=:email and password=:password and status = 1";
            $exe = $conn->prepare($query);
            $exe->bindParam('email', $email, PDO::PARAM_STR);
            $exe->bindValue('password', $password, PDO::PARAM_STR);
            $exe->execute();
            $count = $exe->rowCount();
            $row   = $exe->fetch(PDO::FETCH_ASSOC);

            if($count == 1 && !empty($row)) {
                /******************** Your code ***********************/
                $_SESSION['sess_user_id']   = $row['id'];
                $_SESSION['sess_name'] = $row['name'];
                $_SESSION['role'] = $row['role_id'];
                header("location: home.php");
            } else {
                echo "Invalid email and password!";
            }

        } catch (PDOException $e) {
            echo "Error : ".$e->getMessage();
        }

    } else {
        echo "Both fields are required!";
    }
    return true;
}

function validation($name = '', $password = ''){
if($name != '') {

    return preg_match("/^[a-zA-Z\s]+$/", $name);

}elseif ($password != ''){

    return preg_match("/^.{8,}$/", $password);
}


}