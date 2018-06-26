//swiper
var mySwiper = new Swiper('.swiper-container', {
	prevButton:'#s1 .btn .prve_btn',
	nextButton:'#s1 .btn .next_btn',
})

var userSelect = {
	imgUrl:'',
	selectType:0,
	// domain:'http://campaign.royal-canin.cn/Public/home/images/'
	domain:'http://royal-canin.cdn.max-digital.cn/H5/royal/canin/images/'
}
$("#s2 .main").on('touchstart',function(event) {
	event.preventDefault();
});

//上传图片
var photoData = {
	realWidth : 0,
	realHeight : 0,
	imgRotation : 0,
	stampNum:0,
	pic:"",
	ratio:function(){
		return this.realWidth/this.realHeight;
	}
};
var fileInput = document.getElementById('filebtn');
var animalType;
fileInput.onchange = function() {
	TweenMax.set("#s2 .btn-wrap",{autoAlpha:0});
	TweenMax.set("#s2 .btn a,#s2 .text,#s2 .main,#s2 .up_btn",{autoAlpha:1});
	$(".default-img").hide();
	var myphoto = new Image();
	var file = fileInput.files[0];
	var $file = $(this);
	var fileObj = $file[0];
	var windowURL = window.URL || window.webkitURL;
	var dataURL;
	if(fileObj && fileObj.files && fileObj.files[0]){
    	dataURL = windowURL.createObjectURL(fileObj.files[0]);
   		myphoto.src = dataURL;
        myphoto.onload = function(){
        	EXIF.getData(myphoto, function(){
        	var orientation = EXIF.getTag(this, 'Orientation');
        	if(orientation == undefined||orientation==0){
            	orientation =1;
        	}
        	photoData.orientation = orientation;
        	photoData.realWidth = myphoto.width;
        	photoData.realHeight = myphoto.height;
			photoData.pic = new fabric.Image(myphoto, {
				visible:true,
				hasBorders:false,
				hasControls:false,
			});
			switch(orientation){
				case 1||undefined:
					photoData.pic.width = 310;
					photoData.pic.height = 310/photoData.ratio();
					photoData.pic.left = 0;
					photoData.pic.top = -2;
					break;
		 		//iphone横屏拍摄，此时home键在左侧
				case 3:
					photoData.pic.width = 310;
					photoData.pic.height = 310/photoData.ratio();
					photoData.pic.top= 310/photoData.ratio()-30;
					photoData.pic.left= 310;
					photoData.pic.angle = 180;
					break;
				//iphone竖屏拍摄，此时home键在下方(正常拿手机的方向)
				case 6:
					photoData.pic.width = 310*photoData.ratio();
					photoData.pic.height = 310;
					photoData.pic.top= -2;
					photoData.pic.left= 310;
					photoData.pic.angle = 90;
					break;
				//iphone竖屏拍摄，此时home键在上方
				case 8:
					photoData.pic.width = 310*photoData.ratio();
					photoData.pic.height = 310;
					photoData.pic.top= 310*photoData.ratio();
					photoData.pic.left= 0;
					photoData.pic.angle = 270;
					break;
			}
			//获取选择的type
			animalType = $("#s1 .swiper-slide-active").attr("data-type");
			if(animalType == 1){
				//显示小狗的框
				$("#s2 .main,#s3 .main").addClass('dogcanvas');
			}
			else{
				//显示小猫的框
				$("#s2 .main,#s3 .main").addClass('catcanvas');
			}
			$(this).remove();
			$("#s1 .swiper-slide .animal,#s1 .file_box,#s1 .btn a").fadeOut();
			TweenMax.set("#s2",{autoAlpha:1});
			userPhoto.backgroundColor = '#c3c3c3';
			userPhoto.add(photoData.pic).renderAll();
        });	
    };
}
};
var userPhoto = new fabric.Canvas('upcanvas');
	userPhoto.selection = false;



/********
s2 上传图片到oss
********/

//用客户cdn不用传到cdn

