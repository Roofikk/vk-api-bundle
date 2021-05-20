<?php


namespace roofikk\VkApiBundle\ApiClient\Facebook;

use Facebook\Facebook;

class FbAuth
{
    public function authorize($id, $app_secret)
    {
        $fb = new Facebook([
            'app_id' => $id,
            'app_secret' => $app_secret,
            'default_graph_version' => 'v2.10',
        ]);

        return $fb;
    }
}