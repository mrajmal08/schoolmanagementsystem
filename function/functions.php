<?php
include "includes/config.php";

/**
 *
 * @param $conn
 * @param $table
 * @param bool $where
 * @param null $query
 * @return mixed
 */
function show($conn, $table, $where = false )
{
        //query string
        $query = "SELECT * FROM {$table} ";
        if (!empty($where)) {
            $query .= "WHERE ";
            $i = 0;
            foreach ($where as $key => $value) {
                if ($i == count($where) - 1) {
                    $query .= "{$key} = {$value}";
                } else {
                    $query .= "{$key} = {$value} AND ";
                }
                $i++;
            }
        }
    $data = $conn->query($query);
    return $data->fetchAll(PDO::FETCH_ASSOC);
}

// insert function of all the tables
function insert($conn, $table, $columns, $values, $data)
{
    try {
        $query = "INSERT INTO {$table} (" . implode(',', $columns) . ") VALUES
         (" . implode(',', $values) . ")";
        $exe = $conn->prepare($query);
        return $exe->execute($data);
        //exception
    } catch (PDOException $e) {
        return "Error : " . $e->getMessage();
    }
}

//delete function of all the tables
function delete($conn, $table, $values)
{
    $query = "DELETE FROM {$table} WHERE ";
    $count = 0;
    foreach ($values as $key => $value) {
        if ($count == 0) {
            $query .= "{$key} = '{$value}' ";
        } else {
            $query .= "AND {$key} = '{$value}' ";
        }
        $count++;
    }
    $exe = $conn->prepare($query);
    return $exe->execute();
}

//update function of all the tables
function update($conn, $table, $data)
{
    $where = $data['where'];
    $original = $data['data'];
    $query = "UPDATE {$table} set ";
    $count = 0;
    foreach ($original as $key => $value) {
        if ($count == count($original) - 1) {
            $query .= $key . " = '{$value}'";
        } else {
            $query .= $key . " = '{$value}', ";
        }
        $count++;
    }
    //if query goes with AND after where
    $count = 0;
    $query .= " WHERE ";
    foreach ($where as $key => $value) {
        if ($count == count($where) - 1) {
            $query .= $key . " = '{$value}' ";
        } else {
            $query .= $key . " = '{$value}' AND ";
        }
        $count++;
    }
    $exe = $conn->prepare($query);
    return $exe->execute();
}

//this function is for approval new users request
function approve_req($conn, $user_id)
{
    if (!empty($user_id)) {
        $data['data'] = ['status' => 1];
        $data['where'] = [
            'id' => $user_id,
        ];
        return update($conn, 'user', $data);
    }
}

function get_data_for_query($conn, $query){
    if(!empty($query)){}
    $data = $conn->query($query);
    return $data->fetchAll(PDO::FETCH_ASSOC);
}

//this function display the class and subject by comparing with user
function user_class_subject($conn, $user_id, $type = null)
{
    switch ($type){
        case 'class':
            $query = "SELECT class.id as id, class.name as classname, class.number as classnumber
                  FROM `user_has_class` 
                  INNER JOIN user ON user_has_class.user_id = user.id 
                  INNER JOIN class ON user_has_class.class_id = class.id WHERE user_id = $user_id";
            return get_data_for_query($conn, $query);
            break;
        case 'subject':
            $query = "SELECT class.id as id, class.name as classname, class.number as classnumber
                  FROM `user_has_class` 
                  INNER JOIN user ON user_has_class.user_id = user.id 
                  INNER JOIN class ON user_has_class.class_id = class.id WHERE user_id = $user_id";
            return get_data_for_query($conn, $query);
            break;
        default:
    }
}

// this function shows the specific data of user by comparing user id with role table
function fetch_requested_data($conn)
{
    $query = "SELECT user.id, user.name as username, user.email, user.address, user.contact, user.status,
              role.name as rolename FROM user INNER JOIN role ON user.role_id = role.id WHERE
              user.status = 0";
    return get_data_for_query($conn, $query);
}

