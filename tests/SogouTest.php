<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 * @license http://www.larva.com.cn/license/
 */

namespace Larva\Site\Tool\Tests;

use Larva\Site\Tool\Sogou;


class SogouTest extends TestCase
{
    public function testGetRank()
    {
        $res = Sogou::getRank('https://www.aizham.com');
        $this->assertEquals($res, 1);
    }

    public function testCheckInclude()
    {
        $res = Sogou::checkInclude('https://www.aizham.com');
        $this->assertFalse($res);
    }

    public function testSuggestion()
    {
        $res = Sogou::suggestion('aaa');
        $this->assertIsArray($res);
    }
}
