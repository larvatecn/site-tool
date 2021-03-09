<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 * @license http://www.larva.com.cn/license/
 */

namespace Larva\Site\Tool;

use GuzzleHttp\Exception\GuzzleException;
use Larva\Support\Exception\ConnectionException;
use Larva\Support\HttpClient;

/**
 * 爱站API
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Aizhan
{
    /**
     * 获取爱站百度-网站权重
     * @param string $key
     * @param string|array $domains
     * @return array
     * @throws GuzzleException
     * @throws ConnectionException
     */
    public static function BaiduRank(string $key, $domains)
    {
        if (is_array($domains)) {
            $domains = implode('|', $domains);
        }
        return HttpClient::make()->postJSON("https://apistore.aizhan.com/baidurank/siteinfos/" . $key,[
            'domains' => $domains
        ]);
    }

}
