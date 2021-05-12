<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');

use OMCore\OMDb;
// use OMCore\OMMail;
$DB = OMDb::singleton();
$log = new OMCore\OMLog;
$response = array();
$today = date("Y-m-d H:i:s");
$exp = strtotime('+30 days', strtotime($today));
$expires = date('Y-m-d H:i:s', $exp);
$cmd = isset($_POST['cmd']) ? $_POST['cmd'] : "";

if ($cmd != "") {

// ---------------------------------------------------------------------------------------------------------------------------
    if ($cmd == "products") {
        $sql = "SELECT 
                p.product_id, 
                p.product_name, 
                p.product_detail, 
                c.category_name, 
                p.product_price, 
                p.product_discount_price,
                p.product_image,
                p.product_discount_status
                FROM tb_product p LEFT JOIN tb_category c ON p.category_id = c.category_id
                WHERE p.product_status = 'Y'";
        $sql_param = array();
        $ds = null;
        $res = $DB->query($ds, $sql, $sql_param, 0, -1, "ASSOC");
        $result = array();
        foreach($ds as $v) {
                $result[$v['category_name']][] = array(
                        'product_id'                => $v['product_id'],
                        'product_name'              => $v['product_name'],
                        'product_detail'            => $v['product_detail'],
                        'product_price'             => $v['product_price'],
                        'product_discount_price'    => $v['product_discount_price'],
                        'product_image'             => $v['product_image'],
                        'product_discount_status'   => $v['product_discount_status']
                );
        }

        $response['status'] = true;
        $response['data'] = $result;

    } else {
        $response['status'] = false;
        $response['error_msg'] = 'no command';
        $response['code'] = '500';
    
    }
// ---------------------------------------------------------------------------------------------------------------------------

} else {
    // error
    $response['status'] = false;
    $response['msg'] = 'no command';
    $response['code'] = '500';
}

echo json_encode($response);

    function call_youtube_video($url_search){
        $response = array();
        $curl = curl_init($url_search);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);  
        $result = curl_exec($curl); 
        curl_close($curl);

        if ($result) {
            $response['status'] = true;
            $response['data'] = json_decode($result);
        }else{
            $response['status'] = false;
        }
        
        return $response;
    }

    function file_force_contents($filename, $data, $flags = 0){
        if(!is_dir(dirname($filename)))
            mkdir(dirname($filename).'/', 0777, TRUE);
        return file_put_contents($filename, $data,$flags . FILE_APPEND);
    }

?>