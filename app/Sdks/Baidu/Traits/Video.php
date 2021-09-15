<?php

namespace App\Sdks\BaiDu\Traits;


trait Video
{


    /**
     * @param $file
     * @return mixed
     * 获取子账户账户
     */
    public function uploadVideo($file){
        $url = $this->getUrl('json/sms/service/VideoUploadService/addVideo');
        $pathInfo = pathinfo($file);

        $param = [
            'file' => $file,
            'signature' => $this->getMaterialMd5($file),
            'params' => [
                'format' => $pathInfo['extension'],
                'source' => 2,
                'videoName' => $pathInfo['filename'],
            ]

        ];
        return $this->formDataRequest($url, $param, 'POST');
    }



    public function readVideo($id){
        $url = $this->getUrl('json/sms/service/VideoService/getVideoInfos');
        $param = [
            'ids' => [$id]
        ];
        return $this->authRequest($url, $param, 'POST');
    }
}
