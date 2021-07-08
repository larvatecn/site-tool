<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */

namespace Larva\Site\Tool;

use Larva\Support\HttpClient;

/**
 * 淘宝
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Taobao
{
    /**
     * 获取推荐搜索
     * @param string $word
     * @return array|false
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function suggestion(string $word)
    {
        $response = HttpClient::make()
            ->withUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36')
            ->get("https://suggest.taobao.com/sug", [
                'q' => $word,
                'code' => 'utf-8',
            ]);
        if ($response->ok()) {
            $ret = [];
            foreach ($response->json()['result'] as $word) {
                $ret[] = $word[0];
            }
            return $ret;
        } else {
            return false;
        }
    }
}
