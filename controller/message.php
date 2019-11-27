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


class message extends DbConnect
{

    public $time    =   null ;
    public $ip      =   null;

    public function saveMessage($message)
    {
        if (!empty($message)) {
            $this->time =  date('Y-m-d h:m:s');
            $this->ip  =   $_SERVER['REMOTE_ADDR'];

            $sql = "insert into chat (user_id,message,time,ip) values (:userId,:message, :time, :ip)";
            $value_array = array('userId'=>$_SESSION['userId'],'message' => $message,'time'=>$this->time,'ip'=>$this->ip);

            $newUser = new general();
            $response = $newUser->CRUD_via_prepare($sql, $value_array, 'insert', false);
            if ($response['error']   ==  0) {
                echo $allChat = $this->fetchMessage();
            } else {
                echo 'some error occurred. <br />'.$response['error'];
            }
        } else {
            echo "Chat message can't be empty";
        }
    }


    public function fetchMessage()
    {
        $sql = 'SELECT a.*, b.name as userName
               FROM chat a
               JOIN user b
               ON a.user_id = b.id
               order by id asc';

        $getChat = new general();
        $response = $getChat->CRUD_via_prepare($sql, array(), 'select', true);

        if ($response['error']   ==  0) {
            if (!empty($response['data']) && (count($response['data'])>0)) {
                foreach ($response['data'] as $key => $row) {
                        $time = '';
                        echo '<div class="row" id="chat_'.$row['id'].'">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="main-chat-area">
                                            <label class="userName">'.$row['userName'].': </label>
                                            <span class="time">'.$time.'</span>
                                            <span class="msg">'.htmlentities($row['message']).'</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"></div>
                                 </div>';
                }
            } else {
                  echo 'No chat found';
            }
        } else {
            echo 'some error occurred. <br />'.$response['error'];
        }
    }
}

/*

By Hegel Motokoua

*/