var s2cansubmit = true;
$("#s2 .up_btn").click(function(event) {
	if(s2cansubmit){
		s2cansubmit = false;
		$.ajax({
	        url: "http://api.max-digital.cn/Api/oss/baseUpload",
	        type:'post',
	        dataType:'json',
	        data:{
	        	imgdata : userPhoto.toDataURL({format: 'jpeg',quality: 0.8}),
	        	type : "jpeg",
	        	filepath :"H5/royal/cny",
	        },            
	        success:function(data){
	        	if(data.code == "OK"){
					if(animalType == 1){
						//显示小狗的框
						$("#s3 .main").addClass('dogcanvas');
					}
					else{
						//显示小猫的框
						$("#s3 .main").addClass('catcanvas');
					}
					$("#s3 .img").attr('src',data.oss_file_url);
					userSelect.imgUrl = data.oss_file_url;
					TweenMax.to("#s2",0.8,{autoAlpha:0});
					TweenMax.to("#s3",0.8,{autoAlpha:1});
	        	}
	        	else{
	        		alert(data.code)
	        	}
				s2cansubmit = true;
	        },
	        error:function(){
	            alert("网络繁忙");
	            s2cansubmit = true;                    
	        }
	    })
	}
});
$("#s2 .up_btn").click(function(event) {
	userSelect.selectType =1;
	$("#s3 .main").removeClass('dogcanvas');
	$("#s3 .main").removeClass('catcanvas');
	if(animalType == 1){
		//显示小狗的框
		$("#s3 .main").addClass('dogcanvas');
	}
	else{
		//显示小猫的框
		$("#s3 .main").addClass('catcanvas');
	}
	$("#s3 .img").attr('src',userPhoto.toDataURL({format: 'jpeg',quality: 0.8}));

	
	TweenMax.to("#s2",0.8,{autoAlpha:0});
	TweenMax.to("#s3",0.8,{autoAlpha:1});
})

$("#s2 .btn1").click(function(e){
	e.preventDefault();
	TweenMax.set("#s3 .main",{autoAlpha:0});
	TweenMax.to("#s2",0.8,{autoAlpha:0});
	TweenMax.to("#s3",0.8,{autoAlpha:1});
	$("#s3 .user-default").append(myClone);

});

var myClone;
$(".file_box").click(function(e){
	e.preventDefault();
	$("#s1 .swiper-slide .animal,#s1 .file_box,#s1 .btn a").fadeOut();
	TweenMax.set("#s2",{autoAlpha:1});
	myClone = $(".swiper-slide-active .animal").clone();
	$(".default-img").append(myClone);
	userSelect.imgUrl = userSelect.domain+$(".swiper-slide-active").data("imgsrc");
});

//切换框
$("#s2 .btn a").click(function(event) {
	$("#s2 .main").removeClass('dogcanvas');
	$("#s2 .main").removeClass('catcanvas');
	if(animalType == 1){
		animalType = 2;
		$("#s2 .main").addClass('catcanvas');
	}
	else if(animalType == 2){
		animalType = 1;
		$("#s2 .main").addClass('dogcanvas');
	}
});

/********
s3 提交到数据库
********/
//禁止输入回车
var test= document.getElementById("write_text");  
 test.onkeydown = function(e){  
    send(e);  
 }  
function send(e){  
    var code;  
    if (!e) var  e = window.event;  
    if (e.keyCode) code = e.keyCode;  
    else if (e.which) code = e.which;  
    if(code==13 && window.event){  
        e.returnValue = false;  
    }else if(code==13){  
        e.preventDefault();  
    }  
  
} 

var cansubmit = true;
$("#s3 .up_btn").click(function(event) {
	var val = $("#write_text").val();
	if(val == ""){
		alert("请写下你与TA的故事");
	    return false;
	}
	if(cansubmit){
		cansubmit = false;
		$.ajax({
			url: "xxxxxxx",
			type:'post',
			dataType:'json',
			data: {
				selectType:userSelect.selectType,//0存图片路径，1用imgsrc
				type : animalType,
				text : val,
				imgsrc : userSelect.imgUrl
			},
			success:function(data) {
				$("#s3 .share_ts").fadeIn();
				$('#s3 .disabled_box').show();
			},
			error:function(data) {
				alert("网络繁忙");
				cansubmit = true;
			}
		})
	}
});