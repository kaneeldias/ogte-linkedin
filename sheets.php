<?php

require_once __DIR__ . '/vendor/autoload.php';

$client = new Google_Client();
$client->setApplicationName('Google Sheets and PHP');
$client->setScopes([Google_Service_Sheets::SPREADSHEETS]);
$client->setAccessType('offline');
$client->setAuthConfig(__DIR__ . '/credentials.json');
$sheet_service = new Google_Service_Sheets($client);

$spreadsheetIds = [
    'CC' => "15Ts37hFoveRIf9SP8gUWeTlBgxCIUOp4VtednMRWTI8",
    'CN' => "1BcAy1cV2mKADshW8NdV0ps_AaRCNpmR467YbyZpdYos",
    'CS' => "11MEvrd_feBGgEGcwewRSFxChaW2S6uS3DG5ZjG434BE",
    'USJ' => "1i4bbKzQNWYRV7RO-hZxU2BWn4WI68Zk_KBMjZtCpDOM",
    'Kandy' => "1wzPZy9iKgPNPCcMlTZ8a_EroraxoczhqhfIBeW8mbcs",
    'Ruhuna' => "1eyayItK2U7Xy2uLsCUokhVxuvtrDYUAqpOYH3Zu_m1I",
    'SLIIT' => "1PB0BhWJ17vgKcHOybvm3GFEXGmY8AYeototr21x_59A"
];

$spreadsheetId = "18YJN97bZAyqZJZ04gd7TbyHhNm3_klvuJDG-ApMaPwQ";

function append($values, $entity){

    global $sheet_service;
    global $spreadsheetId;
    global $spreadsheetIds;

    if (array_key_exists($entity, $spreadsheetIds)){
        $spreadsheetId = $spreadsheetIds[$entity];
    }

    $body = new Google_Service_Sheets_ValueRange([
        'values' => $values
    ]);

    $all_values = $values;
    array_splice( $all_values[0], 1, 0, [$entity] ); // splice in at position 3
    $all_body = new Google_Service_Sheets_ValueRange([
        'values' => $all_values
    ]);

    $params = [
        'valueInputOption' => 'USER_ENTERED'
    ];

    $range = 'Sign-Ups';

    //Append to all sheet
    $result = $sheet_service->spreadsheets_values->append("17CrbJe5ewkkbXA6rnkzxB7Qo4juoi29MzO6yV-r2icU",
        $range, $all_body, $params);

    //Append to entity sheet (or other)
    $result = $sheet_service->spreadsheets_values->append($spreadsheetId, $range, $body, $params);
    if($result->getUpdates()->getUpdatedCells() == 8){
        return true;
    }

    return false;

}



?>
