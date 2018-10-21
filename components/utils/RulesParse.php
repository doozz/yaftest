<?php

namespace Components\Utils;


class RulesParse
{
    protected $rules;
    public $reqErr = false;
    public $methodErr = false;
    public $resFlag = true;   //　校验结果标识，true通过　false禁止
    public $warnMsg = false;    // 返回错误集，包含code和msg
    public $warnMsgCode = false; // 返回错误集，包含code
    public $warnMsgMsg = false;  // 返回错误集，包含msg

    public $_sanReq = array();

    /**
     * @name __construct
     *
     * @param $var
     * @param $rule
     *
     * @returns
     */
    public function __construct($rule)
    {
        $this->rules = $rule;
    }

    /**
     * @brief parseMethod 解析action里面的方法
     *
     * @Returns
     */
    public function parseMethod($request)
    {
        $methods = array();
        $datas = array();

        if (strcasecmp($request->getMethod(), $this->rules['_method']) !== 0)
        {
            return 'ERROR METHOD';   
        }


        if($this->rules['_method'] == 'get')
        {
            $datas = $request->getParams();
        }
        
        if($this->rules['_method'] == 'post')
        {
            $datas = $request->getPost();
        }

        foreach($this->rules['_params'] as $param)
        {
            if(!isset($this->rules[$param]))
            {
                continue;
            }
        
            // 非必填且参数无值  无需验证
            if (!$this->rules[$param]['required'] && empty($datas[$param]))
            {
                continue;
            }
          
            $paramKeys = array_keys($this->rules[$param]);

            foreach($paramKeys as $key)
            {
                $ruleValue = isset($this->rules[$param][$key]) ? $this->rules[$param][$key] : '';
                $ruleMsg = isset($this->rules[$param]['msg']) ? $this->rules[$param]['msg'] : '';
               
                $result = $this->_rule($key, $ruleValue, $ruleMsg, $param , $datas);
                if (!$result) {
                    return $ruleMsg; 
                }
                //数据过滤开始
                if($key == 'filters')
                {   
                    if(is_array($datas[$param]))
                    {
                        foreach ($datas[$param] as $key => $value) {
                            $datas[$param][$key] = $ruleValue(addslashes($value));
                        }
                    }else {
                        $datas[$param] =  $ruleValue(addslashes($datas[$param]));
                    }
                }
                
            }
        }
    }

    protected function _rule($key, $ruleValue, $ruleMsg, $param, $datas)
    {
        $result = true;
        switch($key)
        {
            //是否必须
            case 'required':
                if ($ruleValue) {
                    $result = isset($datas[$param]);
                }
                break;
            // 数值最大值
            case 'max':
                $result = $datas[$param] > $ruleValue;
                break;
            // 数值最小值
            case 'min':
                $result = $datas[$param] < $ruleValue;
                break;
            // 数值范围内
            case 'between':
                if(is_array($ruleValue))
                {
                    $max = $ruleValue[1];
                    $min = $ruleValue[0];
                }
                else
                    $min = $max = $$ruleValue;
                $result = $datas[$param] <= $max and $datas[$param] >= $min;
                break;
            // 数值匹配
            case 'range':
               if(is_array($ruleValue))
                {
                    $range = $ruleValue;
                }
                else
                    $range = [$ruleValue];
                $result = in_array($datas[$param], $range);
                break;
            // 匹配另一参数
            case 'equalTo':
                $result = $datas[$param] == $datas[$ruleValue];
            // 最小长度
            // case 'minStr':
            //     $result = $datas[$param] == $datas[$ruleValue];
            //     break;
            // // 最大长度
            // case 'maxStr': 
            //     $result = $datas[$param] == $datas[$ruleValue];
            //     break;
            // 是否是一个有效日期
            case 'date':
                $result = false !== strtotime($datas[$param]);
                break;
            // 只允许字母
            case 'en':
                $result = $this->regex($datas[$param], '/^[A-Za-z]+$/');
                break;
            case 'alphaNum':
                // 只允许字母和数字
                $result = $this->regex($datas[$param], '/^[A-Za-z0-9]+$/');
                break;
            case 'alphaDash':
                // 只允许字母、数字和下划线 破折号
                $result = $this->regex($datas[$param], '/^[A-Za-z0-9\-\_]+$/');
                break;
            case 'zh':
                // 只允许汉字
                $result = $this->regex($datas[$param], '/^[\x{4e00}-\x{9fa5}]+$/u');
                break;
            case 'chsAlpha':
                // 只允许汉字、字母
                $result = $this->regex($datas[$param], '/^[\x{4e00}-\x{9fa5}a-zA-Z]+$/u');
                break;
            case 'chsAlphaNum':
                // 只允许汉字、字母和数字
                $result = $this->regex($datas[$param], '/^[\x{4e00}-\x{9fa5}a-zA-Z0-9]+$/u');
                break;
            case 'chsDash':
                // 只允许汉字、字母、数字和下划线_及破折号-
                $result = $this->regex($datas[$param], '/^[\x{4e00}-\x{9fa5}a-zA-Z0-9\_\-]+$/u');
                break;
            case 'mobile':
                $result = $this->regex($datas[$param], '/^1[345789]\d{9}$/');
                break;
            case 'idNo':
                $result = $this->regex($datas[$param], '/^([\d]{17}[xX\d]|[\d]{15})$/');
                break;
            case 'activeUrl':
                // 是否为有效的网址
                $result = checkdnsrr($datas[$param]);
                break;
            case 'ip':
                // 是否为IP地址
                $result = $this->filter($datas[$param], [FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6]);
                break;
            case 'url':
                // 是否为一个URL地址
                $result = $this->filter($datas[$param], FILTER_VALIDATE_URL);
                break;
            case 'float':
                // 是否为float
                $result = $this->filter($datas[$param], FILTER_VALIDATE_FLOAT);
                break;
            case 'int':
                $result = is_numeric($datas[$param]);
                break;
            case 'integer':
                // 是否为整型
                $result = $this->filter($datas[$param], FILTER_VALIDATE_INT);
                break;
            case 'email':
                // 是否为邮箱地址
                $result = $this->filter($datas[$param], FILTER_VALIDATE_EMAIL);
                break;
            case 'boolean':
                // 是否为布尔值
                $result = in_array($datas[$param], [true, false, 0, 1, '0', '1'], true);
                break;
            case 'array':
                // 是否为数组
                $result = is_array($datas[$param]);
                break;
            case 'file':
                $result = $datas[$param] instanceof File;
                break;
            }
            return  $result;
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
}