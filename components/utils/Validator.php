<?php
namespace Components\Utils;

class Validate
{
    // 实例
    protected static $instance = NULL;
 
    /**
     * 获取单例
     */
    public static function getInstance($key = 'default')
	{
		if(isset(self::$instance[$key]) && self::$instance[$key] !== null)
		{
			return self::$instance[$key];
		}

		new Validate();
		return self::$instance[$key];
	}
    
    /**
     * 验证是否和某个字段的值一致
     * @access protected
     * @param mixed     $value 字段值
     * @param mixed     $rule  验证规则
     * @param array     $data  数据
     * @param string    $field 字段名
     * @return bool
     */
    protected function confirm($value, $rule, $data, $field = '')
    {
        if ('' == $rule) {
            if (strpos($field, '_confirm')) {
                $rule = strstr($field, '_confirm', true);
            } else {
                $rule = $field . '_confirm';
            }
        }
        return $this->getDataValue($data, $rule) === $value;
    }
    /**
     * 验证是否和某个字段的值是否不同
     * @access protected
     * @param mixed $value 字段值
     * @param mixed $rule  验证规则
     * @param array $data  数据
     * @return bool
     */
    protected function different($value, $rule, $data)
    {
        return $this->getDataValue($data, $rule) != $value;
    }
    /**
     * 验证是否大于等于某个值
     * @access protected
     * @param mixed     $value  字段值
     * @param mixed     $rule  验证规则
     * @return bool
     */
    protected function egt($value, $rule)
    {
        return $value >= $rule;
    }
    /**
     * 验证是否大于某个值
     * @access protected
     * @param mixed     $value  字段值
     * @param mixed     $rule  验证规则
     * @return bool
     */
    protected function gt($value, $rule)
    {
        return $value > $rule;
    }
    /**
     * 验证是否小于等于某个值
     * @access protected
     * @param mixed     $value  字段值
     * @param mixed     $rule  验证规则
     * @return bool
     */
    protected function elt($value, $rule)
    {
        return $value <= $rule;
    }
    /**
     * 验证是否小于某个值
     * @access protected
     * @param mixed     $value  字段值
     * @param mixed     $rule  验证规则
     * @return bool
     */
    protected function lt($value, $rule)
    {
        return $value < $rule;
    }
    /**
     * 验证是否等于某个值
     * @access protected
     * @param mixed     $value  字段值
     * @param mixed     $rule  验证规则
     * @return bool
     */
    protected function eq($value, $rule)
    {
        return $value == $rule;
    }
    /**
     * 验证字段值是否为有效格式
     * @access protected
     * @param mixed     $value  字段值
     * @param string    $rule  验证规则
     * @param array     $data  验证数据
     * @return bool
     */
    protected function __construct($value, $rule, $data = [])
    {
        switch ($rule) {
            case 'require':
                // 必须
                $result = !empty($value) || '0' == $value;
                break;
            case 'date':
                // 是否是一个有效日期
                $result = false !== strtotime($value);
                break;
            case 'alpha':
                // 只允许字母
                $result = $this->regex($value, '/^[A-Za-z]+$/');
                break;
            case 'alphaNum':
                // 只允许字母和数字
                $result = $this->regex($value, '/^[A-Za-z0-9]+$/');
                break;
            case 'alphaDash':
                // 只允许字母、数字和下划线 破折号
                $result = $this->regex($value, '/^[A-Za-z0-9\-\_]+$/');
                break;
            case 'chs':
                // 只允许汉字
                $result = $this->regex($value, '/^[\x{4e00}-\x{9fa5}]+$/u');
                break;
            case 'chsAlpha':
                // 只允许汉字、字母
                $result = $this->regex($value, '/^[\x{4e00}-\x{9fa5}a-zA-Z]+$/u');
                break;
            case 'chsAlphaNum':
                // 只允许汉字、字母和数字
                $result = $this->regex($value, '/^[\x{4e00}-\x{9fa5}a-zA-Z0-9]+$/u');
                break;
            case 'chsDash':
                // 只允许汉字、字母、数字和下划线_及破折号-
                $result = $this->regex($value, '/^[\x{4e00}-\x{9fa5}a-zA-Z0-9\_\-]+$/u');
                break;
            case 'mobile':
                $result = $this->regex($value, '/^1[345789]\d{9}$/');
                break;
            case 'idNo':
                $result = $this->regex($value, '/^([\d]{17}[xX\d]|[\d]{15})$/');
                break;
            case 'activeUrl':
                // 是否为有效的网址
                $result = checkdnsrr($value);
                break;
            case 'ip':
                // 是否为IP地址
                $result = $this->filter($value, [FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6]);
                break;
            case 'url':
                // 是否为一个URL地址
                $result = $this->filter($value, FILTER_VALIDATE_URL);
                break;
            case 'float':
                // 是否为float
                $result = $this->filter($value, FILTER_VALIDATE_FLOAT);
                break;
            case 'number':
                $result = is_numeric($value);
                break;
            case 'integer':
                // 是否为整型
                $result = $this->filter($value, FILTER_VALIDATE_INT);
                break;
            case 'email':
                // 是否为邮箱地址
                $result = $this->filter($value, FILTER_VALIDATE_EMAIL);
                break;
            case 'boolean':
                // 是否为布尔值
                $result = in_array($value, [true, false, 0, 1, '0', '1'], true);
                break;
            case 'array':
                // 是否为数组
                $result = is_array($value);
                break;
            case 'file':
                $result = $value instanceof File;
                break;
            case 'image':
                $result = $value instanceof File && in_array($this->getImageType($value->getRealPath()), [1, 2, 3, 6]);
                break;
            default:
                if (isset(self::$type[$rule])) {
                    // 注册的验证规则
                    $result = call_user_func_array(self::$type[$rule], [$value]);
                } else {
                    // 正则验证
                    $result = $this->regex($value, $rule);
                }
        }
        return $result;
    }
    // 判断图像类型
    protected function getImageType($image)
    {
        if (function_exists('exif_imagetype')) {
            return exif_imagetype($image);
        } else {
            $info = getimagesize($image);
            return $info[2];
        }
    }
    /**
     * 验证是否为合格的域名或者IP 支持A，MX，NS，SOA，PTR，CNAME，AAAA，A6， SRV，NAPTR，TXT 或者 ANY类型
     * @access protected
     * @param mixed     $value  字段值
     * @param mixed     $rule  验证规则
     * @return bool
     */
    protected function activeUrl($value, $rule)
    {
        if (!in_array($rule, ['A', 'MX', 'NS', 'SOA', 'PTR', 'CNAME', 'AAAA', 'A6', 'SRV', 'NAPTR', 'TXT', 'ANY'])) {
            $rule = 'MX';
        }
        return checkdnsrr($value, $rule);
    }
    /**
     * 验证是否有效IP
     * @access protected
     * @param mixed     $value  字段值
     * @param mixed     $rule  验证规则 ipv4 ipv6
     * @return bool
     */
    protected function ip($value, $rule)
    {
        if (!in_array($rule, ['ipv4', 'ipv6'])) {
            $rule = 'ipv4';
        }
        return $this->filter($value, [FILTER_VALIDATE_IP, 'ipv6' == $rule ? FILTER_FLAG_IPV6 : FILTER_FLAG_IPV4]);
    }
    /**
     * 验证上传文件后缀
     * @access protected
     * @param mixed     $file  上传文件
     * @param mixed     $rule  验证规则
     * @return bool
     */
    protected function fileExt($file, $rule)
    {
        if (!($file instanceof File)) {
            return false;
        }
        if (is_string($rule)) {
            $rule = explode(',', $rule);
        }
        if (is_array($file)) {
            foreach ($file as $item) {
                if (!$item->checkExt($rule)) {
                    return false;
                }
            }
            return true;
        } else {
            return $file->checkExt($rule);
        }
    }
    /**
     * 验证上传文件类型
     * @access protected
     * @param mixed     $file  上传文件
     * @param mixed     $rule  验证规则
     * @return bool
     */
    protected function fileMime($file, $rule)
    {
        if (!($file instanceof File)) {
            return false;
        }
        if (is_string($rule)) {
            $rule = explode(',', $rule);
        }
        if (is_array($file)) {
            foreach ($file as $item) {
                if (!$item->checkMime($rule)) {
                    return false;
                }
            }
            return true;
        } else {
            return $file->checkMime($rule);
        }
    }
    /**
     * 验证上传文件大小
     * @access protected
     * @param mixed     $file  上传文件
     * @param mixed     $rule  验证规则
     * @return bool
     */
    protected function fileSize($file, $rule)
    {
        if (!($file instanceof File)) {
            return false;
        }
        if (is_array($file)) {
            foreach ($file as $item) {
                if (!$item->checkSize($rule)) {
                    return false;
                }
            }
            return true;
        } else {
            return $file->checkSize($rule);
        }
    }
    /**
     * 验证图片的宽高及类型
     * @access protected
     * @param mixed     $file  上传文件
     * @param mixed     $rule  验证规则
     * @return bool
     */
    protected function image($file, $rule)
    {
        if (!($file instanceof File)) {
            return false;
        }
        if ($rule) {
            $rule                        = explode(',', $rule);
            list($width, $height, $type) = getimagesize($file->getRealPath());
            if (isset($rule[2])) {
                $imageType = strtolower($rule[2]);
                if ('jpeg' == $imageType) {
                    $imageType = 'jpg';
                }
                if (image_type_to_extension($type, false) != $imageType) {
                    return false;
                }
            }
            list($w, $h) = $rule;
            return $w == $width && $h == $height;
        } else {
            return in_array($this->getImageType($file->getRealPath()), [1, 2, 3, 6]);
        }
    }
    /**
     * 验证时间和日期是否符合指定格式
     * @access protected
     * @param mixed     $value  字段值
     * @param mixed     $rule  验证规则
     * @return bool
     */
    protected function dateFormat($value, $rule)
    {
        $info = date_parse_from_format($rule, $value);
        return 0 == $info['warning_count'] && 0 == $info['error_count'];
    }
    /**
     * 使用filter_var方式验证
     * @access protected
     * @param mixed     $value  字段值
     * @param mixed     $rule  验证规则
     * @return bool
     */
    protected function filter($value, $rule)
    {
        if (is_string($rule) && strpos($rule, ',')) {
            list($rule, $param) = explode(',', $rule);
        } elseif (is_array($rule)) {
            $param = isset($rule[1]) ? $rule[1] : null;
            $rule  = $rule[0];
        } else {
            $param = null;
        }
        return false !== filter_var($value, is_int($rule) ? $rule : filter_id($rule), $param);
    }
    /**
     * 验证某个字段等于某个值的时候必须
     * @access protected
     * @param mixed     $value  字段值
     * @param mixed     $rule  验证规则
     * @param array     $data  数据
     * @return bool
     */
    protected function requireIf($value, $rule, $data)
    {
        list($field, $val) = explode(',', $rule);
        if ($this->getDataValue($data, $field) == $val) {
            return !empty($value);
        } else {
            return true;
        }
    }
 
