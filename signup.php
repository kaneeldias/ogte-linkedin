<?php

    include_once "config.php";
    include_once "sheets.php";
    include_once "drive.php";

    if (!isset($_POST['product']) ||
        !isset($_POST['first_name']) ||
        !isset($_POST['last_name']) ||
        !isset($_POST['email']) ||
        !isset($_POST['phone']) ||
        !isset($_POST['education']) ||
        !isset($_FILES['cv']) ||
        !isset($_POST['g-recaptcha-response'])){
        $output = json_encode(array('type' => 'fail', 'text' => "Incomplete form"));
        die($output);
    }

    if (!isset($_POST['entity'])){
        $_POST['entity'] = "Others";
    }

    $product = $_POST['product'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $education = $_POST['education'];

    if ($_FILES['cv']['tmp_name'] != ""){
        $cv = file_get_contents($_FILES['cv']['tmp_name']);
    }
    $entity = $_POST['entity'];
    $gcaptcha = $_POST['g-recaptcha-response'];

    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array(
        'secret' => $gcaptcha_private,
        'response' => $gcaptcha,
        'remoteip' => $_SERVER['REMOTE_ADDR']
    );

    $curlConfig = array(
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => $data
    );

    $ch = curl_init();
    curl_setopt_array($ch, $curlConfig);
    $response = curl_exec($ch);
    curl_close($ch);

    $jsonResponse = json_decode($response);

    /*
    if (!$jsonResponse || !$jsonResponse->success === true) {
        $output = json_encode(array('type' => 'fail', 'text' => "Captcha invalid"));
        die($output);
    }
    */

    $date = new DateTime("now", new DateTimeZone('Asia/Colombo') );
    $timestamp = $date->format('Y-m-d H:i:s');

    $url = "Not provided";
    if (isset($cv)){
        $url = upload($cv, $first_name, $last_name, $entity, $timestamp);
    }

    $res = append([[$timestamp, $product, $first_name, $last_name, $email, $phone, $education, $url]], $entity);

    if ($res) {
        $output = json_encode(array('type' => 'success', 'text' => "Details successfully submitted."));
        die($output);
    } else{
        $output = json_encode(array('type' => 'fail', 'text' => "An unknown error occurred."));
        die($output);
    }



?>
