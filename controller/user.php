<?php
//when you connect baseClass, $link will return as Database Connection String
// do not add baseClass in general.php. add this class in another controller file where DB is really required.

if (!class_exists('DbConnect')) {
    include __DIR__.'/baseClass.php';
}

if (!class_exists('general')) {
    //include __DIR__.'/general.php'; // for server
    include_once('general.php'); // for test
}


class user extends DbConnect
{

    public function addUser($userName)
    {
        if (!empty($userName)) {
            $query = new general;
            $sql = "select * from user where name ='" . $userName . "'";
            $response = $query->get_single_record('user', "name", $userName, '');
            if ($response == 1) {
                echo 'this username is already taken';
            } elseif ($response == 0) {
                return $this->registerUser($userName);
            } else {
                echo 'something wrong happen ';
            }
        } else {
            echo "username can't be empty";
        }
    }

    public function registerUser($userName)
    {

        $sql = "insert into user (name) values (:name)";
        $value_array = array('name'=>$userName);

        $newUser = new general();
        $response = $newUser->CRUD_via_prepare($sql, $value_array, 'insert', false);

        if ($response['error']   ==  0) {
            //set session for this user
            $userDetails = array('userName' => $userName, 'userId'   => $response['last_inserted_id']);
            $session_response = $this->registerUserToSession($userDetails);
            echo $session_response;
        } else {
            echo 'some error occurred. <br />'.$response['error'];
        }
    }


    public function registerUserToSession($userDetails)
    {
        try {
            if (!isset($_SESSION)) {
                session_start();
            }

            $_SESSION['userName']   =   $userDetails['userName'];
            $_SESSION['userId']     =   $userDetails['userId'];
            $_SESSION['time'] = date('Y-m-d h:i:s');

            return 1;
        } catch (Exception $e) {
            $e->getMessage();
        }

    }

    /**
     * @return mixed
     */
    public function syncUser()
    {
        $user = new general();
        $sql  = "select * from user where `name` = '".$_SESSION['userName']."'";
        $response = $user->CRUD_via_prepare($sql, '', 'select', false);
        $data = $response['data'];


        if (!isset($data['name'])) {
            session_destroy();
            return 0;
        } else {
            echo 'user found';
        }
    }
}

/*

By Hegel Motokoua

*/