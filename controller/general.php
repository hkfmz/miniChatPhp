<?php
if (!class_exists('DbConnect')) {
    include __DIR__.'/../controller/baseClass.php'; // for server
}

class general extends DbConnect
{
    public function get_table_schema($table = "")
    {
        return $this->get_table_columns($table, true);
    }
    public function get_table_columns($table = "", $show_column_only = false)
    {
        $column_sql="SHOW FIELDS FROM ".$table;
        //echo $column_sql;
        $query=$this->link->query($column_sql);
        $row=$query->fetchAll(PDO::FETCH_ASSOC);

        $result = $this->link->query("SHOW VARIABLES LIKE '%version%'");
        $aa=$query->fetchAll(PDO::FETCH_ASSOC);
        $column_name=array();
        if (count($row)) {
            if ($show_column_only==false) {
                foreach ($row as $key => $value) {
                    //$column_name[$value['COLUMN_NAME']]=$value['COLUMN_NAME'];
                    $column_name[$value['Field']]=$value['Field'];
                }
            } else {
                $column_name=$row;
            }
            unset($row); // free up memory
            return $column_name;
        } else {
            return 'No Record Found';
        }

    }
    public function CRUD_via_prepare($sql = "", $value_array = "", $CRUD_type = "insert", $fetch_all = false)
    {
        $return_array=array();
        $query=$this->link->prepare($sql);   //sql values must be :id,:name.. format
        try {
            if ($CRUD_type=='insert') {
                try {
                    $done   =   $query->execute($value_array);
                    $return_array['error']=0;
                    $return_array['last_inserted_id']=$this->link->lastInsertId();
                } catch (Exception $e) {
                      $return_array['error']=1;
                      //add logger here
                }
            } elseif ($CRUD_type=='select') {
                if ($value_array == "" || (!is_array($value_array))) {
                            $value_array=array(); // execute command required array as an input
                }
                try {
                    $done   =   $query->execute($value_array);
                    $return_array['error']=0;
                    if ($fetch_all==false) {
                        $return_array['data']=$query->fetch(PDO::FETCH_ASSOC);
                    } else {
                        $return_array['data']=$query->fetchAll(PDO::FETCH_ASSOC);
                    }


                } catch (Exception $e) {
                          $return_array['error']=1;
                          //add logger here
                }
            } elseif ($CRUD_type=='update') {
            } elseif ($CRUD_type=='delete') {
            }



            /*
            $done   =   $query->execute($value_array);
            $return_array['msg']='done';
            $return_array['error']=0;
            if($CRUD_type   ==  'insert'){
                $return_array['last_inserted_id']=$this->link->lastInsertId();
            }
            else if($CRUD_type=='select'){
                if($fetch_all==false){
                    $return_array['data']=$query->fetch(PDO::FETCH_ASSOC);
                }
                else{
                    $return_array['data']=$query->fetchAll(PDO::FETCH_ASSOC);
                }
            }
            */
        } catch (PDOException $e) {
            $return_array['msg']=$e->getMessage();
            $return_array['last_inserted_id']='';
            $return_array['error']=1;
        }
        return $return_array;
    }


    public function get_single_record($table_name = "", $column_name = "id", $column_value, $custom_query = "")
    {
        $sql="SELECT count(*) as 'total' from ".$table_name." where ".$column_name." = '".$column_value."'";
        if ($custom_query!="" && $table_name=="") {
            $sql=$custom_query; // changing the custom query in sql variable (use when we want joins or subqueries)
        }
        $query=$this->link->query($sql);
        try {
            $row = $query->fetch(PDO::FETCH_ASSOC);
            return $row['total'];
        } catch (PDOExeption $e) {
            return 'Error #5007 Found<br />'.$e->getMessage();
        }
    }









    public function get_all_record($table_name = "clients", $order_by = "id", $order_type = "desc")
    {
        $dataset=array();
        $sql='SELECT * from '.$table_name.' order by '.$order_by.' '.$order_type;
        $query=$this->link->query($sql);
        try {
            $row=$query->fetchAll(PDO::FETCH_ASSOC);
            if (count($row)) {
                return $row;
            } else {
                return NRF;
            }
        } catch (PDOExeption $e) {
            return 'Error #5007 Found<br />'.$e->getMessage();
        }
    }

