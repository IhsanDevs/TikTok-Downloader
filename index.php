<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
require "lib/simple_html_dom.php";
// Start function
function error_msg_json($msg = "Oups.. invalid URL"){
    $error = [
        "status" => false,
        "msg" => $msg
    ];
    echo json_encode($error);
};
// End
if (!isset($_GET['url_video']) || empty($_GET['url_video'])) { // Kondisi jika parameter `url_video` kosong / tidak ada.
    error_msg_json("Error. parameter `url_video` kosong!");
    exit;
}
$target = $_GET['url_video'];
$url_video = new simple_html_dom();
$url_video = file_get_html("https://tiktokdownloader.one");
$token = $url_video->find("meta", 2)->content; // Ambil token downloader pada tag meta
$endPoit = "https://tiktokdownloader.one/api/v1/fetch?url=$target&is_copy_url=1";
$header = [
    "token: $token",
];
$curl = curl_init();
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
curl_setopt($curl, CURLOPT_URL, $endPoit);
curl_setopt($curl, CURLOPT_REFERER, $endPoit);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.93 Safari/537.36 Edg/90.0.818.56");
$str = curl_exec($curl);
curl_close($curl);
$dataDecoded = json_decode($str);
if (isset($dataDecoded->error)) { // Kondisi jika ada key `error`
    error_msg_json("Error. video tidak ditemukan!");
    exit;
}
$success = [
    "status" => true,
    "results" => $dataDecoded
];
echo json_encode($success);