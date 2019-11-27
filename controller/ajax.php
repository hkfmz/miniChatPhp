<?php
error_reporting(E_ALL);
include_once(__DIR__.'/../security/setting.php');

if (isset($_REQUEST['task'])) {
    $data=@$_REQUEST['allData'];
    $dataSet=array();
    @parse_str($_REQUEST['allData'], $dataSet);
    $task_name=$_REQUEST['task'];
    //echo '<pre>'; print_r($task_name); echo '</pre>';


    switch($task_name){
        case 'addUser';
            include_once 'user.php';
            $user  =   new user;
            $status = $user->addUser($dataSet['userName']);
            echo $status;
            break;

        case 'saveChat';
            include_once 'message.php';
            $message  =   new message();
            $status  =   $message->saveMessage($dataSet['message']);
            echo $status;
            break;

        case 'sync';
            include_once 'message.php';
            $message  =   new message();
            $status  =   $message->fetchMessage();
            echo $status;
            break;

        case 'syncUser';
            include_once 'user.php';
            $message  =   new user();
            $status  =   $message->syncUser();
            echo $status;
            break;
    }
    /*
	echo '<pre>';
	print_r($dataSet);
	echo '</pre>';
	*/
}
/*

By Hegel Motokoua

*/
