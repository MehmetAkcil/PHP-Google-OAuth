<?php

class GoogleOauth

{

    public string $client_id;
    public string $redirect_uri;
    public string $auth_url;
    public string $client_secret;
    public string $token_url;
    public string $info_url;

    public function __construct($client_id, $client_secret)
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->redirect_uri = 'http://localhost/form.php?callback';
        $this->auth_url = 'https://accounts.google.com/o/oauth2/auth';
        $this->token_url = "https://www.googleapis.com/oauth2/v3/token";
        $this->info_url = "https://www.googleapis.com/oauth2/v3/userinfo?access_token=";
    }


    public function login(): string
    {

        $params = [
            'response_type' => 'code',
            'access_type' => "offline",
            'prompt' => "consent",
            'client_id' => $this->client_id,
            'redirect_uri' => $this->redirect_uri,
            'scope' => 'https://www.googleapis.com/auth/userinfo.email'
        ];
        $this->auth_url .= '?' . http_build_query($params);


        return $this->auth_url;

    }


    public function callback(): mixed
    {
        $code = $_GET['code'];
        $params = array(
            'grant_type' => 'authorization_code',
            'code' => $code,
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'redirect_uri' => $this->redirect_uri
        );
        $token_request = curl_init($this->token_url);
        curl_setopt($token_request, CURLOPT_POST, true);
        curl_setopt($token_request, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($token_request, CURLOPT_RETURNTRANSFER, true);
        $token_response = curl_exec($token_request);
        curl_close($token_request);
        $token_data = json_decode($token_response);

        $access_token = $token_data->access_token ?? null;

        if($access_token !== null ){
            $detail = file_get_contents($this->info_url . $access_token);
            return json_decode($detail);
        }

        return false;
    }

}