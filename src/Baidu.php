<?php
/**
 * @copyright Copyright (c) 2018 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 * @license http://www.larva.com.cn/license/
 */

namespace Larva\Site\Tool;

use GuzzleHttp\Exception\GuzzleException;
use Larva\Support\Exception\ConnectionException;
use Larva\Support\HttpClient;

/**
 * 百度站长工具
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Baidu
{
    /**
     * 链接提交
     * @param string $site 网站
     * @param string $token Token
     * @param string|array $urls Url列表
     * @return array
     * @throws GuzzleException
     * @throws ConnectionException
     */
    public static function Push(string $site, string $token, $urls)
    {
        if (is_array($urls)) {
            $urls = implode("\n", $urls);
        }
        $response = HttpClient::make()
            ->acceptJson()
            ->postText("http://data.zz.baidu.com/urls?site={$site}&token={$token}", $urls);
        return $response->json();
    }

    /**
     * 链接更新
     * @param string $site 网站
     * @param string $token Token
     * @param string|array $urls Url列表
     * @return array
     */
    public static function Update($site, $token, $urls)
    {
        if (is_array($urls)) {
            $urls = implode("\n", $urls);
        }
        $response = HttpClient::make()
            ->acceptJson()
            ->postText("http://data.zz.baidu.com/update?site={$site}&token={$token}", $urls);
        return $response->json();
    }

    /**
     * 链接删除
     * @param string $site 网站
     * @param string $token Token
     * @param string|array $urls Url列表
     * @return array
     * @throws ConnectionException
     * @throws GuzzleException
     */
    public static function Delete($site, $token, $urls)
    {
        if (is_array($urls)) {
            $urls = implode("\n", $urls);
        }
        $response = HttpClient::make()
            ->acceptJson()
            ->postText("http://data.zz.baidu.com/del?site={$site}&token={$token}", $urls);
        return $response->json();
    }

    /**
     * 快速收录
     * @param string $site 网站
     * @param string $token Token
     * @param string|array $urls Url列表
     * @return array|mixed
     * @throws ConnectionException
     * @throws GuzzleException
     */
    public static function DailyPush($site, $token, $urls)
    {
        if (is_array($urls)) {
            $urls = implode("\n", $urls);
        }
        $response = HttpClient::make()
            ->acceptJson()
            ->postText("http://data.zz.baidu.com/urls?site={$site}&token={$token}&type=daily", $urls);
        return $response->json();
    }

    /**
     * 蜘蛛模拟
     * @param string $url
     * @return string|false
     * @throws ConnectionException
     * @throws GuzzleException
     */
    public static function SpiderPC(string $url)
    {
        if (strpos($url, "://") == false) {
            $url = "http://" . $url;
        }
        $response = HttpClient::make()
            ->withUserAgent('Mozilla/5.0 (compatible; Baiduspider/2.0;+http://www.baidu.com/search/spider.html）')
            ->get($url);
        if ($response->ok()) {
            return $response->body();
        }
        return false;
    }

    /**
     * 蜘蛛模拟
     * @param string $url
     * @return string|false
     */
    public static function SpiderMobile(string $url)
    {
        if (strpos($url, "://") == false) {
            $url = "http://" . $url;
        }
        $response = HttpClient::make()
            ->withUserAgent('Mozilla/5.0 (Linux;u;Android 4.2.2;zh-cn;) AppleWebKit/534.46 (KHTML,likeGecko) Version/5.1 Mobile Safari/10600.6.3 (compatible; Baiduspider/2.0;+http://www.baidu.com/search/spider.html)')
            ->get($url);
        if ($response->ok()) {
            return $response->body();
        }
        return false;
    }

    /**
     * 检查是否收录页面
     * @param string $url
     * @return bool
     * @throws ConnectionException
     * @throws GuzzleException
     */
    public static function checkInclude(string $url): bool
    {
        $response = HttpClient::make()->get("https://www.baidu.com/s?wd={$url}");
        if (!strpos($response->body(), '提交网址')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取推荐搜索
     * @param string $word
     * @return array|false
     */
    public static function suggestion(string $word)
    {
        $response = HttpClient::make()
            ->withUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36')
            ->get("http://suggestion.baidu.com/su", [
                'wd' => $word,
                //'p' => '1',
                'cb' => 'window.bdsug.sug'
            ]);
        if ($response->ok()) {
            $content = str_replace(['window.bdsug.sug(', ');'], '', mb_convert_encoding($response->body(), "UTF-8", "GB2312"));
            $arr = static::ext_json_decode($content, true);
            return $arr['s'];
        } else {
            return false;
        }
    }

    /**
     * 兼容key没有双引括起来的JSON字符串解析
     * @param string $json JSON字符串
     * @param boolean $assoc true:Array,false:Object
     * @return array/object
     */
    private static function ext_json_decode(string $json, $assoc = true)
    {
        if (preg_match('/\w:/', $json)) {
            $json = preg_replace('/(\w+):/is', '"$1":', $json);
        }
        return json_decode($json, $assoc);
    }
}
