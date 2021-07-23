<?php

namespace App\Sdks\BaiDu;



use App\Sdks\BaiDu\Traits\Error;
use App\Sdks\BaiDu\Traits\MultiRequest;
use App\Sdks\BaiDu\Traits\OcpcToken;
use App\Sdks\BaiDu\Traits\Token;
use App\Sdks\BaiDu\Traits\Account;
use App\Sdks\BaiDu\Traits\AccountPassword;
use App\Sdks\BaiDu\Traits\Request;

class BaiDu
{
    use Account;
    use AccountPassword;
    use Token;
    use OcpcToken;
    use Request;
    use MultiRequest;
    use Error;

    /**
     * 公共接口地址
     */
    const BASE_URL = 'https://api.baidu.com';




    /**
     * BaiDu constructor.
     * @param $accountName
     * @param $password
     * @param $token
     */
    public function __construct($accountName,$password,$token){
        $this->setAccountName($accountName);
        $this->setAccountPassword($password);
        $this->setToken($token);
    }

    /**
     * @param $uri
     * @return string
     * 获取请求地址
     */
    public function getUrl($uri){
        return self::BASE_URL .'/'. ltrim($uri, '/');
    }

    /**
     * @param string $path
     * @return string
     * 获取 sdk 路径
     */
    public function getSdkPath($path = ''){
        $path = rtrim($path, '/');
        $sdkPath = rtrim(__DIR__ .'/'. $path, '/');
        return $sdkPath;
    }
}
