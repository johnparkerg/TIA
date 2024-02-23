<?php
$rbd = $_GET["route"]=="/rbd";
?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>DeseosPositivos.com</title>
    <!-- The following loads latest bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
    <style>
        @media (min-width: 512px) {
            .container {
                width: 512px;
            }
        }

        div#imageHolder {
            padding: 0;
            margin: 0;
        }

        body.keyboard {
            height: calc(100% + 500px);
            /* add padding for keyboard */
        }

        input {
            font-size: 16px !important;
        }
    </style>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?=$_ENV["GTAGID"]?>"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', '<?=$_ENV["GTAGID"]?>');
    </script>
</head>

<body>
    <!-- A very beautiful bootstrap mobile friendly landing page with a logo, an image, a text input and a button. All stacked on top of each other. -->
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1>DeseosPositivos<span class="text-muted">.com</span></h1>
            </div>
            <div class="col-4 text-center">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="customSwitch1">
                    <label class="custom-control-label" for="customSwitch1">Borracho</label>
                </div>
            </div>
            <div class="col-4 text-center">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="customSwitch2">
                    <label class="custom-control-label" for="customSwitch2">Nalgas</label>
                </div>
            </div>
            <div class="col-4 text-center">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="customSwitch3" <?php if($rbd) { echo "checked=checked"; } ?>>
                    <label class="custom-control-label" for="customSwitch3"><?php if($rbd) { echo "RBD"; } else { echo "Chayanne"; } ?></label>
                </div>
            </div>
            <!-- This next col-12 has a full width agressiveness slider that goes from 0 to 100 -->
            <div class="col-12 border-top m-3"></div>
            <div class="col-10 text-center">
                <label for="customRange1">Agresividad</label>
                <input type="range" class="custom-range" min="0" max="100" id="customRange1">
            </div>
            <div class="col-2 text-center d-flex">
                <!-- Random Generator -->
                <button type="button" class="btn btn-secondary w-100 p-2" id="random">
                    <!-- shuffle icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-shuffle" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M0 3.5A.5.5 0 0 1 .5 3H1c2.202 0 3.827 1.24 4.874 2.418.49.552.865 1.102 1.126 1.532.26-.43.636-.98 1.126-1.532C9.173 4.24 10.798 3 13 3v1c-1.798 0-3.173 1.01-4.126 2.082A9.6 9.6 0 0 0 7.556 8a9.6 9.6 0 0 0 1.317 1.918C9.828 10.99 11.204 12 13 12v1c-2.202 0-3.827-1.24-4.874-2.418A10.6 10.6 0 0 1 7 9.05c-.26.43-.636.98-1.126 1.532C4.827 11.76 3.202 13 1 13H.5a.5.5 0 0 1 0-1H1c1.798 0 3.173-1.01 4.126-2.082A9.6 9.6 0 0 0 6.444 8a9.6 9.6 0 0 0-1.317-1.918C4.172 5.01 2.796 4 1 4H.5a.5.5 0 0 1-.5-.5"/>
  <path d="M13 5.466V1.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384l-2.36 1.966a.25.25 0 0 1-.41-.192m0 9v-3.932a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384l-2.36 1.966a.25.25 0 0 1-.41-.192"/>
</svg>
            </div>
            <!--div class="col-12 text-center">
                <input type="range" class="custom-range" min="0" max="100" id="customRange2">
                <label for="customRange2">Poesía</label>
            </div-->
            <div class="col-12 text-center">
                <div class="col-12" id="imageHolder">
                    <img class="img-responsive d-none" alt="Image" id="image">
                </div>
                <div class="spinner-border d-none" role="status" id="loader">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <!-- This next col-12 has a share button that shares the image with id image>
            <div class="col-12 text-center">
                <button type="button" class="btn btn-secondary w-100 p-2" id="share">Compartir</button>
            </div-->
        </div>
        <div class="row fixed-bottom">
            <div class="col-12 text-center">
                <input type="text" class="form-control" placeholder="Enter text" id="text" onfocus="this.select();"
                    onmouseup="return false;">
            </div>
            <div class="col-12 text-right">
                <button type="button" class="btn btn-primary w-100 p-5" id="generate">Generar</button>
            </div>
            <script>
                // Random button function
                var random = document.getElementById("random");
                random.onclick = function () {
                    //Set customSwitch1, customSwitch2, customSwitch3 and customRange1 to random values
                    document.getElementById("customSwitch1").checked = Math.random() >= 0.5;
                    document.getElementById("customSwitch2").checked = Math.random() >= 0.5;
                    document.getElementById("customSwitch3").checked = Math.random() >= 0.5;
                    document.getElementById("customRange1").value = Math.floor(Math.random() * 101);
                }
                // Get the button
                var generate = document.getElementById("generate");
                // a function that will be called when the button is clicked and will show a loader on top of the image and then fetch an image from an endpoint and replace the image with the new image
                generate.onclick = function () {
                    // remove d-none class from loader

                    var oldImage = document.getElementById("image");
                    oldImage.parentNode.removeChild(oldImage);
                    var loader = document.getElementById("loader");
                    loader.classList.remove("d-none");
                    // fetch image
                    var image = document.createElement("img");
                    //Get the text from the input and url encode it into a variable
                    var text = document.getElementById("text").value;
                    var encoded = encodeURIComponent(text);
                    var url = "generator.php?t=" + encoded + "&id=" + makeid(5);
                    // Add drunk = true and aggressiveness = x to the url according to the switch and slider
                    url += "&drunk=" + document.getElementById("customSwitch1").checked;
                    url += "&aggressiveness=" + document.getElementById("customRange1").value;
                    url += "&nalgas=" + document.getElementById("customSwitch2").checked;
                    url += "&chayanne=" + document.getElementById("customSwitch3").checked;
                    //url += "&poetry=" + document.getElementById("customRange2").value;
                    <?php if($rbd) { ?> url += "&rbd=true"; <?php } ?>
                    url += "&base64=true";
                    // Get url and fetch the image in base 64 with AllowOriginHeader
                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function (data) {
                            image.src = data.image;
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.error('Error loading image:', errorThrown);
                        }
                    });
                    image.className = "img-responsive";
                    image.className = "w-100";
                    image.id = "image";
                    image.onload = function () {
                        // add d-none class to loader
                        loader.classList.add("d-none");
                        // replace image
                        var imageHolder = document.getElementById("imageHolder");
                        imageHolder.append(image);
                    }
                    this.blur();
                }

                function makeid(length) {
                    let result = '';
                    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                    const charactersLength = characters.length;
                    let counter = 0;
                    while (counter < length) {
                        result += characters.charAt(Math.floor(Math.random() * charactersLength));
                        counter += 1;
                    }
                    return result;
                }
                // focus events don't bubble, must use capture phase
                document.body.addEventListener("focus", event => {
                    const target = event.target;
                    switch (target.tagName) {
                        case "INPUT":
                        case "TEXTAREA":
                        case "SELECT":
                            document.body.classList.add("keyboard");
                    }
                }, true);
                document.body.addEventListener("blur", () => {
                    document.body.classList.remove("keyboard");
                }, true); 
            </script>
</body>

</html>