//assign the class and subject to the user
function assign_class_subject($conn, $user_id, $class_id = null, $subject_id = null)
{
    if (!empty($class_id)) {
        try {
            //ids for show data
            $data = [
                'user_id' => $user_id,
                'class_id' => $class_id,
            ];
            //getting columns and values for insert query
            $where = [
                'user_id' => $user_id,
                'class_id' => $class_id
            ];
            $result = show($conn, 'user_has_class', $where);
            if (isset($result[0]['user_id']) && isset($result[0]['class_id'])) {
                if ($result[0]['user_id'] > 1 && $result[0]['class_id'] > 1) {
                    return "Error : ";
                }
            } else {
                //getting columns and values for insert query
                $columns = ['user_id', 'class_id'];
                $values = [':user_id', ':class_id'];
                insert($conn, 'user_has_class', $columns, $values, $data);
            }
        } catch (PDOException $e) {
            return "Error : " . $e->getMessage();
        }
    } elseif (!empty($subject_id)) {
        try {
            //ids for show data
            $data = [
                'user_id' => $user_id,
                'subject_id' => $subject_id,
            ];
            //getting columns and values for insert query
            $where = [
                'user_id' => $user_id,
                'subject_id' => $subject_id
            ];
            $result = show($conn, 'user_has_subject', $where);
            if (isset($result[0]['user_id']) && isset($result[0]['subject_id'])) {
                if ($result[0]['user_id'] > 1 && $result[0]['subject_id'] > 1) {
                    return "<span style='color: red'> invalid email and password </span>";
                }
            } else {
                //getting columns and values for insert query
                $columns = ['user_id', 'subject_id'];
                $values = [':user_id', ':subject_id'];
                insert($conn, 'user_has_subject', $columns, $values, $data);
            }
        } catch (PDOException $e) {
            return "Error : " . $e->getMessage();
        }
    }
}

//login user function
function login_user($conn, $email, $password)
{
    if ($email != "" && $password != "") {
        try {
            //check user for verification
            $where = [
                'email' => "'$email'",
                'password' => "'$password'",
                'status' => 1
            ];
            $row = show($conn, 'user', $where);
            $count = count($row);
            if ($count == 1 && !empty($row)) {
                $_SESSION['sess_user_id'] = $row[0]['id'];
                $_SESSION['sess_name'] = $row[0]['name'];
                $_SESSION['role'] = $row[0]['role_id'];
                header("location: home");
                exit;
            } else {
                return "<span style='color: red'>Invalid email and password!</span>";
            }
        } catch (PDOException $e) {
            return "Error : " . $e->getMessage();
        }
    } else {
        return "<span style='color: red'>Both fields are required!</span>";
    }
}

//dynamic function for datatable
function datatable($conn, $thead, $tbody, $action)
{
    ?>
    <div class='table-responsive'>
        <table class='table table-striped table-bordered zero-configuration'>
            <thead>
            <tr>
                <?php
                for ($i = 0; $i < count($thead); $i++) {
                    ?>
                    <th><?= $thead[$i] ?></th>
                    <?php
                }
                ?>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($tbody as $row) {
                ?>
                <tr>
                    <?php
                    foreach ($row as $key => $body) {
                        if ($key != 'id') {
                            if ($key != 'status') {
                                if ($key != 'role_id') {
                                    ?>
                                    <td><?= $body ?></td>
                                <?php }
                            }
                        }
                    }
                    ?>
                    <td>
                        <?php buttons($action, $row); ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <?php
}

function buttons($action, $row)
{
    foreach ($action as $key => $button) {
        printButton($button, $row);
    }
}

//print button function
function printButton($button, $row)
{
    $url = $button['url'] . '?type=' . $button['value'];
    foreach ($button['require'] as $key => $value) {
        $url .= "&{$value}=" . $row[$value];
    }
    if (!empty($button['default'])) {
        foreach ($button['default'] as $key => $value) {
            $url .= "&{$key}=" . $value;
        }
    }
    ?>
    <a class="<?= $button['class'] ?>" href="<?= $url; ?>"><?= $button['value'] ?></a>
    <?php
}
