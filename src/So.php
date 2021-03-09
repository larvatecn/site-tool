<?php
/**
 * @copyright Copyright (c) 2018 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 * @license http://www.larva.com.cn/license/
 */

namespace Larva\Site\Tool;

use Larva\Support\HttpClient;

/**
 * 360 搜索自动推送
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class So
{
    /**
     * 360 搜索推送
     * @param string $url
     * @param string $sid
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function push($url, $sid)
    {
        $token = static::token($url, $sid);
        $url = urlencode($url);
        return HttpClient::make()
            ->withUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36')
            ->get("https://s.360.cn/so/zz.gif?url={$url}&sid={$sid}&token={$token}");
    }

    /**
     * 检查是否收录页面
     * @param string $url
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function checkInclude(string $url)
    {
        $response = HttpClient::make()
            ->withUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36')
            ->get('get', "https://www.so.com/s?ie=utf-8&q={$url}");
        if (!strpos($response->body(), '找不到该UR')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 生成令牌
     * @param $url
     * @param $sid
     * @return string
     */
    public static function token($url, $sid): string
    {
        $n = array_reverse(str_split($url));
        $r = str_split($sid);
        $i = [];
        for ($s = 0; $s < 16; $s++) {
            $i[] = $r[$s] . ($n[$s] ?? "");
        }
        return implode('', $i);
    }

    /**
     * 获取推荐搜索
     * @param string $word
     * @return array|false
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function suggestion($word)
    {
        $response = HttpClient::make()
            ->withUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36')
            ->get("suggest", [
                'word' => $word,
            ]);
        if ($response->ok()) {
            $words = json_decode($response->body(), true);
            $ret = [];
            foreach ($words['result'] as $word) {
                $ret[] = $word['word'];
            }
            return $ret;
        } else {
            return false;
        }
    }
}
