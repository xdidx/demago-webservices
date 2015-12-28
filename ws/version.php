<?php
$response = array();
$response['version'] = "0";
$response['maxVersion'] = "0";
$response['maxVersionDownloadLink'] = "";

if (isset($_POST['checksum'])) {

    $pathToVersions = 'versions/';
    $files = scandir($pathToVersions);
    foreach ($files as $file) {
        if (strpos($file, ".dll") !== false) {
            $version = floatval(substr($file, 0, strpos($file, ".dll")));

            if ($version > $response['maxVersion']) {
                $response['maxVersion'] = $version;
                $response['maxVersionDownloadLink'] = 'http://did.sytes.net/projets/gta-demago/ws/files/mod/'.$version.'.zip';
                $response['texturesLink'] = 'http://did.sytes.net/projets/gta-demago/ws/files/textures/mods.zip';
                $response['musicsLink'] = 'http://did.sytes.net/projets/gta-demago/ws/files/musics/music.zip';
            }

            if (md5_file($pathToVersions.$file) == $_POST['checksum']) {
                $response['version'] = $version;
            }
        }
    }

    if ($response['version'] != "0") {
        $response['error'] = 0;
        $response['message'] = "La version du mod installé est la " . $response['version'];
    } else {
        $response['error'] = 1;
        $response['message'] = "Aucune version correspondante n'a été trouvée.";
    }

} else {
    $response['error'] = 1;
    $response['message'] = "Checksum non fourni";
}

echo json_encode($response);