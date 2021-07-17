<?php

namespace App\Sdks\BaiDu\Traits;

use App\Common\Tools\CustomException;

trait OcpcToken
{
    /**
     * @var
     * access token
     */
    protected $ocpcToken;

    /**
     * @param $ocpcToken
     * @return bool
     * 设置  ocpc token
     */
    public function setOcpcToken($ocpcToken){
        $this->ocpcToken = $ocpcToken;
        return true;
    }

    /**
     * @return mixed
     * @throws CustomException
     * 获取 ocpc token
     */
    public function getOcpcToken(){
        if(is_null($this->ocpcToken)){
            throw new CustomException([
                'code' => 'NOT_FOUND_OCPC_TOKEN',
                'message' => '尚未设置ocpc token',
            ]);
        }
        return $this->ocpcToken;
    }
}
