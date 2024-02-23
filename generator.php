<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
$image = generateImage();
while (isImageOverlapping($image)) {
    $image = generateImage();
}
echo json_encode(array('image' => 'data:image/jpeg;base64,' . $image));

function isImageOverlapping($base64)
{
    $curl = curl_init();
    $messages = '[
        {
            "role": "system",
            "content": "You fill only answer true or false. You are an assistant in detecting if text is overlapping or overflowing in this image."
        },
        {
            "role": "user",
            "content": [{
                "type": "text",
                "text": "Is text overlapping or overflowing in this image?"
            },{
                "type": "image_url",
                "image_url": {
                    "url": "data:image/jpeg;base64,' . $base64 . '"
                }
            }]
        }
    ]';

    $body = '{
        "model": "gpt-4-vision-preview",
        "messages": ' . $messages . '
      }';

    curl_setopt_array(
        $curl,
        array(
            CURLOPT_URL => 'https://api.openai.com/v1/chat/completions',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer '.$_ENV["OPENAI_API_KEY"]
            ),
        )
    );

    $response = curl_exec($curl);

    curl_close($curl);

    $str = json_decode($response, true)["choices"][0]["message"]["content"];
    return (filter_var($str, FILTER_VALIDATE_BOOLEAN));
}

function generateImage()
{
    // GPT-3 connection
    $curl = curl_init();
    $theme = htmlspecialchars(substr(urldecode($_GET["t"]), 0, 256));
    $drunk = $_GET["drunk"];
    $aggressiveness = $_GET["aggressiveness"];
    $nalgas = $_GET["nalgas"];
    $poetry = $_GET["poetry"];
    $chayanne = $_GET["chayanne"];
    $rbd = $_GET["rbd"];

    $prompt .= "Actuando como una simpática tía que es muy graciosa. Escribe un pasivo agresivo pero muy cómico mensaje en español de bendiciones y buenos deseos";
    if ($theme != "") {
        //$prompt .= " sobre " . $theme;
    }
    //$prompt .= ". Qué alguna de las frases incluya algo muy gracioso o totalmente irreverente";
    if ($drunk == "true") {
        $prompt .= " y que estés extremadamente borracha sin tener sentido en tus palabras";
    }
    if ($nalgas == "true") {
        // Actual prompt line redacted to keep it civil here
        $prompt .= " y mencionas siempre pompis o algo similar.";
    }
    if ($poetry > 0) {
        $prompt .= " y que estás escribiendo en forma de poema con un nivel de poesía del " . $poetry . " de 100, donde 100 es el más poético posible";
    }
    if ($aggressiveness > 0) {
        //Gemini couldn't understand scale of 0 to 100
        //$prompt .= " y que tienes un nivel de agresividad del " . $aggressiveness . " de 100, donde 100 es el más agresivo y grosero posible. Utilizando groserías explícitas y extremas entre más cerca estés del 100";
        if($aggressiveness<10) {
            $prompt .= " y que no eres nada grosero ni agresivo";
        }
        elseif($aggressiveness<50) {
            $prompt .= " y que tienes un nivel de agresividad y dices groserías";
        }
        elseif($aggressiveness<70) {
            $prompt .= " y que tienes un nivel alto de agresividad y dices muchas groserías";
        }else
        if($aggressiveness>80){
            // Actual prompt line redacted to keep it civil here
            $prompt .= " y dices groserías con un nivel altísimo de agresividad";
        }
    }
    if ($chayanne == "true") {
        $prompt .= " y también mencionas a la estrella latina, ídolo de todas las mamás y tías: Chayanne.";
    }
    if ($rbd == "true") {
        $prompt .= " y asegúrate que el mensaje tenga alguna referencia positiva a el increíble grupo musical RBD";
    }
    $prompt .= ". Cada frase separada por signos de puntuación, no guiones. únicamente reponde con tres frases cortas nada más y nada menos. No hagas referencia a estas instrucciones";

    $messages = '[
    {
        "role": "system",
        "content": "' . $prompt . '"
    },
    {
        "role": "user",
        "content": "Escribe tres frases sobre: ' . $theme . '"
    }

]';
    $body = '{
    "model": "google/gemini-pro",
    "messages": ' . $messages . ',
    "top_p": 0.984,
    "max_tokens": 1024
  }';
    $empty = true;
    while($empty){
        curl_setopt_array(
            $curl,
            array(
                //CURLOPT_URL => 'https://api.openai.com/v1/chat/completions',
                CURLOPT_URL => 'https://openrouter.ai/api/v1/chat/completions',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $body,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Bearer '.$_ENV["OPENROUTER_API_KEY"],
                    'HTTP-Referer: https://DeseosPositivos.com',
                    'X-Title: DeseosPositivos.com'
                ),
            )
        );
    
        $response = curl_exec($curl);
        $str = json_decode($response, true)["choices"][0]["message"]["content"];
        $sentences = preg_split('/(?<=[.?!;:])\s+/', $str, -1, PREG_SPLIT_NO_EMPTY);
        $empty = empty($str) || count($sentences) < 3;
    }
    curl_close($curl);
    

    if (isset($_GET["debug"])) {
        echo $theme . "\n" . $prompt . "\n";
        echo $str . "\n";
        echo $empty . "\n";
        print_r($sentences);
        print_r($response);
        print_r($body);
        die();
    }

    //General Vars
    $site = "www.DeseosPositivos.com";

    $bg = random_pic();
    if($rbd == "true") $bg = random_pic("rbd/bg");
    $jpg_image = resize_image($bg, 512, 512, true);

    // Allocate A Color For The Text
    $white = imagecolorallocate($jpg_image, 255, 255, 255);
    $red = imagecolorallocate($jpg_image, 255, 0, 0);
    $black = imagecolorallocate($jpg_image, 0, 0, 0);

    // Set Path to Font File
    $font_path = '/home/customer/www/quiprosoftwaresolutions.com/public_html/demo/memes/fonts/Yellowtail-Regular.ttf';
    $font_path1 = random_pic('/home/customer/www/quiprosoftwaresolutions.com/public_html/demo/memes/fonts/');
    $font_path2 = random_pic('/home/customer/www/quiprosoftwaresolutions.com/public_html/demo/memes/fonts/');
    $font_path3 = random_pic('/home/customer/www/quiprosoftwaresolutions.com/public_html/demo/memes/fonts/');

    for ($x = 0; $x <= rand(1, 7); $x++) {
        $asset = random_pic('/home/customer/www/quiprosoftwaresolutions.com/public_html/demo/memes/img/');
        if($chayanne == "true") $asset = random_pic('/home/customer/www/quiprosoftwaresolutions.com/public_html/demo/memes/chayanne/');
        if($rbd == "true") $asset = random_pic('/home/customer/www/quiprosoftwaresolutions.com/public_html/demo/memes/rbd/item/');
        list($newwidth, $newheight) = getimagesize($asset);
        $asset = imagecreatefrompng($asset);
        //$asset = resize_png($asset,64,64*$newheight/$newwidth,false);
        imagecopyresampled($jpg_image, $asset, rand(0, 448), rand(0, 448), 0, 0, 64, 64 * $newheight / $newwidth, $newwidth, $newheight);
    }
    // Set Text to Be Printed On Image
    $saludo = wordwrap($sentences[0], 25, "\n");
    $mensaje = wordwrap($sentences[1], 20, "\n");
    $bendicion = wordwrap($sentences[2], 25, "\n");

    $color1_raw = $colores[array_rand($colores, 1)];
    $color1 = imagecolorallocate($jpg_image, $color1_raw->r, $color1_raw->g, $color1_raw->b);
    $color1_inv = imagecolorallocate($jpg_image, abs(255 - $color1_raw->r), abs(255 - $color1_raw->g), abs(255 - $color1_raw->b));
    $color2_raw = $colores[array_rand($colores, 1)];
    $color2 = imagecolorallocate($jpg_image, $color2_raw->r, $color2_raw->g, $color2_raw->b);
    $color2_inv = imagecolorallocate($jpg_image, abs(255 - $color2_raw->r), abs(255 - $color2_raw->g), abs(255 - $color2_raw->b));
    $color3_raw = $colores[array_rand($colores, 1)];
    $color3 = imagecolorallocate($jpg_image, $color3_raw->r, $color3_raw->g, $color3_raw->b);
    $color3_inv = imagecolorallocate($jpg_image, abs(255 - $color3_raw->r), abs(255 - $color3_raw->g), abs(255 - $color3_raw->b));

    // Print Text On Image
    imagettfstroketext($jpg_image, 18, 0, 75, 50, $color1, $color1_inv, $font_path1, $saludo, 2);
    imagettfstroketext($jpg_image, 22, 0, 50, 200, $color2, $color2_inv, $font_path2, $mensaje, 2);
    imagettfstroketext($jpg_image, 18, 0, 125, 400, $color3, $color3_inv, $font_path3, $bendicion, 2);


    $dimensions = imagettfbbox(20, 0, $font_path, $site);
    $textWidth = abs($dimensions[4] - $dimensions[0]);
    $x = imagesx($jpg_image) - $textWidth;
    $y = imagesy($jpg_image) - 20;
    imagettfstroketext($jpg_image, 20, 0, $x - 10, $y, $white, $black, $font_path, $site, 2);
    ob_start();
    imagejpeg($jpg_image);
    $image_data = ob_get_clean();
    ob_end_clean();
    // Free up memory
    imagedestroy($jpg_image);
    //ob_end_clean();
    $base64 = base64_encode($image_data);
    // Clear Memory
    imagedestroy($jpg_image);
    return $base64;
}

