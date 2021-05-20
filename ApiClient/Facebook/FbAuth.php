<?php


namespace roofikk\VkApiBundle\ApiClient\Facebook;

use Facebook\Facebook;

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

        return $fb;
    }
}