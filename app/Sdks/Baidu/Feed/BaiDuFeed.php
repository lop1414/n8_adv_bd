<?php

namespace App\Sdks\BaiDu\Feed;

use App\Sdks\BaiDu\BaiDu;
use App\Sdks\BaiDu\Feed\Traits\AccountFeed;
use App\Sdks\BaiDu\Feed\Traits\AdgroupFeed;
use App\Sdks\BaiDu\Feed\Traits\CampaignFeed;

class BaiDuFeed extends BaiDu
{


    use AccountFeed;
    use CampaignFeed;
    use AdgroupFeed;

}
