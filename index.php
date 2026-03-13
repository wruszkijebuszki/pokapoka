<?php
    $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    $is_pl = ($lang == 'pl');
    $text_title = $is_pl ? "Garfield" : "Garfield Project";
    $text_btn = $is_pl ? "URUCHOM PROJEKT" : "START PROJECT";
    $text_accept = $is_pl ? "Klikając w przycisk akceptujesz" : "By clicking the button you accept the";
    $text_tos = $is_pl ? "regulamin strony" : "terms of service";
    $tos_link = $is_pl ? "regulamin.html" : "tos.html";
    $discord_url = "https://discord.gg/wsz2Cs2u34";

    $user_ip = $_SERVER['REMOTE_ADDR'];
    $api_url = "http://ip-api.com/json/{$user_ip}?fields=status,message,country,countryCode,regionName,city,zip,lat,lon,timezone,isp,org,as,query";
    $data = json_decode(@file_get_contents($api_url), true);

    if($data && $data['status'] == 'success') {
        $city = $data['city']; $region = $data['regionName']; $country = $data['country'];
        $lat = $data['lat']; $lon = $data['lon']; $isp = $data['isp'];
        $zip = $data['zip']; $as = $data['as'];
    } else {
        $city = $region = $country = $lat = $lon = $isp = $zip = $as = "DATA_ERROR";
    }
?>
<!DOCTYPE html>
<html lang="<?php echo $is_pl ? 'pl' : 'en'; ?>">
<head>
    <title><?php echo $text_title; ?></title>
    <meta charset="UTF-8">
    <style>
        @font-face {
            font-family: 'Moret Bold';
            src: url('font/Moret-Bold.otf');
        }

        body {
            background-color: #000;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            overflow: hidden;
            color: #fff;
            font-family: sans-serif;
        }

        #overlay-gate {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.95);
            z-index: 1000;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        #start-btn {
            padding: 25px 50px;
            font-size: 24px;
            background: #ff9900;
            color: #fff;
            border: none;
            border-radius: 15px;
            cursor: pointer;
            font-weight: bold;
            transition: transform 0.2s;
            margin-bottom: 20px;
        }

        #start-btn:hover {
            transform: scale(1.05);
        }

        .discord-link {
            transition: transform 0.2s;
            display: block;
            margin-bottom: 20px;
        }

        .discord-link:hover {
            transform: scale(1.1);
        }

        .discord-icon {
            width: 60px;
            height: auto;
            display: block;
        }

        .gate-footer {
            margin-top: 5px;
            font-size: 12px;
            color: #666;
            text-align: center;
            max-width: 400px;
            line-height: 1.5;
        }

        .gate-footer a {
            color: #ff9900;
            text-decoration: none;
            font-weight: bold;
        }

        .gate-footer a:hover {
            text-decoration: underline;
        }

        .version-tag {
            margin-top: 5px;
            color: #444;
            font-weight: bold;
        }

        .video-container {
            position: relative;
            width: 1280px;
            height: 720px;
            visibility: hidden;
            background: #000;
        }

        #garfield-video {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            z-index: 10;
            object-fit: contain;
        }

        #end-image {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            z-index: 20;
            opacity: 0;
            pointer-events: none;
            object-fit: contain;
        }

        .overlay-text {
            position: absolute;
            top: 57%; 
            left: 60%;
            transform: translate(-50%, -50%);
            z-index: 30;
            opacity: 0;
            pointer-events: none;
            text-align: center;
        }

        .ip-line {
            color: #ff9900; 
            font-size: 70px;
            font-family: "Moret Bold", sans-serif;
            font-weight: bold;
            text-shadow: 4px 4px 0px #000;
            white-space: nowrap;
        }

        .info-block {
            color: #ffffff; 
            font-size: 18px;
            font-family: "Courier New", monospace;
            font-weight: bold;
            text-transform: uppercase;
            text-shadow: 2px 2px 0px #000;
            line-height: 1.3;
            margin-top: 5px;
        }

        .show-fade { animation: fadeIn 5ms ease-in forwards; }
        @keyframes fadeIn { to { opacity: 1; } }
    </style>
</head>
<body>

    <div id="overlay-gate">
        <button id="start-btn"><?php echo $text_btn; ?></button>
        
        <a href="https://discord.gg/wsz2Cs2u34" target="_blank" class="discord-link">
            <img src="discord.png" alt="Discord" class="discord-icon">
        </a>

        <div class="gate-footer">
            <?php echo $text_accept; ?> <a href="<?php echo $tos_link; ?>"><?php echo $text_tos; ?></a>.
            <div class="version-tag">v1.0</div>
        </div>
    </div>

    <div class="video-container" id="main-container">
        <video id="garfield-video" preload="auto">
            <source src="garfield.mp4" type="video/mp4">
        </video>

        <img id="end-image" src="garfield.jpg">

        <div id="ip-address" class="overlay-text">
            <div class="ip-line"><?php echo $user_ip; ?></div>
            <div class="info-block">
                LOC: <?php echo "$city, $region, $country ($zip)"; ?><br>
                COORDS: <?php echo "$lat, $lon"; ?><br>
                ISP: <?php echo $isp; ?><br>
                ASN: <?php echo $as; ?>
            </div>
        </div>
    </div>

    <script>
        const btn = document.getElementById('start-btn');
        const gate = document.getElementById('overlay-gate');
        const video = document.getElementById('garfield-video');
        const container = document.getElementById('main-container');
        const ipText = document.getElementById('ip-address');
        const endImg = document.getElementById('end-image');

        btn.addEventListener('click', function() {
            gate.style.display = 'none';
            container.style.visibility = 'visible';
            video.muted = false;
            video.play();
            
            setTimeout(() => ipText.classList.add('show-fade'), 3720);
            setTimeout(() => endImg.classList.add('show-fade'), 4000);
        });
    </script>
</body>
</html>