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
 * Class Douyin
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Douyin
{
    /**
     * 获取抖音短视频信息
     * @param string $url 抖音分享Url
     * @return array
     */
    public static function info($url)
    {
        $info = [];
        $res = static::getDouyinUrl($url);
        preg_match('/href="(.*?)">Found/', $res, $matches);
        preg_match('/itemId: "(.*?)",/', static::getDouyinUrl(str_replace('&', '&', $matches[1])), $matches);
        $arr = json_decode(static::getDouyinUrl('https://www.iesdouyin.com/web/api/v2/aweme/iteminfo/?item_ids=' . $matches[1]), true);
        $info['desc'] = $arr['item_list'][0]['desc'];
        $info['share_url'] = $arr['item_list'][0]['share_url'];
        $info['author'] = [
            'uid' => $arr['item_list'][0]['author']['uid'],
            'nickname' => $arr['item_list'][0]['author']['nickname'],
            'signature' => $arr['item_list'][0]['author']['signature'],
            'avatar_larger' => $arr['item_list'][0]['author']['avatar_larger']['url_list'][0],
            'avatar_medium' => $arr['item_list'][0]['author']['avatar_medium']['url_list'][0],
            'avatar_thumb' => $arr['item_list'][0]['author']['avatar_thumb']['url_list'][0],
        ];
        $info['music'] = [
            'id' => $arr['item_list'][0]['music']['id'],
            'mid' => $arr['item_list'][0]['music']['mid'],
            'title' => $arr['item_list'][0]['music']['title'],
            'cover_hd' => $arr['item_list'][0]['music']['cover_hd']['url_list'],
            'cover_medium' => $arr['item_list'][0]['music']['cover_medium']['url_list'],
            'cover_large' => $arr['item_list'][0]['music']['cover_large']['url_list'],
            'cover_thumb' => $arr['item_list'][0]['music']['cover_thumb']['url_list'],
            'play_url' => $arr['item_list'][0]['music']['play_url']['url_list'],
        ];
        $info['video'] = [
            'vid' => $arr['item_list'][0]['video']['vid'],
            'duration' => $arr['item_list'][0]['video']['duration'],
            'ratio' => $arr['item_list'][0]['video']['ratio'],
            'width' => $arr['item_list'][0]['video']['width'],
            'height' => $arr['item_list'][0]['video']['height'],
            'cover' => $arr['item_list'][0]['video']['cover']['url_list'],
            'origin_cover' => $arr['item_list'][0]['video']['origin_cover']['url_list'],
            'dynamic_cover' => $arr['item_list'][0]['video']['dynamic_cover']['url_list'],
            'watermark_play_addr' => $arr['item_list'][0]['video']['play_addr']['url_list'],
        ];
        preg_match('/href="(.*?)">Found/', static::getDouyinUrl(str_replace('playwm', 'play', $info['video']['watermark_play_addr'][0])), $matches);
        $videoUrl = str_replace('&amp;', '&', $matches[1]);
        $info['video']['play_addr'][] = $videoUrl;
        return $info;
    }

    /**
     * 获取抖音Url内容
     * @param string $url
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected static function getDouyinUrl($url)
    {
        $response = HttpClient::make()
            ->withUserAgent('Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/604.1.38 (KHTML, like Gecko) Version/11.0 Mobile/15A372 Safari/604.1')
            ->get($url);
        return $response->body();
    }
}
