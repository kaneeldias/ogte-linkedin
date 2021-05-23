<?php

require_once __DIR__ . '/vendor/autoload.php';

$first_name = "Kaneel";
$last_name= "Dias";

$driveIds = [
    'CC' => "1wS4VnCVlrIE0NFJNaaY-tYw20UfrddLs",
    'CN' => "10v71ofv8lKFwKIV2-u8LDRsqFGTITvm0",
    'CS' => "1Ua4ZIzHj84GX8Eqn3epxQ-BJ4WKE0N8h",
    'USJ' => "1FL_cYa1TaqrkQce6ywft59g-_CcqjpGl",
    'Kandy' => "1CU8Hz3IXRuqlX33gOKrnVy_I-8uIn411",
    'Ruhuna' => "1TFiNXsf1dkj2ufzf7mjebTmpI-f0GW0Z",
    'SLIIT' => "1hy7jY0sdwkxeG6Ok4MbjnMkjNIpfk3HB"
];

$driveId = "1_7Ki-HCrwgJrOcvjHBtb_VhDcPeDMNni";


$client = new Google_Client();
$client->setApplicationName('Google Drive and PHP');
$client->setScopes([Google_Service_Drive::DRIVE]);
$client->setAccessType('offline');
$client->setAuthConfig(__DIR__ . '/credentials.json');
$drive_service = new Google_Service_Drive($client);

$data = file_get_contents("test.pdf");

function upload($data, $first_name, $last_name, $entity, $timestamp){

    global $client;
    global $driveIds;
    global $driveId;
    global $drive_service;

    //Upload to all CVs folder
    $file = new Google_Service_Drive_DriveFile($client);
    $file->setName($first_name." ".$last_name." ".date("Y-m-d H:i:s").'.pdf');
    $file->setMimeType('application/pdf');
    $file->setParents(["1O-aL_u4YP8T-VVy1zzqQYS1cH_5dxTg-"]);
    $createdFile = $drive_service->files->create($file, array(
        'data' => $data,
        'mimeType' => 'application/pdf',
        'uploadType' => 'media',
    ));

    //Upload to entity CV folder (or other)
    $file = new Google_Service_Drive_DriveFile($client);
    $file->setName($first_name." ".$last_name." ".$timestamp.'.pdf');
    $file->setMimeType('application/pdf');

    if (array_key_exists($entity, $driveIds)){
        $driveId = $driveIds[$entity];
    }

    $file->setParents([$driveId]);

    $createdFile = $drive_service->files->create($file, array(
        'data' => $data,
        'mimeType' => 'application/pdf',
        'uploadType' => 'media',
    ));

    return "https://drive.google.com/file/d/".$createdFile->getId();

}



?>
