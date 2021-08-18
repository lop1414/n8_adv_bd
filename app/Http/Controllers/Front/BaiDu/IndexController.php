<?php

namespace App\Http\Controllers\Front\BaiDu;

use App\Common\Controllers\Front\FrontController;
use App\Common\Enums\AdvClickSourceEnum;
use App\Common\Enums\ConvertTypeEnum;
use App\Common\Enums\PlatformEnum;
use App\Common\Services\SystemApi\AdvBdApiService;
use App\Common\Services\SystemApi\AdvOceanApiService;
use App\Models\BaiDu\BaiDuAdgroupModel;
use App\Models\BaiDu\BaiDuCreativeModel;
use App\Services\BaiDu\BaiDuAdgroupService;
use App\Services\BaiDu\BaiDuCreativeService;
use Illuminate\Http\Request;

class IndexController extends FrontController
{
    /**
     * constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }



    public function test(Request $request){
        $key = $request->input('key');
        if($key != 'aut'){
            return $this->forbidden();
        }

//        $this->testCreateClick();
//        $this->testModelData();
//        $this->testConvertMatch();
//        $this->testConvertCallbackGet();
//        $this->testUpdateChannelAdgroup();
    }

    private function testCreateClick(){
        $data = [
            'click_at' => '1612583307000',
            'request_id' => 'n8_411275a61dd0047114cd6dd4b99365d0',
            'adgroup_id' => '6015275314',
            'creative_id' => '297322444811',
            'ip' => '117.136.34.110',
            'ua' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_8) AppleWebKit/537.13+ (KHTML, like Gecko) Version/5.1.7 Safari/534.57.',
            'oaid' => '4D0B7036EEDD48D0AE5162D6ECC72195e0dbbcfb11954949fd72e665e42a68b0',
            'android_id'  => 'fe48755b0e43c1b3048bd862b6786f42'
        ];
        $a = new AdvBdApiService();
        $ret = $a->apiCreateClick($data, AdvClickSourceEnum::N8_TRANSFER);
        dd($ret);
    }

    private function testConvertMatch(){
        $converts = [
            [
                'convert_type' => ConvertTypeEnum::PAY, // 转化类型
                'convert_id' => 6666, // 转化id
                'convert_at' => '2021-02-06 11:48:30', // 转化时间
                'convert_times' => 1, // 转化次数(包含当前转化)
                'request_id' => 'n8_411275a61dd0047114cd6dd4b99365d0',
                'muid' => '',
                'oaid' => '',
                'oaid_md5' => '',
                'ip' => '127.0.0.1',
                'ua' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_8) AppleWebKit/537.13+ (KHTML, like Gecko) Version/5.1.7 Safari/534.57.',
                // 联运用户信息
                'n8_union_user' => [
                    'guid' => 1,
                    'channel_id' => 47287,
                    'created_at' => '2021-02-06 11:48:26',
                    'click_source' => AdvClickSourceEnum::ADV_CLICK_API,
                ],
            ],
            [
                'convert_type' => ConvertTypeEnum::PAY, // 转化类型
                'convert_id' => 888, // 转化id
                'convert_at' => '2021-02-06 11:48:30', // 转化时间
                'convert_times' => 1, // 转化次数(包含当前转化)
                'request_id' => '',
                'muid' => '',
                'oaid' => '',
                'oaid_md5' => '',
                'ip' => '117.136.34.110',
                'ua' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_8) AppleWebKit/537.13+ (KHTML, like Gecko) Version/5.1.7 Safari/534.57.',
                // 联运用户信息
                'n8_union_user' => [
                    'guid' => 1,
                    'channel_id' => 47287,
                    'created_at' => '2021-02-06 11:48:26',
                    'click_source' => AdvClickSourceEnum::ADV_CLICK_API,
                ],
            ],
        ];
        $a = new AdvBdApiService();
        $ret = $a->apiConvertMatch($converts);
        dd($ret, 'testConvertMatch');
    }

    private function testConvertCallbackGet(){
        $a = new AdvBdApiService();
        $ret = $a->apiGetConvertCallbacks([
            [
                'convert_type' => ConvertTypeEnum::PAY, // 转化类型
                'convert_id' => 6666, // 转化id
            ],[
                'convert_type' => ConvertTypeEnum::PAY, // 转化类型
                'convert_id' => 888, // 转化id
            ],
        ]);
        dd($ret, 'testConvertCallbackGet');
    }

    public function testModelData(){
        $a = new OceanAccountData();
//        $a->setParams(['account_id' => 1672178687569997, 'app_id' => '1646386495126539']);
        $a->setParams(['id' => 123, 'app_id' => 1646386495126539]);
        $item123 = $a->read();
        $a->setParams(['id' => 124]);
        $item124 = $a->read();
        $a->setParams(['id' => 1234]);
        $item1233 = $a->read();
//        $a->clear();
//        $a->clearAll();
//        dd($a->where('id', '>=', 123)->orderBy('id', 'asc')->first());
        dd($item123, $item124, $item1233, 'item');
    }

    public function testUpdateChannelAdgroup(){
        $channelId = 228;

        $adgroupIds = [
            '1690757112343566',
            '1690757111708711',
        ];

        $a = new AdvBdApiService();
        $a->apiUpdateChannelAdgroup($channelId, $adgroupIds,PlatformEnum::DEFAULT,[1]);
    }


}
