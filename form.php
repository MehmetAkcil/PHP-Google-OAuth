<?php
include './GoogleOauth.php';

if(isset($_GET["login"])){

    // login url redirect
    $auth = new GoogleOauth("client_id", "secret_key");
    $url = $auth->login();
    header("Location: {$url}");
}


if(isset($_GET["callback"])){

    //callback
    $auth = new GoogleOauth("client_id", "secret_key");
    $detail = $auth->callback();

    if($detail === false){
        echo "Oturum acma islemi basarisiz";
    }


    $sub = $detail->sub;
    $picture = $detail->picture;
    $email = $detail->email;
    $email_verified = $detail->email_verified;


    echo "Sub: {$sub} <br />Picture: {$picture} <br />Email: {$email} <br />Verified: " . ($email_verified ? 'Basarili' : 'basarisiz');

}