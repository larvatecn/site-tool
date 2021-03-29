<?php
/**
 * @copyright Copyright (c) 2018 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 * @license http://www.larva.com.cn/license/
 */

namespace Larva\Site\Tool;

use GuzzleHttp\Exception\GuzzleException;
use Larva\Support\HttpClient;

/**
 * 搜狗站长工具
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Sogou
{
    /**
     * 获取搜狗 SR
     * @param $url
     * @return string|int
     * @throws GuzzleException
     */
    public static function getRank($url)
    {
        $response = HttpClient::make()
            ->withUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36')
            ->get("http://rank.ie.sogou.com/sogourank.php", [
                'ur' => $url
            ]);
        if ($response->ok()) {
            return intval(str_replace(['sogourank=', "\r", "\n"], '', $response->body()));
        } else {
            return 1;
        }
    }

    /**
     * 检查是否收录页面
     * @param string $url
     * @return bool
     * @throws GuzzleException
     */
    public static function checkInclude(string $url): bool
    {
        $response = HttpClient::make()
            ->withUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36')
            ->get("https://www.sogou.com/web?query={$url}");
        if (!strpos($response->body(), '点击此处提交')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取推荐搜索
     * @param string $word
     * @return array|false
     * @throws GuzzleException|\Larva\Support\Exception\ConnectionException
     */
    public static function suggestion(string $word)
    {
        $response = HttpClient::make()
            ->withUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36')
            ->get("http://w.sugg.sogou.com/sugg/ajaj_json.jsp", [
                'key' => $word,
                'type' => 'web'
            ]);
        if ($response->ok()) {
            $content = str_replace(['window.sogou.sug(', ",-1);"], '', mb_convert_encoding($response->body(), "UTF-8", "GB2312"));
            $arr = json_decode($content, true);
            return $arr[1];
        } else {
            return false;
        }
    }
}
