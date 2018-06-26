
if(/Android (\d+\.\d+)/.test(navigator.userAgent)){
    var version = parseFloat(RegExp.$1);
    if(version>2.3){
      var phoneScale = parseInt(window.screen.width)/640;
      document.write('<meta name="viewport" content="width=640, minimum-scale = '+ phoneScale +', maximum-scale = '+ phoneScale +', target-densitydpi=device-dpi">');
    }else{
      document.write('<meta name="viewport" content="width=640, target-densitydpi=device-dpi">');
    }
}else{
    document.write('<meta name="viewport" content="width=640, user-scalable=no, target-densitydpi=device-dpi">');
}
function isMobile(mobile){
    var patt =/^1[34578]{1}\d{9}$/;
    var re = new RegExp(patt);
    if(re.test(mobile)){
        return $.trim(mobile);
    }else{
        return false;
    }
}

document.addEventListener('DOMContentLoaded', function () {
  // (function audioAutoPlay() {
    //     var audio = document.getElementById('audio');
    //         audio.play();
    //     document.addEventListener("WeixinJSBridgeReady", function () {
    //         audio.play();
    //     }, false);
    // })();
});
/*********************************************
 Preload 预加载图片
 **********************************************/
 var queue = new createjs.LoadQueue();
 // var posterPhoto;
 function preload(){
     queue.installPlugin(createjs.Sound);
     queue.on("progress", handleLoadStart);
     queue.on("complete", handleComplete);
     queue.setMaxConnections(5);
     queue.loadManifest([
     ]);
 }

var stage;
function handleLoadStart(event) {
 // document.getElementById("loading-text").innerHTML = Math.floor(queue.progress * 100) + "%";
}
function handleComplete() {
 loadingOpen();
}