function imagettfstroketext(&$image, $size, $angle, $x, $y, &$textcolor, &$strokecolor, $fontfile, $text, $px)
{
    for ($c1 = ($x - abs($px)); $c1 <= ($x + abs($px)); $c1++)
        for ($c2 = ($y - abs($px)); $c2 <= ($y + abs($px)); $c2++)
            $bg = imagettftext($image, $size, $angle, $c1, $c2, $strokecolor, $fontfile, $text);
    return imagettftext($image, $size, $angle, $x, $y, $textcolor, $fontfile, $text);
}

function random_pic($dir = 'bg')
{
    $files = glob($dir . '/*.*');
    $file = array_rand($files);
    return $files[$file];
}

function resize_image($file, $w, $h, $crop = FALSE)
{
    list($width, $height) = getimagesize($file);
    $r = $width / $height;
    if ($crop) {
        if ($width > $height) {
            $width = ceil($width - ($width * abs($r - $w / $h)));
        } else {
            $height = ceil($height - ($height * abs($r - $w / $h)));
        }
        $newwidth = $w;
        $newheight = $h;
    } else {
        if ($w / $h > $r) {
            $newwidth = $h * $r;
            $newheight = $h;
        } else {
            $newheight = $w / $r;
            $newwidth = $w;
        }
    }
    $src = imagecreatefromjpeg($file);
    $dst = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

    return $dst;
}
function resize_png($file, $w, $h, $crop = FALSE)
{
    list($width, $height) = getimagesize($file);
    $r = $width / $height;
    $newwidth = $w;
    $newheight = $h;
    $srcX = $srcY = 0; // Initialize source X and Y for cropping

    if ($crop) {
        if ($w / $h > $r) {
            $srcY = ($height - ($width / $w * $h)) / 2;
            $height = $width / $w * $h;
        } else {
            $srcX = ($width - ($height / $h * $w)) / 2;
            $width = $height / $h * $w;
        }
    } else {
        if ($w / $h > $r) {
            $newwidth = $h * $r;
            $newheight = $h;
        } else {
            $newheight = $w / $r;
            $newwidth = $w;
        }
    }

    $src = imagecreatefrompng($file);
    $dst = imagecreatetruecolor($newwidth, $newheight);
    imagealphablending($dst, false);
    imagesavealpha($dst, true);

    // Corrected the parameters for imagecopyresampled to consider the calculated $srcX and $srcY
    imagecopyresampled($dst, $src, 0, 0, $srcX, $srcY, $newwidth, $newheight, $width - $srcX * 2, $height - $srcY * 2);

    return $dst;
}