    public function get_total_number_of_record($table_name = "clients", $sql = "")
    {
        if (empty($sql) && !empty($table_name)) {
// make simple sql for full record
            $sql='SELECT count(*) as `total` FROM '.$table_name;
        } elseif (empty($table_name) && !empty($sql)) {
            $sql=$sql;
        } elseif (empty($table_name) && empty($sql)) {
            die("both parameter is missing.<br />Atlist you should must assing one paramater ");
        } else {
            die("parameters is quite confusing me.");
        }

        $query=$this->link->query($sql);
        $row=$query->fetch(PDO::FETCH_ASSOC);
        $total_row=$row['total'];
        if ($total_row==0 || !isset($total_row)) {
            $total_row=0;
        }
        return $total_row;
    }
    public function explode_all($string = "", $seprator = ",")
    {
        $string =rtrim($string, $seprator);
        $string=explode(',', $string);
        return $string;
    }
    public function emplode_all($array = "")
    {
        $string="";
        foreach ($array as $key => $value) {
            //$string.=$key.'-'.
        }
    }
    public function current_time()
    {
        echo '';
    }
    public function insert_record($sql = "")
    {
        $return_array=array();
        try {
            $stmt=$this->link->query($sql);
            $return_array['msg']='done';
            $return_array['last_inserted_id']=$this->link->lastInsertId();
            $return_array['error']=0;
        } catch (PDOException $e) {
            //print_r($e->getMessage());
            $return_array['msg']=$e->getMessage();
            $return_array['last_inserted_id']='';
            $return_array['error']=1;
        }
        return $return_array;
    }
    public function get_column_count_by_query($sql)
    {
        $query = $this->link->prepare($sql);
        $query->execute();
        return $query->rowCount();
    }
    public function get_record_by_custom_query($sql = "", $page = 1, $page_limit = 10, $is_custom_search = false, $is_pagination = true)
    {
        $page_limit;
        $data_set=array();
        $query = $this->link->prepare($sql);
        $query->execute();
        if ($query->rowCount()) {
            $data_set['total_data'] = $query->fetchAll(PDO::FETCH_ASSOC);
            $data_set['row_count'] = $total_row=$query->rowCount();
            if ($is_pagination == true) {
                $pagination=$this->pagination_row($total_row, $page_limit);
            }
            return $data_set;
        } else {
            return NRF;
        }
    }
    public function pagination_row($total_records = 100, $num_rec_per_page = 1)
    {
        //$num_rec_per_page=((isset($_GET['l2']))?$_GET['l2']:1);
        $fake_page_id=((isset($_GET['page']) && ($_GET['page']!=""))?$_GET['page']:1);
        $total_pages = ceil($total_records / $num_rec_per_page);
        if ((!isset($_GET['page'])) || ($_GET['page']=="")) {
            $_GET['page']=1;
            $page_limit=1;
        } else {
            $page_limit=$_GET['page'];
        }
        $matchcase=((isset($_GET['matchcase']) && ($_GET['matchcase']!=""))?'&matchcase='.$_GET['matchcase']:"");
        $pagination='<div class="all_pages">';
        for ($p=1; $p<=$total_pages; $p++) {
            $next_range=($p*$num_rec_per_page)-$num_rec_per_page;
            $url='?r='.$_GET['r'].$matchcase.'&l1='.$next_range.'&l2='.$num_rec_per_page.'&page='.$p;
            if (isset($_REQUEST['type'])) {
                $url .='&type='.$_REQUEST['type'];
            }
            $pagination.='<i class="'.(($fake_page_id==$p)?'page_selected':'').'"><a href="'.$url.'">'.$p.'</a></i>';
        }
        $pagination.='</div>';
        return $pagination;
    }
    public function create_page_range($range_array = "", $limit_2 = 10)
    {
        if (!array($range_array) || (!isset($range_array)) || ($range_array=='default')) {
            $range_array=array(5,10,20,50,'all');
        }
        $add_range="";
        foreach ($range_array as $page_key => $page_value) {
            if ($page_value==$limit_2 || $limit_2==MAX_PAGE_LIMIT) {
                $chkselect = 'selected';
                $new_range="";
            } else {
                $chkselect = '';
                $new_range = $limit_2;
            }
            echo '	<option value="'.$page_value.'" '.$chkselect.'>'.$page_value.'</option>';
        }
        // echo '<option value="'.$new_range.'" selected>'.$new_range.'</option>';
    }
    public function delete_record($table = "", $column_name = "", $column_val = "")
    {
        $sql='delete from '.$table.' where '.$column_name.'=' .$column_val;

        echo $sql;
    }
} // class close


function error_help($error_code = "")
{
    $err_msg="#Error ".$error_code." Found. click 
        <a href='void(0)' onClick=\"window.open('hemp.php?error_code=".$error_code."', 'err', 'left=400, top=100, status=0,scrollbars=1,width=500,height=500')\" target='_new'>
        here</a> for more details";
    return $err_msg;
}
function last_search($parameter = "")
{
    $refine_url ='?' . $_SERVER['QUERY_STRING'];
    if (isset($_REQUEST[$parameter])) {
        if (strlen($_REQUEST[$parameter])>0) {
            $last_search=$_REQUEST[$parameter];
        } else {
            $last_search='';
        }
    } else {
        $last_search='';
    }
    return $last_search;
}
function send_error_report($subject = "", $msg_body = "")
{
}

/*

By Hegel Motokoua

*/