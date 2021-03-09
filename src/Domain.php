<?php
/**
 * @copyright Copyright (c) 2018 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 * @license http://www.larva.com.cn/license/
 */

namespace Larva\Site\Tool;

use Pdp\Rules;

/**
 * 域名工具
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Domain
{
    /**
     * 域名后缀解析
     * @param string $domain
     * @return \Pdp\Domain
     * @see https://github.com/jeremykendall/php-domain-parser
     */
    public static function Resolve(string $domain): \Pdp\Domain
    {
        $rules = Rules::createFromPath('../resources/public_suffix_list.dat');
        return $rules->resolve($domain);
    }

    /**
     * 从URL中提取顶级域名
     *
     * @param string $host
     * @return bool|\Pdp\Domain
     */
    public function parseDomain(string $host)
    {
        if (strpos ( $host, '://' ) !== false) {
            $url = parse_url ( $host );
            if (isset ( $url ['host'] )) {
                $host = $url ['host'];
            }
        }
        if ($host && strpos ( $host, '.' ) !== false) {
            return static::Resolve($host);
        }
        return false;
    }

    /**
     * @param $date1
     * @param $date2
     * @return string
     */
    public static function diffDate($date1, $date2)
    {
        $datestart = date('Y-m-d', strtotime($date1));
        if (strtotime($datestart) > strtotime($date2)) {
            $tmp = $date2;
            $date2 = $datestart;
            $datestart = $tmp;
        }
        list ($Y1, $m1, $d1) = explode('-', $datestart);
        list ($Y2, $m2, $d2) = explode('-', $date2);
        $Y = $Y2 - $Y1;
        $m = $m2 - $m1;
        $d = $d2 - $d1;
        if ($d < 0) {
            $d += ( int )date('t', strtotime("-1 month $date2"));
            $m--;
        }
        if ($m < 0) {
            $m += 12;
            $Y--;
        }
        if ($Y == 0) {
            return $m . '个月零' . $d . '天';
        } elseif ($Y == 0 && $m == 0) {
            return $d . '天';
        } else {
            return $Y . '年' . $m . '个月零' . $d . '天';
        }
    }

    /**
     * @param string $domain
     * @return array
     */
    public static function whois($domain)
    {
        $command = '/usr/bin/whois -H {domain}';
        $cmd = strtr($command, [
            '{domain}' => escapeshellarg($domain)
        ]);
        $whois = shell_exec($cmd);
        exec($cmd, $whois);
        $headers = [];
        $rawWhois = '';
        if (is_array($whois)) {
            foreach ($whois as $name => $value) {
                // parse raw header :
                $rawWhois .= $value;
                $rawHeader = $value;
                if (($separatorPos = strpos($rawHeader, ':')) !== false) {
                    $name = strtolower(trim(substr($rawHeader, 0, $separatorPos)));
                    $value = trim(substr($rawHeader, $separatorPos + 1));
                    $headers[][$name] = $value;
                    if ($name == 'dnssec') {
                        break;
                    }
                } elseif (empty($rawHeader)) {
                    break;
                } else {
                    $headers[]['raw'] = $rawHeader;
                }
            }
        }
        return ['whois' => $headers, 'raw' => $rawWhois];
    }

    /**
     * 获取域名Whois服务器
     * @param string $server
     * @param string $domain
     * @return string
     */
    public static function getWhoisByServer($server, $domain)
    {
        $whois = '';
        $ch = fsockopen($server, 43, $errno, $errstr, 3);
        if (!$ch) {
            return $whois;
        } else {
            stream_set_blocking($ch, 0);
            stream_set_timeout($ch, 3);
            fputs($ch, "$domain\r\n");
            while (!feof($ch)) {
                $whois .= fread($ch, 128);
            }
        }
        return $whois;
    }
}