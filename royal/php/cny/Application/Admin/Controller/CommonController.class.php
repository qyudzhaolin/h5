<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Think;

class CommonController extends Controller {
	//public $checklogin = true;
    public function __construct() {
        parent::__construct();
		if($this->checklogin==true){
            //echo $this->checklogin;exit;
			$this->checkaccess();
		}
        $this->assign('controller_name', CONTROLLER_NAME);
        $this->assign('action_name', ACTION_NAME);
    }
    public function checkaccess(){
        if(session('uid')){
            return true;
        }else{
            $this->redirect("/Admin/login/index");
        }
    }
    
}