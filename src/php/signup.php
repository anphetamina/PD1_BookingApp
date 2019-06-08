<?php
include 'db.php';

session_start();

if (!empty($_POST)) {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action == 'registration') {
            if (isset($_POST['user']) && isset($_POST['password'])) {
                $username = $_POST['user'];
                $password = $_POST['password'];

                $response = register($username, $password);

                switch ($response) {
                    case REGISTRATION_SUCCESS:
                        echo 'Registrazione avvenuta con successo';
                        break;

                    case USER_ALREADY_EXISTS:
                        echo 'Username già esistente';
                        break;

                    case USER_NOT_VALID:
                        echo 'Username non valido';
                        break;

                    case PASSWORD_NULL:
                        echo 'Password non valida';
                        break;

                    default:
                        break;
                }
            }
        }
    }
}


function checkUser($username) {
    $connection = db_get_connection();
    $result = false;
    $count = 0;
    $query = "select count(*) from user where user=?";
    if($stmt = $connection->prepare($query)) {
        $stmt->bind_param('s', $username);
        try {
            if(!$stmt->execute())
                throw new Exception('checkUser failed');
            $stmt->bind_result($count);
            $stmt->fetch();
            if($count>0) $result = true; // already exists
            $stmt->close();
        } catch (Exception $exception) {
            /*$connection->rollback();
            print 'Rollback ' . $exception->getMessage();*/
            $connection->autocommit(true);
            if($stmt!=null) $stmt->close();
            $connection->close();
            return false;
        }
    }

    $connection->commit();
    $connection->close();

    return $result;
}

function checkEmail($username) {
    return filter_var($username, FILTER_VALIDATE_EMAIL) && htmlentities($username)==$username;
}

function register($user, $psw) {
    if(checkUser($user) || !checkEmail($user)) return false;

    $connection = db_get_connection();
    $result = false;
    $query = 'insert into user (user, password) values (?, ?)';
    if($stmt = $connection->prepare($query)) {

        $hash = md5($psw);

        $stmt->bind_param('ss', $user, $hash);
        try {
            if(!$stmt->execute())
                throw new Exception('register failed');
            $stmt->bind_result($result);
            $stmt->fetch();
            $result = password_verify($psw, $result);
            $stmt->close();
        } catch (Exception $exception) {
            $connection->rollback();
            print 'Rollback ' . $exception->getMessage();
            $connection->autocommit(true);
            if($stmt!=null) $stmt->close();
            $connection->close();
            return false;
        }
    }

    $connection->commit();
    $connection->close();

    return $result;
}