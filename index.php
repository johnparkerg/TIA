<?php
// GPT-3 connection
$curl = curl_init();
$theme = substr(urldecode($_GET["t"]),0,128);

if($theme!=""){
    $prompt = "Escribe, en español, un bonito y positivo mensaje de bendiciones y buenos deseos sobre ".$theme.". Escribe únicamente tres oraciones diferentes con una falta de ortografía:";
}
else{
    $prompt = "Escribe, en español, un bonito y positivo mensaje de bendiciones y buenos deseos. Escribe únicamente tres oraciones diferentes con una falta de ortografía:";
}
if(isset($_GET["debug"])){
    echo $theme."<br/>".$prompt;
    die();
}
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.openai.com/v1/completions',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
  "model": "text-davinci-002",
  "prompt": "'.$prompt.'",
  "temperature": 0.7,
  "max_tokens": 256,
  "top_p": 1,
  "frequency_penalty": 0.69,
  "presence_penalty": 0.38
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'Authorization: Bearer '.$_ENV["OPENAI_API_KEY"]
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$str = json_decode($response,true)["choices"][0]["text"];
$sentences = preg_split('/(?<=[.?!])\s+(?=[a-z])/i', $str);


//General Vars
$site = "www.DeseosPositivos.com";

//Set the Content Type
header('Content-type: image/jpeg');

// Create Image From Existing File and loag a generic background from bg
//$jpg_image = imagecreatefromjpeg('bg/'.$fondos[array_rand($fondos,1)]->fondo);
//$jpg_image = imagecreatefromjpeg(random_pic());
$jpg_image = resize_image(random_pic(),512,512,true);

// Allocate A Color For The Text
$white = imagecolorallocate($jpg_image, 255, 255, 255);
$red = imagecolorallocate($jpg_image, 255, 0, 0);
$black = imagecolorallocate($jpg_image, 0, 0, 0);

// Set Path to Font File
$font_path1 = random_pic('fonts/');
$font_path2 = random_pic('fonts/');
$font_path3 = random_pic('fonts/');

// Insert a random number of piolines
for ($x = 0; $x <= rand(1, 7); $x++) {
    $asset = random_pic('img/');
    list($newwidth, $newheight) = getimagesize($asset);
    $asset = imagecreatefrompng($asset);
    //$asset = resize_png($asset,64,64*$newheight/$newwidth,false);
    imagecopyresampled($jpg_image, $asset, rand(0,448), rand(0,448), 0, 0, 64,64*$newheight/$newwidth,$newwidth,$newheight);
}

// Set Text to Be Printed On Image
$saludo = wordwrap($sentences[0], 25, "\n");
$mensaje = wordwrap($sentences[1], 20, "\n");
$bendicion = wordwrap($sentences[2], 25, "\n");

$color1_raw = $colores[array_rand($colores,1)];
$color1 = imagecolorallocate($jpg_image, $color1_raw->r, $color1_raw->g, $color1_raw->b);
$color1_inv = imagecolorallocate($jpg_image, abs(255-$color1_raw->r), abs(255-$color1_raw->g), abs(255-$color1_raw->b));
$color2_raw = $colores[array_rand($colores,1)];
$color2 = imagecolorallocate($jpg_image, $color2_raw->r, $color2_raw->g, $color2_raw->b);
$color2_inv = imagecolorallocate($jpg_image, abs(255-$color2_raw->r), abs(255-$color2_raw->g), abs(255-$color2_raw->b));
$color3_raw = $colores[array_rand($colores,1)];
$color3 = imagecolorallocate($jpg_image, $color3_raw->r, $color3_raw->g, $color3_raw->b);
$color3_inv = imagecolorallocate($jpg_image, abs(255-$color3_raw->r), abs(255-$color3_raw->g), abs(255-$color3_raw->b));

// Print Text On Image
imagettfstroketext($jpg_image, 18, 0, 75, 50, $color1, $color1_inv, $font_path1, $saludo, 2);
imagettfstroketext($jpg_image, 30, 0, 50, 200, $color2, $color2_inv, $font_path2, $mensaje, 2);
imagettfstroketext($jpg_image, 18, 0, 125, 400, $color3, $color3_inv, $font_path3, $bendicion, 2);


$dimensions = imagettfbbox(20, 0, $font_path, $site);
$textWidth = abs($dimensions[4] - $dimensions[0]);
$x = imagesx($jpg_image) - $textWidth;
$y = imagesy($jpg_image) - 20;
imagettfstroketext($jpg_image, 20, 0, $x-10, $y, $white, $black, $font_path, $site, 2);

// Send Image to Browser

imagejpeg($jpg_image);

// Clear Memory
imagedestroy($jpg_image);

function imagettfstroketext(&$image, $size, $angle, $x, $y, &$textcolor, &$strokecolor, $fontfile, $text, $px) {
    for($c1 = ($x-abs($px)); $c1 <= ($x+abs($px)); $c1++)
        for($c2 = ($y-abs($px)); $c2 <= ($y+abs($px)); $c2++)
            $bg = imagettftext($image, $size, $angle, $c1, $c2, $strokecolor, $fontfile, $text);
   return imagettftext($image, $size, $angle, $x, $y, $textcolor, $fontfile, $text);
}

function random_pic($dir = 'bg')
{
    $files = glob($dir . '/*.*');
    $file = array_rand($files);
    return $files[$file];
}

function resize_image($file, $w, $h, $crop=FALSE) {
    list($width, $height) = getimagesize($file);
    $r = $width / $height;
    if ($crop) {
        if ($width > $height) {
            $width = ceil($width-($width*abs($r-$w/$h)));
        } else {
            $height = ceil($height-($height*abs($r-$w/$h)));
        }
        $newwidth = $w;
        $newheight = $h;
    } else {
        if ($w/$h > $r) {
            $newwidth = $h*$r;
            $newheight = $h;
        } else {
            $newheight = $w/$r;
            $newwidth = $w;
        }
    }
    $src = imagecreatefromjpeg($file);
    $dst = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

    return $dst;
}
function resize_png($file, $w, $h, $crop=FALSE) {
    list($width, $height) = getimagesize($file);
    $r = $width / $height;
    if ($crop) {
        if ($width > $height) {
            $width = ceil($width-($width*abs($r-$w/$h)));
        } else {
            $height = ceil($height-($height*abs($r-$w/$h)));
        }
        $newwidth = $w;
        $newheight = $h;
    } else {
        if ($w/$h > $r) {
            $newwidth = $h*$r;
            $newheight = $h;
        } else {
            $newheight = $w/$r;
            $newwidth = $w;
        }
    }
    $src = imagecreatefrompng($file);
    $dst = imagecreatetruecolor($newwidth, $newheight);
    imagealphablending( $dst, false );
    imagesavealpha( $dst, true );
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newwidth*$r, $width, $height);
    return $dst;
}
