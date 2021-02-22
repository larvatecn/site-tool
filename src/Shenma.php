<?php
/**
 * @copyright Copyright (c) 2018 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 * @license http://www.larva.com.cn/license/
 */

namespace Larva\Site\Tool;

use Larva\Support\HttpClient;

/**
 * 神马站长工具
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Shenma
{
    /**
     * 神马 MIP 推送
     * @param string $site
     * @param string $username
     * @param string $token
     * @param string|array $urls
     * @return mixed
     */
    public static function MIPPush($site, $username, $token, $urls)
    {
        if (is_array($urls)) {
            $urls = implode("\n", $urls);
        }
        $client = new HttpClient();
        return $client->post( "https://data.zhanzhang.sm.cn/urls?site={$site}&username={$username}&resource_name=mip_add&token={$token}", [
            'body' => $urls
        ]);
    }

    /**
     * AMP MIP 清理
     * @param string $site
     * @param string $username
     * @param string $token Token
     * @param string|array $urls
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Larva\Support\Exception\ConnectionException
     */
    public static function AMPClean($site, $username, $token, $urls)
    {
        if (is_array($urls)) {
            $urls = implode("\n", $urls);
        }
        return HttpClient::make()->post('post', "https://data.zhanzhang.sm.cn/urls?site={$site}&username={$username}&resource_name=mip_clean&token={$token}",$urls)->json();
    }
}
