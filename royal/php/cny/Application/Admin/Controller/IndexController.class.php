<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Think;

class IndexController extends CommonController {
	public $checklogin = true;
	private $pagesize = 20;


    public function index(){
//        echo date('w');
        
$name=htmlspecialchars(I('name'));
$phone=htmlspecialchars(I('phone'));
$start_time=I('start_time');
$end_time=I('end_time');

if($name){
    $data['name']=$name;
}
if($phone){
   $data['phone']=$phone;  
}
if($start_time&&$end_time){

$data['date']=array('EGT',$start_time);
$data['date']=array('ELT',$end_time);
}

        $count = M('Info')->where($data)->count();
        $Page = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        $list = M('Info')->where($data)->order('date')->limit($Page->firstRow.','.$Page->listRows)->select();
       $this->assign('list',$list);// 赋值数据集
       $this->assign('page',$show);// 赋值分页输出
       $this->display(); // 输出模板
    }

   


    public function export(){
        //导入PHPExcel类库，因为PHPExcel没有用命名空间，只能import导入
        //数据库读取数据
      $name=htmlspecialchars(I('name'));
$phone=htmlspecialchars(I('phone'));
$start_time=I('start_time');
$end_time=I('end_time');

     if($name){
    $data['name']=$name;
}
if($phone){
   $data['phone']=$phone;  
}
if($start_time&&$end_time){

$data['date']=array('EGT',$start_time);
$data['date']=array('ELT',$end_time);
}


        $lists = M('Info')->where($data) ->select();
        $header = array(
            'name'=>'姓名',
            'phone'=>'电话',
            'date'=>'日期'
        );
        $this->outPut($lists,$header);
    }

	
	 protected function outPut($list=array(),$headtitle=array()){
        //dump($list);exit;
        $header = implode("\t",array_values($headtitle));
//        $header = "\"" .$header;
        $header .= "\t\n";
        $content .= $header;
        $filename = '申领信息'.date('YmdHis').'.xls';
        ob_end_clean();
        header("Expires: ".gmdate("D, d M Y H:i:s")." GMT");
        header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
        header("X-DNS-Prefetch-Control: off");
        header("Cache-Control: private, no-cache, must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/force-download");
        header("Content-Disposition: attachment; filename=".$filename);

        $content=iconv("UTF-8","GBK//IGNORE",$content) ;
        echo $content;
        foreach($list as $row)
        {
            $row['cdate'] = date('Y-m-d H:i',$row['cdate']);
            $new_arr = array();
            $content = "";
            foreach ($headtitle as $key1 => $value)
            {
                array_push($new_arr, $row[$key1]);
            }
            $line = implode("\t",$new_arr);
            //$line = "\"" .$line;
            $line .= "\t\n";
            $content .= $line;
            $content=@iconv("UTF-8","GBK//IGNORE",$content) ;
            echo $content;
        }
    }
	
	
	
	


	
	
	
	
	
	
}