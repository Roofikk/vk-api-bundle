<?php


namespace roofikk\VkApiBundle\ApiClient\Facebook;

use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

class FbAuth
{
    protected $id;
    protected $app_secret;

    public function __construct($id, $app_secret)
    {
        $this->id = $id;
        $this->app_secret = $app_secret;
    }

    public function authorize()
    {

        $fb = new Facebook([
            'app_id' => $this->id,
            'app_secret' => $this->app_secret,
            'default_graph_version' => 'v2.10',
        ]);

        $helper = $fb->getRedirectLoginHelper();
        try {
            $accessToken = $helper->getAccessToken();
        } catch(FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        if (isset($accessToken)) {
            // Logged in!
            $_SESSION['facebook_access_token'] = (string) $accessToken;

            // Now you can redirect to another page and use the
            // access token from $_SESSION['facebook_access_token']
        }

        return $fb;
    }
}