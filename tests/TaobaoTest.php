<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */

namespace Tests;

use Larva\Site\Tool\Taobao;

/**
 * Class TaobaoTest
 * @author Tongle Xu <xutongle@gmail.com>
 */
class TaobaoTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSuggestion()
    {
        $res = Taobao::suggestion('aaa');
        $this->assertIsArray($res);
    }
}
