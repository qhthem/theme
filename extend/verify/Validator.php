<?php
// +----------------------------------------------------------------------
// | QHPHP [ 代码创造未来，思维改变世界。 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 https://www.astrocms.cn/ All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: ZHAOSONG <1716892803@qq.com>
// +----------------------------------------------------------------------
namespace extend\verify;
/**
 * 验证类
 *
 * @author zhaosong
 */
class Validator
{
    /**
     * 验证是否为数字
     *
     * @param mixed $value 要验证的值
     * @return bool 是否为数字
     */
    public static function isNumeric($value)
    {
        return is_numeric($value);
    }

    /**
     * 验证是否为电子邮件地址
     *
     * @param string $value 要验证的值
     * @return bool 是否为电子邮件地址
     */
    public static function isEmail($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * 验证是否为有效日期
     *
     * @param string $value 要验证的值
     * @return bool 是否为有效日期
     */
    public static function isValidDate($value)
    {
        $date = strtotime($value);
        return $date !== false && date('Y-m-d', $date) === $value;
    }

    /**
     * 验证是否为字母和数字组成的字符串
     *
     * @param string $value 要验证的值
     * @return bool 是否为字母和数字组成的字符串
     */
    public static function isAlphanumeric($value)
    {
        return preg_match('/^[a-zA-Z0-9]+$/', $value) === 1;
    }

    /**
     * 验证是否为字母组成的字符串
     *
     * @param string $value 要验证的值
     * @return bool 是否为字母组成的字符串
     */
    public static function isAlpha($value)
    {
        return preg_match('/^[a-zA-Z]+$/', $value) === 1;
    }

    /**
     * 验证是否为中文组成的字符串
     *
     * @param string $value 要验证的值
     * @return bool 是否为中文组成的字符串
     */
    public static function isChinese($value)
    {
        return preg_match('/^[\x{4e00}-\x{9fa5}]+$/u', $value) === 1;
    }

    /**
     * 验证是否为小写字母组成的字符串
     *
     * @param string $value 要验证的值
     * @return bool 是否为小写字母组成的字符串
     */
    public static function isLowercase($value)
    {
        return preg_match('/^[a-z]+$/', $value) === 1;
    }

    /**
     * 验证是否为有效的域名或 IP 地址
     *
     * @param string $value 要验证的值
     * @return bool 是否为有效的域名或 IP 地址
     */
    public static function isValidDomainOrIP($value)
    {
        return filter_var($value, FILTER_VALIDATE_DOMAIN) !== false || filter_var($value, FILTER_VALIDATE_IP) !== false;
    }

    /**
     * 验证是否为有效的 IP 地址
     *
     * @param string $value 要验证的值
     * @return bool 是否为有效的 IP 地址
     */
    public static function isValidIP($value)
    {
        return filter_var($value, FILTER_VALIDATE_IP) !== false;
    }

    /**
     * 验证是否为有效的手机号码
     *
     * @param string $value 要验证的值
     * @return bool 是否为有效的手机号码
     */
    public static function isValidPhone($value)
    {
        return preg_match('/^1[3-9]\d{9}$/', $value) === 1;
    }

    /**
     * 验证是否在指定范围内
     *
     * @param mixed $value 要验证的值
     * @param mixed $min 最小值
     * @param mixed $max 最大值
     * @return bool 是否在指定范围内
     */
    public static function isInRange($value, $min, $max)
    {
        return $value >= $min && $value <= $max;
    }

    /**
     * 验证是否不在指定范围内
     *
     * @param mixed $value 要验证的值
     * @param mixed $min 最小值
     * @param mixed $max 最大值
     * @return bool 是否不在指定范围内
     */
    public static function isNotInRange($value, $min, $max)
    {
        return $value < $min || $value > $max;
    }

    /**
     * 验证是否为有效的 URL
     *
     * @param string $value 要验证的值
     * @return bool 是否为有效的 URL
     */
    public static function isValidURL($value)
    {
        return filter_var($value, FILTER_VALIDATE_URL) !== false;
    }
}