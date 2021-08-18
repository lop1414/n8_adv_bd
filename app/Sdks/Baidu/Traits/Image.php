<?php

namespace App\Sdks\BaiDu\Traits;


trait Image
{

    /**
     * @param $file
     * @return string
     * 图片转base64编码
     */
    public function imgToBase64($file){
        if($fp = fopen($file,"rb", 0))
        {
            $gambar = fread($fp,filesize($file));
            fclose($fp);

            return base64_encode($gambar);
        }
    }




    /**
     * @param $file
     * @param false $addImage
     * @return mixed
     * 获取子账户账户
     */
    public function uploadImage($file,$addImage = true){
        $url = $this->getUrl('json/sms/service/ImageManageService/uploadImage');
        $pathInfo = pathinfo($file);
        $param = [
            'items' => [
                [
                    'content' => $this->imgToBase64($file),
                    'imgmd5'  => $this->getMaterialMd5($file),
                    'imageName' => $pathInfo['filename']
                ]
            ],
            'addImage' => $addImage
        ];
        return $this->authRequest($url, $param, 'POST');
    }



    public function readImage($id){
        $url = $this->getUrl('json/sms/service/ImageManageService/getImageList');
        $param = [
            'filters' => [
                [
                    'field' => 'imageId',
                    'op'    => 'eq',
                    'values'=> [$id]
                ]
            ]
        ];
        return $this->authRequest($url, $param, 'POST');
    }
}
