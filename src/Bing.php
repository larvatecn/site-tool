<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 * @license http://www.larva.com.cn/license/
 */

namespace Larva\Site\Tool;

use Larva\Support\HttpClient;

/**
 * Class Bing
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Bing
{
    /**
     * 链接提交
     * @param string $site 网站
     * @param string $token Token
     * @param string|array $urls Url列表
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Larva\Support\Exception\ConnectionException
     */
    public static function Push($site, $token, $urls)
    {
        if (is_array($urls)) {
            return HttpClient::make()
                ->postJSON("https://ssl.bing.com/webmaster/api.svc/json/SubmitUrlbatch?apikey={$token}", ['siteUrl' => $site, 'urlList' => $urls]);
        } else {
            return HttpClient::make()
                ->postJSON("https://ssl.bing.com/webmaster/api.svc/json/SubmitUrl?apikey={$token}", ['siteUrl' => $site, 'url' => $urls]);
        }
    }

    /**
     * 获取剩余配额
     * @param string $site
     * @param string $token
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Larva\Support\Exception\ConnectionException
     */
    public static function GetUrlSubmissionQuota(string $site, string $token)
    {
        return HttpClient::make()
            ->getJSON("https://ssl.bing.com/webmaster/api.svc/json/GetUrlSubmissionQuota", ['siteUrl' => $site, 'apikey' => $token]);
    }

    /**
     * 获取推荐搜索
     * @param string $word
     * @return array|false
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Larva\Support\Exception\ConnectionException
     */
    public static function suggestion(string $word)
    {
        $response = HttpClient::make()
            ->withUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36')
            ->get("https://sg1.api.bing.com/qsonhs.aspx", [
                'q' => $word,
                'type' => 'json',
            ]);
        if ($response->ok()) {
            $ret = [];
            $arr = json_decode($response->body(), true);
            if (isset($arr['AS']['Results'][0]['Suggests'])) {
                foreach ($arr['AS']['Results'][0]['Suggests'] as $result) {
                    $ret[] = $result['Txt'];
                }
            }
            return $ret;
        } else {
            return false;
        }
    }
}
