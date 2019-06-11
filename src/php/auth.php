<?php
include 'common.php';

define('LOGIN_SUCCESS', 1111);
define('LOGIN_FAILED', 121234);
define('LOGIN_ERROR', 231);


if (!empty($_POST)) {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action == 'login' && !$_SESSION['authenticated']) {
            if (isset($_POST['username']) && isset($_POST['password'])) {
                $username = $_POST['username'];
                $password = $_POST['password'];

                $response = login($username, $password);

                switch ($response) {
                    case LOGIN_SUCCESS:
                        startSession();
                        echo 'Login effettuato';
                        break;

                    case LOGIN_FAILED:
                        echo 'Password errata';
                        break;

                    case LOGIN_ERROR:
                        echo 'Login error';
                        break;

                    default:
                        echo 'Azione proibita';
                        break;
                }
            }
        }

        if ($action == 'logout' && $_SESSION['authenticated']) {
            echo logout();
        }
    }
}

function login($user, $psw) {
    $connection = db_get_connection();
    $result = false;
    $query = "select password from user where username=? for update";
    if($stmt = $connection->prepare($query)) {
        $stmt->bind_param('s', $user);
        try {
            if(!$stmt->execute())
                throw new Exception('login failed');
            $stmt->bind_result($hash);
            $stmt->fetch();
            $result = password_verify($psw, $hash);
            $stmt->close();
        } catch (Exception $exception) {
            /*$connection->rollback();
            print 'Rollback ' . $exception->getMessage();*/
            $connection->autocommit(true);
            if($stmt!=null) $stmt->close();
            $connection->close();
            return LOGIN_ERROR;
        }
    }

    $connection->commit();
    $connection->close();

    return ($result)? LOGIN_SUCCESS : LOGIN_FAILED;
}

function logout() {
    if(destroySession()) return true;

    return false;
}