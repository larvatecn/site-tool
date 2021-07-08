<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */

namespace Tests;

use Larva\Site\Tool\Alexa;

class AlexaTest extends TestCase
{
    public function testGetRank()
    {
        $res = Alexa::getRank('www.baidu.com');
        $this->assertIsArray($res);
    }
}
