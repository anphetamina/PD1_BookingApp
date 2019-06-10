<?php
include 'common.php';

define('REGISTRATION_SUCCESS', 1846);
define('REGISTRATION_FAILED', -71);
define('USERNAME_ALREADY_EXISTS', 1926);
define('USERNAME_NOT_VALID', 90);
define('PASSWORD_NULL', 17);

if (!empty($_POST)) {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action == 'register' && !$_SESSION['authenticated']) {
            if (isset($_POST['username']) && isset($_POST['password'])) {
                $username = $_POST['username'];
                $password = $_POST['password'];

                $response = register($username, $password);

                switch ($response) {
                    case REGISTRATION_SUCCESS:
                        echo 'Registrazione avvenuta con successo';
                        break;

                    case REGISTRATION_FAILED:
                        echo 'Registrazione fallita';
                        break;

                    case USERNAME_ALREADY_EXISTS:
                        echo 'Username già esistente';
                        break;

                    case USERNAME_NOT_VALID:
                        echo 'Username non valido';
                        break;

                    case PASSWORD_NULL:
                        echo 'Password non valida';
                        break;

                    default:
                        echo 'Azione proibita';
                        break;
                }
            }
        }
    }
}

// todo sanitize

function checkUser($username) {
    $connection = db_get_connection();
    $result = false;
    $count = 0;
    $query = "select count(*) from user where username=? for update";
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
    if (checkUser($user)) return USERNAME_ALREADY_EXISTS;
    if (!checkEmail($user)) return USERNAME_NOT_VALID;

    $connection = db_get_connection();
    //$result = false;
    $query = "insert into user (username, password) values (?, ?)";
    if($stmt = $connection->prepare($query)) {

        if (!$hash = password_hash($psw, PASSWORD_DEFAULT)) {
            $connection->close();
            return PASSWORD_NULL;
        }

        $stmt->bind_param('ss', $user, $hash);
        try {
            if(!$result = $stmt->execute())
                throw new Exception('register failed');
            /*$stmt->bind_result($result);
            $stmt->fetch();*/
            $stmt->close();
        } catch (Exception $exception) {
            $connection->rollback();
            print 'Rollback ' . $exception->getMessage();
            $connection->autocommit(true);
            if($stmt!=null) $stmt->close();
            $connection->close();
            return REGISTRATION_FAILED;
        }
    }

    $connection->commit();
    $connection->close();

    return REGISTRATION_SUCCESS;
}