<?php 

namespace Home\Controller;
use Think\Controller;
use Think\Think;
//session('fans_id',2);
class CommonController extends Controller {



    public function getShareInfo(){
        $is_weixin =1;// $this->is_weixin();
        $url = $this->currentUrl();
//        echo $url;
        if($is_weixin) {
            $signPackage = $this->getSignPackage($url);
//            dump($signPackage);
            $this->assign('signPackage', $signPackage);
        }
        $this->assign('host', "http://" . $_SERVER['HTTP_HOST']);
    }





    public function currentUrl(){
        $host = "http://" . $_SERVER['HTTP_HOST'];
        $uri = $_SERVER['REQUEST_URI'];
        if(strpos($uri,'godiva.max-digital.cn')){
            $url = $_SERVER['REQUEST_URI'];
        }else{
            $url = $host.$uri;
        }
        return $url;
    }


    public function getSignPackage($url) {
        $resurl = "http://api.max-digital.cn/api/index/getSignPackage?signid={$this->signid}&url=".urlencode($url);
        $data = $this->sub_curl($resurl);
        $signPackage = json_decode($data,true);
        return $signPackage;
    }



}


?>