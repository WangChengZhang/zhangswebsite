<?php
include_once 'initialization.php';

$characters = "3456789ABCDEFGHIJKLMNPQRSTUVWXY";
$code = '';

$img = imagecreatetruecolor ( 100, 30 );
imagefill ( $img, 0, 0, imagecolorallocate ( $img, rand ( 128, 255 ), rand ( 128, 255 ), rand ( 128, 255 ) ) ); // 设置背景颜色

imagerectangle ( $img, 0, 0, 99, 29, imagecolorallocate ( $img, 255, 0, 0 ) );//设置边框

//干扰点和干扰线
for($i=0; $i<50; $i++) {
	imagesetpixel($img, rand(1, 99), rand(1, 29), imagecolorallocate ( $img, rand ( 0, 127 ), rand ( 0, 127 ), rand ( 0, 127 ) ));
	if ($i<5){
		imageline($img, rand(1,45), rand(1,28), rand(55,98), rand(1,28), imagecolorallocate ( $img, rand ( 0, 127 ), rand ( 0, 127 ), rand ( 0, 127 ) ));
	}
}

for($i = 0; $i < 4; $i ++) {
	$code .= $characters {rand ( 0, strlen ( $characters - 1 ) )};//生成验证码
	imagechar($img,
			5,/*字体大小*/
			rand($i*25,$i*25+12),/*水平位置*/
			rand(0,15),/*垂直位置*/
			$code{$i},/*单个字符*/
			imagecolorallocate ( $img, rand ( 0, 127 ), rand ( 0, 127 ), rand ( 0, 127 ) )/*颜色*/);

}

//存储数据到session
$_SESSION['vcode'] = $code;//验证码
$_SESSION['vcodetime'] = time();//当前时间戳

//输出图像
header('Content-type: image/png');
imagepng($img);
imagedestroy($img);



