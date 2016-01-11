<?php
$response = array();
$response['version'] = "0";
$response['maxVersion'] = "0";
$response['maxVersionDownloadLink'] = "";

$projectUrl = 'http://nesblog.com/gta-demago/';

if (isset($_POST['checksum'])) {

    $pathToVersions = 'versions/';
    $files = scandir($pathToVersions);
    foreach ($files as $file) {
        if (strpos($file, ".dll") !== false) {
            $version = floatval(substr($file, 0, strpos($file, ".dll")));

            if ($version > $response['maxVersion']) {
                $response['maxVersion'] = $version;
                $response['maxVersionDownloadLink'] = $projectUrl.'/ws/files/mod/'.$version.'.zip';
                $response['texturesLink'] = $projectUrl.'ws/files/textures/mods.zip';
                $response['musicsLink'] = $projectUrl.'ws/files/musics/music.zip';
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