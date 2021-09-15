<?php
// 分享朋友圈
$str = <<<HTML
<html>
	<head>
		<meta charset=utf-8>
		<meta http-equiv=X-UA-Compatible content="IE=edge,chrome=1">
		<meta name=renderer content=webkit>
		<meta name=viewport content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
	</head>
	<body>
		<script type="text/javascript">
			function go(){
				var title = "👉无需发朋友圈，点我继续阅读下一章👈";
				var content = "描述";
				var iconUrl = "http://storage-n8-center.7788zongni.com/read_icon.jpg";
				var linkUrl = "{{LINK_URL}}";

				if(isBaidu()){
					if(isIOS()){
						var timestamp = Date.parse(new Date());
						var shareUrl = "baiduboxapp://callShare?" + ["options=" + encodeURIComponent(JSON.stringify({
							type: "url",
							mediaType: "weixin_timeline",
							iconUrl: iconUrl,
							title: title,
							content: content,
							linkUrl: linkUrl,
							shareSuccessCB: "shareSuccessCallback",
							shareErrorCB: "shareFailCallback"

						}))];
						shareUrl = shareUrl + "&minver=5.3.5.0&successcallback=shareSuccessCallback" + timestamp + "&errorcallback=shareFailCallback" + timestamp;
						location.href = shareUrl;
					}else{
						prompt("BdboxApp:" + JSON.stringify({
							obj: "Bdbox_android_utils",
							func: "callShare",
							args: ['{' +
							'imageUrl: "",' +
							'mediaType: "weixin_timeline",' +
							'title: "' + title +
							'",content: "' + content +
							'",linkUrl: "' + linkUrl +
							'",iconUrl: "' + iconUrl + '" }',
								"window.NativeShareSuccessCallback", "window.NativeShareFailCallback"]
						}))
					}
				}else{
					location.href = "weixin://";
				}
			}

			function isAndroid(){
				var ua = navigator.userAgent;
				return /(Android);?[\s\/]+([\d.]+)?/.test(ua);

			}

			function isIOS(){
				var ua = navigator.userAgent;
				var ue = /(iPad).*OS\s([\d_]+)/.test(ua);
				return !ue && /(iPhone\sOS)\s([\d_]+)/.test(ua);
			}

			function isBaidu(){
				var ua = navigator.userAgent;
				return /baidu/.test(ua);
			}

			go();
		</script>
	</body>
</html>
HTML;

$linkUrl = urldecode($_GET['url']);
$str = str_replace("{{LINK_URL}}", $linkUrl, $str);

include "./function.php";

$logidUrl = $_SERVER['REQUEST_SCHEME'] .'://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$ip = getIp();
$ua = $_SERVER["HTTP_USER_AGENT"];

$reportUrl = 'https://n8-adv-bd.7788zongni.com/front/forward_click?';
$param = [
        'sign'=> '87a47565be4714701a8bc2354cbaea36',
        'ip' => $ip,
        'ua' => $ua,
        'click_at' => time() .'000',
        'link' => $logidUrl,
];
$reportUrl .= http_build_query($param);
$ret = file_get_contents($reportUrl);

echo $str;