    /**
     * 验证某个字段有值的情况下必须
     * @access protected
     * @param mixed     $value  字段值
     * @param mixed     $rule  验证规则
     * @param array     $data  数据
     * @return bool
     */
    protected function requireWith($value, $rule, $data)
    {
        $val = $this->getDataValue($data, $rule);
        if (!empty($val)) {
            return !empty($value);
        } else {
            return true;
        }
    }
    /**
     * 验证是否在范围内
     * @access protected
     * @param mixed     $value  字段值
     * @param mixed     $rule  验证规则
     * @return bool
     */
    protected function in($value, $rule)
    {
        return in_array($value, is_array($rule) ? $rule : explode(',', $rule));
    }
    /**
     * 验证是否不在某个范围
     * @access protected
     * @param mixed     $value  字段值
     * @param mixed     $rule  验证规则
     * @return bool
     */
    protected function notIn($value, $rule)
    {
        return !in_array($value, is_array($rule) ? $rule : explode(',', $rule));
    }
    /**
     * between验证数据
     * @access protected
     * @param mixed     $value  字段值
     * @param mixed     $rule  验证规则
     * @return bool
     */
    protected function between($value, $rule)
    {
        if (is_string($rule)) {
            $rule = explode(',', $rule);
        }
        list($min, $max) = $rule;
        return $value >= $min && $value <= $max;
    }
    /**
     * 使用notbetween验证数据
     * @access protected
     * @param mixed     $value  字段值
     * @param mixed     $rule  验证规则
     * @return bool
     */
    protected function notBetween($value, $rule)
    {
        if (is_string($rule)) {
            $rule = explode(',', $rule);
        }
        list($min, $max) = $rule;
        return $value < $min || $value > $max;
    }
    /**
     * 验证数据长度
     * @access protected
     * @param mixed     $value  字段值
     * @param mixed     $rule  验证规则
     * @return bool
     */
    protected function length($value, $rule)
    {
        if (is_array($value)) {
            $length = count($value);
        } elseif ($value instanceof File) {
            $length = $value->getSize();
        } else {
            $length = mb_strlen((string) $value);
        }
        if (strpos($rule, ',')) {
            // 长度区间
            list($min, $max) = explode(',', $rule);
            return $length >= $min && $length <= $max;
        } else {
            // 指定长度
            return $length == $rule;
        }
    }
    /**
     * 验证数据最大长度
     * @access protected
     * @param mixed     $value  字段值
     * @param mixed     $rule  验证规则
     * @return bool
     */
    protected function max($value, $rule)
    {
        if (is_array($value)) {
            $length = count($value);
        } elseif ($value instanceof File) {
            $length = $value->getSize();
        } else {
            $length = mb_strlen((string) $value);
        }
        return $length <= $rule;
    }
    /**
     * 验证数据最小长度
     * @access protected
     * @param mixed     $value  字段值
     * @param mixed     $rule  验证规则
     * @return bool
     */
    protected function min($value, $rule)
    {
        if (is_array($value)) {
            $length = count($value);
        } elseif ($value instanceof File) {
            $length = $value->getSize();
        } else {
            $length = mb_strlen((string) $value);
        }
        return $length >= $rule;
    }
    /**
     * 验证日期
     * @access protected
     * @param mixed     $value  字段值
     * @param mixed     $rule  验证规则
     * @return bool
     */
    protected function after($value, $rule)
    {
        return strtotime($value) >= strtotime($rule);
    }
    /**
     * 验证日期
     * @access protected
     * @param mixed     $value  字段值
     * @param mixed     $rule  验证规则
     * @return bool
     */
    protected function before($value, $rule)
    {
        return strtotime($value) <= strtotime($rule);
    }
    /**
     * 验证有效期
     * @access protected
     * @param mixed     $value  字段值
     * @param mixed     $rule  验证规则
     * @return bool
     */
    protected function expire($value, $rule)
    {
        if (is_string($rule)) {
            $rule = explode(',', $rule);
        }
        list($start, $end) = $rule;
        if (!is_numeric($start)) {
            $start = strtotime($start);
        }
        if (!is_numeric($end)) {
            $end = strtotime($end);
        }
        return $_SERVER['REQUEST_TIME'] >= $start && $_SERVER['REQUEST_TIME'] <= $end;
    }
    /**
     * 验证IP许可
     * @access protected
     * @param string    $value  字段值
     * @param mixed     $rule  验证规则
     * @return mixed
     */
    protected function allowIp($value, $rule)
    {
        return in_array($_SERVER['REMOTE_ADDR'], is_array($rule) ? $rule : explode(',', $rule));
    }
    /**
     * 验证IP禁用
     * @access protected
     * @param string    $value  字段值
     * @param mixed     $rule  验证规则
     * @return mixed
     */
    protected function denyIp($value, $rule)
    {
        return !in_array($_SERVER['REMOTE_ADDR'], is_array($rule) ? $rule : explode(',', $rule));
    }
    /**
     * 使用正则验证数据
     * @access protected
     * @param mixed     $value  字段值
     * @param mixed     $rule  验证规则 正则规则或者预定义正则名
     * @return mixed
     */
    protected function regex($value, $rule)
    {
        if (isset($this->regex[$rule])) {
            $rule = $this->regex[$rule];
        }
        if (0 !== strpos($rule, '/') && !preg_match('/\/[imsU]{0,4}$/', $rule)) {
            // 不是正则表达式则两端补上/
            $rule = '/^' . $rule . '$/';
        }
        return 1 === preg_match($rule, (string) $value);
    }
    // 获取错误信息
    public function getError()
    {
        return $this->error;
    }
}
