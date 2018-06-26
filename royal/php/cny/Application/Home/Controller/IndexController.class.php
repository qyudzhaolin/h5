<?php
namespace Home\Controller;
use Think\Controller;
use Think\Think;
  //session('fans','ocrzDjpdSyStJOLldZiiwNirAKjQ');
class IndexController extends Controller
{
    protected  $appid = 'wxdbe851a8c75d23d8';//appid
    protected  $key = 'tuisong ';//key
    protected $signid = 4;//
//   public $domain = "http://campaign.royal-canin.cn";
    public $domain = "http://www.ceshi.com";

    public function index(){
        $openid = session('openid');
        $host = 'http://'.$_SERVER['HTTP_HOST'];
        if($this->is_weixin()) {
            if (empty($openid)) {
                $back_url = urlencode($host . '/Home/index/getcode');
                $auth_url = "http://api.max-digital.cn/Api/index/authSimple?url={$back_url}&signid={$this->signid}";
                redirect($auth_url);
            }

            //分享Code
        }
        $this->getShareInfo();
        if ($openid) {
//            $fans = M('Fans')->find($fans_id);
            $fans['openid'] = $openid;
        }
        $this->assign('userinfo',json_encode($fans));
        $this->display('index');
        

    }
    public function getcode(){
        $data = I('get.');
        print_r($data);
        $openid = $data['openid'];
        if($openid){
            session('openid',$openid);
            $this->redirect('index/index');
        }else{
            echo 'error';exit;
        }
    }
   public function share(){
        $this->getShareInfo();
        $id = intval(I('id'));
        $info = M('Info')->find($id);
        $this->assign('info',$info);
        $this->display('share');
    }

    

    public function clear(){
        dump(session('fans_id'));
        session('fans_id',null);
        dump(session('fans_id'));
    }


    public  function cardurl(){
        $province_list=M('store');
        $list=$province_list->distinct(true)->field('city')->order('city DESC')->select();
        $this->assign('List',$list);
        $this->display('url');

    }

    /**
     * 分享
     */

    public function getShareInfo(){
        $is_weixin = $this->is_weixin();
        if($is_weixin) {
            $uri = $_SERVER['REQUEST_URI'];
            $url = "http://" . $_SERVER['HTTP_HOST'].$uri;
            $codeurl = urlencode($url);
            $request_url = "http://api.max-digital.cn/Api/index/getSignPackage?url={$codeurl}&signid=4";
            $data = $this->sub_curl($request_url);
            $signdata = json_decode($data,true);
            //最后一次的匹配记录
            $this->assign('signPackage', $signdata);
        }

    }


    public function auth2()
    {

        $host = "http://" . $_SERVER['HTTP_HOST'];
        $url = urlencode($host . "/home/Index/getCode");
        $request_url = "http://api.max-digital.cn/Api/index/auth?url={$url}&signid={$this->signid}";
      
        redirect($request_url);
    }


    public function getSignPackage($url)
    {


        $resurl = "http://www.arvatocrm.cn/Home/OauthApi/get_jssdk";
        $sign=md5($this->key.'e76a288e81110b0d49728ff0f3b1b9d4'.date('Y-m-d'));
        $postdata['key'] = $this->key;
        $postdata['sign'] = $sign;
        $postdata['appid'] = $this->appid;
        $postdata['url'] = urlencode($url);
        var_dump($postdata);
        $data = $this->sub_curl($resurl,$postdata,1);
        $signPackage = json_decode($data, true);
        var_dump($signPackage);
        return $signPackage;
    }



    /*public function getCode()
    {
        $data=$_GET;

         session('fans',$data);
        // $openid = I('get.openid');
        // if ($openid) {
        //     session('openid', $openid);
        // } else {
        //     $this->auth2();
        // }

        $this->redirect('index/index');
    }*/
    protected function is_weixin()
    {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == true) {
            return true;
        }
        return false;
    }
    //curl操作
    protected function sub_curl($url, $data = array(), $is_post = 1)
    {
        $ch = curl_init();
        if (!$is_post)//get 请求
        {
            $url = $url . '?' . http_build_query($data);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, $is_post);
        if ($is_post) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $info = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $info;
    }

    public function saveInfo()
    {

        $data['type']=I('type');
        $data['content']=I('text');
        $img=I('imgsrc');
        $data['time']=date('Y-m-d H:i:s');
        $data['img']=$img;
        $data['select_type']=I('post.selectType');
        $insert_id=M('Info')->add($data);
        if($insert_id){
            $this->ajaxReturn(array('status'=>'1','msg'=>'success','id'=>$insert_id));
        }
        else{

            $this->ajaxReturn(array('status'=>'0','msg'=>'data error'));
        }

     
    }

 



 



 










}
