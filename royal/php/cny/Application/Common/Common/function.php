<?php
function getPrizeName($id){
	if(empty($id)){
		return false;
	}
	return M('Prize')->where('id='.$id)->getField('name');
}
// 处理带Emoji的数据，type=0表示写入数据库前的emoji转为HTML，为1时表示HTML转为emoji码
function deal_emoji($msg, $type = 0) {
	if ($type == 0) {
		$msg = urlencode ( $msg );
		$msg = json_encode ( $msg );
	} else {

		$msg = preg_replace ( "#\\\u([0-9a-f]+)#ie", "iconv('UCS-2','UTF-8', pack('H4', '\\1'))", $msg );

		// $msg = preg_replace("#(\\\ue[0-9a-f]{3})#ie", "addslashes('\\1')",$msg);

		$msg = urldecode ( $msg );
		// $msg = json_decode ( $msg );
		// dump($msg);
		$msg = str_replace ( '"', "", $msg );
		// dump($msg);exit;
		if ($txt !== null) {
			$msg = $txt;
		}
	}

	return $msg;
}
function show_emoji($msg){
    require_once CORE_PATH.'emoji.php';
    $msg = emoji_softbank_to_unified($msg);
    $msg = emoji_unified_to_html($msg);
    return $msg;
}
function hideTel($phone){
    if($phone){
        $phone = substr($phone, 0, 3).'****'.substr($phone, 7, 4);
    }
    return $phone;
}
function getNicknameByid($id,$is_web=1){
    if($id){
        $nickname = M('Fans')->where("id={$id}")->getField('nickname');
        if($is_web==1){
            $nickname = show_emoji(deal_emoji($nickname,1));
        }else{
            $nickname = deal_emoji($nickname,1);
        }
    }else{
        $nickname = '';
    }
    return $nickname;
}











?>