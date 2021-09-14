<?php

namespace App\Services;

use App\Common\Services\ClickService;
use App\Common\Tools\CustomException;
use App\Enums\FixStatusEnum;
use App\Enums\QueueEnums;
use App\Models\PageClickModel;


class AdvPageClickService extends ClickService
{
    /**
     * constructor.
     */
    public function __construct(){
        parent::__construct(QueueEnums::PAGE_CLICK);
    }

    /**
     * @param $data
     * @return mixed
     * @throws CustomException
     * 数据过滤
     */
    public function dataFilter($data){

        if(!empty($data['link'])){
            if($data['link'] == base64_encode(base64_decode($data['link']))){
                $data['link'] = base64_decode($data['link']);
            }
        }

        $clickAt = date('Y-m-d H:i:s', intval($data['click_at'] / 1000));

        $ret = parse_url($data['link']);
        parse_str($ret['query'], $param);

        return [
            'ip'       => $data['ip'],
            'ua'       => $data['ua'],
            'click_at' => $clickAt,
            'bd_vid'   => $param['bd_vid'],
            'extends'  => $data
        ];
    }

    /**
     * @param $data
     * @return bool|void
     * 创建
     */
    protected function create($data){
        $clickModel = new PageClickModel();
        $clickModel->bd_vid = $data['bd_vid'];
        $clickModel->ip = $data['ip'] ?? '';
        $clickModel->ua = $data['ua'] ?? '';
        $clickModel->click_at = $data['click_at'] ?? null;
        $clickModel->extends = $data['extends'] ?? [];
        $clickModel->fix_status = FixStatusEnum::WAITING;
        $ret = $clickModel->save();
        return $ret;
    }
}
