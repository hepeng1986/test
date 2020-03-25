<?php

/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2020/3/18
 * Time: 10:33
 */
class Validation
{
    //开始解析
    public static function validate(&$params, $validations, $isContinue = false)
    {
        $sMsg = "";
        //确保本函数不抛异常
        try {
            if (!is_array($params) || !is_array($validations)) {
                throw new Exception("校验参数必须是数组");
            }
            self::_validate($params, $validations, $isContinue);
        } catch (Exception $e) {
            $sMsg = $e->getMessage();
        }
        return $sMsg;
    }

    //真正校验函数
    private static function _validate(&$params, $validations, $isContinue = false)
    {
        //遍历
        $sMsg = "";
        foreach ($validations as $field => $validator) {
            try {
                self::_validateItem($params, $field, $validator);
            } catch (Exception $e) {
                if ($isContinue) {
                    $sMsg .= $e->getMessage() . "；";
                } else {
                    throw new Exception($e->getMessage());
                }
            }
        }
        return $sMsg;
    }
    //校验一行
    private static function _validateItem($params, $field, $validator){
        $aRule = self::getRule($validator);
        $sCustomMsg = isset($aRule[">>>"])?$aRule[">>>"]:"";
        $sCustomAlias = isset($aRule["Alias"])?$aRule["Alias"]:"";
        foreach ($aRule as $ruleKey=>$ruleValue){
            if("Alias" == $ruleKey && ">>>" == $ruleKey){
                continue;
            }
            if(!self::checkRuleExist($ruleKey)){
                throw new Exception("验证规则:【{$ruleKey}】不存在");
            }
            //校验规则正确性
            $paramList = [];
            $sErrorMsg = self::checkRuleValid($ruleKey,$ruleValue,$paramList);
            if(!$sErrorMsg){
                throw new Exception($sErrorMsg);
            }
            if("Required" == $ruleKey){
                if(!isset($params[$field]) || "" === $params[$field]){
                    self::throwErrorMessage($ruleKey,$ruleValue,$sCustomAlias,$sCustomMsg,$paramList);
                }
            }elseif("Default" == $ruleKey){

            }else{
                $sMethod = "validate".$ruleKey;
                if(!method_exists(self::class,$sMethod)){
                    throw new Exception("验证方法:【{$sMethod}】不存在");
                }
                $bRet = call_user_func_array([self::class, $sMethod], $paramList);
                if(!$bRet){
                    self::throwErrorMessage($ruleKey,$sCustomAlias,$sCustomMsg);
                }
            }
        }
    }
    //获取校验错误信息
    private static function throwErrorMessage($ruleKey,$ruleValue,$sCustomAlias,$sCustomMsg,$paramList)
    {
        $sLastMessage = "";

        $sMessage = self::$errorTemplates[$ruleKey];
        throw new Exception($sLastMessage);
    }
    //获取校验规则
    private static function getRule($validator)
    {
        $aRet = [];
        $aConf = explode("|", $validator);
        foreach ($aConf as $k=>$item){
            $aTemp = explode(":",$item,3);
            $sRuleKey = trim($aTemp[0]);
            $sRuleValue = isset($aTemp[1])?trim($aTemp[1]):"";
            $sRuleMsg = isset($aTemp[2])?trim($aTemp[2]):"";
            $aRet[$sRuleKey] = [
                "value"=>$sRuleValue,
                "message"=>$sRuleMsg
            ];
        }
        return $aRet;
    }
    //判断规则是否存在
    private static function checkRuleExist($sKey){
        //如果不为空且在方法名中
        if($sKey && isset(self::$errorTemplates[$sKey])){
            return true;
        }
        return false;
    }
    /**********************************Int*********************************************/
    //判断是否是整数
    public static function validateInt($value)
    {
        if (is_int($value)) {
            return true;
        }
        if (is_string($value) && preg_match('/^(-?[1-9][0-9]*|0)$/', $value)) {
            return true;
        }
        return false;
    }

    //判断是否等于整数
    public static function validateIntEq($value, $equalVal)
    {
        if (!self::validateInt($value)) {
            return false;
        }
        if ($value == $equalVal) {
            return true;
        }
        return false;
    }

    //判断是否不等于整数
    public static function validateIntNe($value, $equalVal)
    {
        if (!self::validateInt($value)) {
            return false;
        }
        if ($value != $equalVal) {
            return true;
        }
        return false;
    }

    //大于
    public static function validateIntGt($value, $min)
    {
        if (!self::validateInt($value)) {
            return false;
        }
        if ($value > $min) {
            return true;
        }
        return false;
    }

    //大于等于
    public static function validateIntGe($value, $min)
    {
        if (!self::validateInt($value)) {
            return false;
        }
        if ($value >= $min) {
            return true;
        }
        return false;
    }

    //小于
    public static function validateIntLt($value, $max)
    {
        if (!self::validateInt($value)) {
            return false;
        }
        if ($value < $max) {
            return true;
        }
        return false;
    }

    //小于等于
    public static function validateIntLe($value, $max)
    {
        if (!self::validateInt($value)) {
            return false;
        }
        if ($value <= $max) {
            return true;
        }
        return false;
    }

    //大于小于
    public static function validateIntGtLt($value, $min, $max)
    {
        if (!self::validateInt($value)) {
            return false;
        }
        if ($value < $max && $value > $min) {
            return true;
        }
        return false;
    }

    //大于等于小于
    public static function validateIntGeLt($value, $min, $max)
    {
        if (!self::validateInt($value)) {
            return false;
        }
        if ($value < $max && $value >= $min) {
            return true;
        }
        return false;
    }

    //大于小于等于
    public static function validateIntGtLe($value, $min, $max)
    {
        if (!self::validateInt($value)) {
            return false;
        }
        if ($value <= $max && $value > $min) {
            return true;
        }
        return false;
    }

    //大于等于小于等于
    public static function validateIntGeLe($value, $min, $max)
    {
        if (!self::validateInt($value)) {
            return false;
        }
        if ($value <= $max && $value >= $min) {
            return true;
        }
        return false;
    }

    //取值列表
    public static function validateIntIn($value, $valueList)
    {
        if (!self::validateInt($value)) {
            return false;
        }
        if (in_array($value, $valueList)) {
            return true;
        }
        return false;
    }

    //不在取值列表
    public static function validateIntNotIn($value, $valueList)
    {
        if (!self::validateInt($value)) {
            return false;
        }
        if (!in_array($value, $valueList)) {
            return true;
        }
        return false;
    }
    /**********************************Float*********************************************/
    //判断是否是浮点数
    public static function validateFloat($value)
    {
        if (is_float($value)) {
            return true;
        }
        if (is_string($value) && is_numeric($value)) {
            return true;
        }
        return false;
    }

    //大于
    public static function validateFloatGt($value, $min)
    {
        if (!self::validateFloat($value)) {
            return false;
        }
        if ($value > $min) {
            return true;
        }
        return false;
    }

    //大于等于
    public static function validateFloatGe($value, $min)
    {
        if (!self::validateFloat($value)) {
            return false;
        }
        if ($value >= $min) {
            return true;
        }
        return false;
    }

    //小于
    public static function validateFloatLt($value, $max)
    {
        if (!self::validateFloat($value)) {
            return false;
        }
        if ($value < $max) {
            return true;
        }
        return false;
    }

    //小于等于
    public static function validateFloatLe($value, $max)
    {
        if (!self::validateFloat($value)) {
            return false;
        }
        if ($value <= $max) {
            return true;
        }
        return false;
    }

    //大于小于
    public static function validateFloatGtLt($value, $min, $max)
    {
        if (!self::validateFloat($value)) {
            return false;
        }
        if ($value < $max && $value > $min) {
            return true;
        }
        return false;
    }

    //大于等于小于
    public static function validateFloatGeLt($value, $min, $max)
    {
        if (!self::validateFloat($value)) {
            return false;
        }
        if ($value < $max && $value >= $min) {
            return true;
        }
        return false;
    }

    //大于小于等于
    public static function validateFloatGtLe($value, $min, $max)
    {
        if (!self::validateFloat($value)) {
            return false;
        }
        if ($value <= $max && $value > $min) {
            return true;
        }
        return false;
    }

    //大于等于小于等于
    public static function validateFloatGeLe($value, $min, $max)
    {
        if (!self::validateFloat($value)) {
            return false;
        }
        if ($value <= $max && $value >= $min) {
            return true;
        }
        return false;
    }
    /**********************************Date*********************************************/
    //年月日
    public static function validateDate($value)
    {
        if (preg_match('/^([0-9]{4})-(0?[1-9]|1[0-2])-(0?[1-9]|[1-2][0-9]|3[01])$/', $value, $matches)) {
            return checkdate(intval($matches[2]), intval($matches[3]), intval($matches[1]));
        }
        return false;
    }

    //严格形式
    public static function validateDateEx($value)
    {
        if (preg_match('/^([0-9]{4})-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[01])$/', $value, $matches)) {
            return checkdate(intval($matches[2]), intval($matches[3]), intval($matches[1]));
        }
        return false;
    }

    //年月
    public static function validateYmDate($value)
    {
        if (preg_match('/^([0-9]{4})-(0?[1-9]|1[0-2])$/', $value, $matches)) {
            return true;
        }
        return false;
    }

    //严格形式
    public static function validateYmDateEx($value)
    {
        if (preg_match('/^([0-9]{4})-(0[1-9]|1[0-2])$/', $value, $matches)) {
            return true;
        }
        return false;
    }

    //From
    public static function validateDateFrom($value, $fromDate)
    {
        if (!self::validateDate($value)) {
            return false;
        }
        $timestamp = strtotime($value);
        $deststamp = strtotime($fromDate);
        if ($timestamp >= $deststamp) {
            return true;
        }
        return false;
    }

    //To
    public static function validateDateTo($value, $ToDate)
    {
        if (!self::validateDate($value)) {
            return false;
        }
        $timestamp = strtotime($value);
        $deststamp = strtotime($ToDate);
        if ($timestamp <= $deststamp) {
            return true;
        }
        return false;
    }

    //FromTo
    public static function validateDateFromTo($value, $fromDate, $ToDate)
    {
        if (!self::validateDate($value)) {
            return false;
        }
        $timestamp = strtotime($value);
        $deststamp = strtotime($fromDate);
        $deststamp2 = strtotime($ToDate);
        if ($timestamp <= $deststamp2 && $timestamp >= $deststamp) {
            return true;
        }
        return false;
    }
    /**********************************String*********************************************/
    //string
    public static function validateStr($value)
    {
        if (is_string($value)) {
            return true;
        }
        return false;
    }

    //字符串等于
    public static function validateStrEq($value, $equalsValue)
    {
        if (is_string($value) && $value === $equalsValue) {
            return true;
        }
        return false;
    }

    //不等于
    public static function validateStrNe($value, $equalsValue)
    {
        if (is_string($value) && $value !== $equalsValue) {
            return true;
        }
        return false;
    }

    //In
    public static function validateStrIn($value, $valueList)
    {
        if (is_string($value) && in_array($value, $valueList)) {
            return true;
        }
        return false;
    }

    //NotIn
    public static function validateStrNotIn($value, $valueList)
    {
        if (is_string($value) && !in_array($value, $valueList)) {
            return true;
        }
        return false;
    }

    //length
    public static function validateStrLen($value, $length)
    {
        if (is_string($value) && mb_strlen($value) == $length) {
            return true;
        }
        return false;
    }

    //大于等于
    public static function validateStrLenGe($value, $length)
    {
        if (is_string($value) && mb_strlen($value) >= $length) {
            return true;
        }
        return false;
    }

    //小于等于
    public static function validateStrLenLe($value, $length)
    {
        if (is_string($value) && mb_strlen($value) <= $length) {
            return true;
        }
        return false;
    }

    //大于小于
    public static function validateStrLenGeLe($value, $min, $max)
    {
        if (is_string($value)) {
            $length = mb_strlen($value);
            if ($length >= $min && $length <= $max) {
                return true;
            }
        }
        return false;
    }

    //length
    public static function validateByteLen($value, $length)
    {
        if (is_string($value) && strlen($value) == $length) {
            return true;
        }
        return false;
    }

    //大于等于
    public static function validateByteLenGe($value, $length)
    {
        if (is_string($value) && strlen($value) >= $length) {
            return true;
        }
        return false;
    }

    //小于等于
    public static function validateByteLenLe($value, $length)
    {
        if (is_string($value) && strlen($value) <= $length) {
            return true;
        }
        return false;
    }

    //大于小于
    public static function validateByteLenGeLe($value, $min, $max)
    {
        if (is_string($value)) {
            $length = strlen($value);
            if ($length >= $min && $length <= $max) {
                return true;
            }
        }
        return false;
    }

    //字母
    public static function validateLetters($value)
    {
        if (is_string($value) && preg_match('/^[a-zA-Z]+$/', $value)) {
            return true;
        }
        return false;
    }

    //数字
    public static function validateNumbers($value)
    {
        if (is_string($value) && preg_match('/^[0-9]+$/', $value)) {
            return true;
        }
        return false;
    }

    //word
    public static function validateWords($value)
    {
        if (is_string($value) && preg_match('/^[0-9a-zA-Z]+$/', $value)) {
            return true;
        }
        return false;
    }

    //var
    public static function validateVarName($value)
    {
        if (is_string($value) && preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $value)) {
            return true;
        }
        return false;
    }

    //email
    public static function validateEmail($value)
    {
        if (is_string($value) && filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    //ip
    public static function validateIp($value)
    {
        if (is_string($value) && filter_var($value, FILTER_VALIDATE_IP)) {
            return true;
        }
        return false;
    }

    //url
    public static function validateUrl($value)
    {
        if (is_string($value) && filter_var($value, FILTER_VALIDATE_URL)) {
            return true;
        }
        return false;
    }

    //phone
    public static function validateMobile($value)
    {
        if (is_string($value) && preg_match('/^1[0-9]{10}$/', $value)) {
            return true;
        }
        return false;
    }

    //Regexp
    public static function validateRegexp($value, $regexp)
    {
        if (is_string($value) && preg_match($regexp, $value)) {
            return true;
        }
        return false;
    }
    /**********************************Arr*********************************************/
    //数组
    public static function validateArr($value)
    {
        if (is_array($value)) {
            return true;
        }
        return false;
    }

    //严格索引数组
    public static function validateArrIndex($value)
    {
        if (is_array($value)) {
            foreach ($value as $key => $val) {
                if (!is_integer($key)) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    //长度
    public static function validateArrLen($value, $length)
    {
        if (!self::validateArrIndex($value)) {
            return false;
        }
        if (count($value) == $length) {
            return true;
        }
        return false;
    }

    //大于
    public static function validateArrLenGe($value, $length)
    {
        if (!self::validateArrIndex($value)) {
            return false;
        }
        if (count($value) >= $length) {
            return true;
        }
        return false;
    }

    //小于
    public static function validateArrLenLe($value, $length)
    {
        if (!self::validateArrIndex($value)) {
            return false;
        }
        if (count($value) <= $length) {
            return true;
        }
        return false;
    }

    //大于小于
    public static function validateArrLenGeLe($value, $min, $max)
    {
        if (!self::validateArrIndex($value)) {
            return false;
        }
        $length = count($value);
        if ($length >= $min && $length <= $max) {
            return true;
        }
        return false;
    }

    //in
    public static function validateArrIn($value, $valueList)
    {
        if (!self::validateArrIndex($value)) {
            return false;
        }
        $aDest = explode(",", $valueList);
        $result = array_diff($value, $aDest);
        if (empty($result)) {
            return true;
        }
        return false;
    }
    /**********************************File*********************************************/
    //后缀名
    public static function validateFileExt($value, $valueList)
    {
        if (!is_string($value)) {
            return false;
        }
        $info = pathinfo($value, PATHINFO_EXTENSION);
        if ($info && in_array($info, $valueList)) {
            return true;
        }
        return false;
    }
    /**
     * @var array 验证失败时的错误提示信息的模板
     *
     * 输入值一般为字符串
     */
    static private $errorTemplates = [
        // 整型（不提供length检测,因为负数的符号位会让人混乱, 可以用大于小于比较来做到这一点）
        'Int' => '“{{param}}”必须是整数',
        'IntEq' => '“{{param}}”必须等于 {{value}}',
        'IntGt' => '“{{param}}”必须大于 {{min}}',
        'IntGe' => '“{{param}}”必须大于等于 {{min}}',
        'IntLt' => '“{{param}}”必须小于 {{max}}',
        'IntLe' => '“{{param}}”必须小于等于 {{max}}',
        'IntGtLt' => '“{{param}}”必须大于 {{min}} 小于 {{max}}',
        'IntGeLe' => '“{{param}}”必须大于等于 {{min}} 小于等于 {{max}}',
        'IntGtLe' => '“{{param}}”必须大于 {{min}} 小于等于 {{max}}',
        'IntGeLt' => '“{{param}}”必须大于等于 {{min}} 小于 {{max}}',
        'IntIn' => '“{{param}}”只能取这些值: {{valueList}}',
        'IntNotIn' => '“{{param}}”不能取这些值: {{valueList}}',

        // 浮点型（内部一律使用double来处理）
        'Float' => '“{{param}}”必须是浮点数',
        'FloatGt' => '“{{param}}”必须大于 {{min}}',
        'FloatGe' => '“{{param}}”必须大于等于 {{min}}',
        'FloatLt' => '“{{param}}”必须小于 {{max}}',
        'FloatLe' => '“{{param}}”必须小于等于 {{max}}',
        'FloatGtLt' => '“{{param}}”必须大于 {{min}} 小于 {{max}}',
        'FloatGeLe' => '“{{param}}”必须大于等于 {{min}} 小于等于 {{max}}',
        'FloatGtLe' => '“{{param}}”必须大于 {{min}} 小于等于 {{max}}',
        'FloatGeLt' => '“{{param}}”必须大于等于 {{min}} 小于 {{max}}',

        // bool型
        'Bool' => '“{{param}}”必须是bool型(true or false)', // 忽略大小写
        'BoolSmart' => '“{{param}}”只能取这些值: true, false, 1, 0, yes, no, y, n（忽略大小写）',

        // 字符串
        'Str' => '“{{param}}”必须是字符串',
        'StrEq' => '“{{param}}”必须等于"{{value}}"',
        'StrEqI' => '“{{param}}”必须等于"{{value}}"（忽略大小写）',
        'StrNe' => '“{{param}}”不能等于"{{value}}"',
        'StrNeI' => '“{{param}}”不能等于"{{value}}"（忽略大小写）',
        'StrIn' => '“{{param}}”只能取这些值: {{valueList}}',
        'StrInI' => '“{{param}}”只能取这些值: {{valueList}}（忽略大小写）',
        'StrNotIn' => '“{{param}}”不能取这些值: {{valueList}}',
        'StrNotInI' => '“{{param}}”不能取这些值: {{valueList}}（忽略大小写）',
        // todo StrSame:var 检测某个参数是否等于另一个参数, 比如password2要等于password
        'StrLen' => '“{{param}}”长度必须等于 {{length}}', // 字符串长度
        'StrLenGe' => '“{{param}}”长度必须大于等于 {{min}}',
        'StrLenLe' => '“{{param}}”长度必须小于等于 {{max}}',
        'StrLenGeLe' => '“{{param}}”长度必须在 {{min}} - {{max}} 之间', // 字符串长度
        'ByteLen' => '“{{param}}”长度（字节）必须等于 {{length}}', // 字符串长度
        'ByteLenGe' => '“{{param}}”长度（字节）必须大于等于 {{min}}',
        'ByteLenLe' => '“{{param}}”长度（字节）必须小于等于 {{max}}',
        'ByteLenGeLe' => '“{{param}}”长度（字节）必须在 {{min}} - {{max}} 之间', // 字符串长度
        'Letters' => '“{{param}}”只能包含字母',
        'Alphabet' => '“{{param}}”只能包含字母', // 同Letters
        'Numbers' => '“{{param}}”只能是纯数字',
        'Digits' => '“{{param}}”只能是纯数字', // 同Numbers
        'LettersNumbers' => '“{{param}}”只能包含字母和数字',
        'Numeric' => '“{{param}}”必须是数值', // 一般用于大数处理（超过double表示范围的数,一般会用字符串来表示）, 如果是正常范围内的数, 可以使用'Int'或'Float'来检测
        'VarName' => '“{{param}}”只能包含字母、数字和下划线，并且以字母或下划线开头',
        'Email' => '“{{param}}”不是合法的email',
        'Url' => '“{{param}}”不是合法的Url地址',
        'Ip' => '“{{param}}”不是合法的IP地址',
        'Mac' => '“{{param}}”不是合法的MAC地址',
        'Regexp' => '“{{param}}”不匹配正则表达式“{{regexp}}”', // Perl正则表达式匹配. 目前不支持modifiers. http://www.rexegg.com/regex-modifiers.html

        // 数组. 如何检测数组长度为0
        'Arr' => '“{{param}}”必须是数组',
        'ArrLen' => '“{{param}}”长度必须等于 {{length}}',
        'ArrLenGe' => '“{{param}}”长度必须大于等于 {{min}}',
        'ArrLenLe' => '“{{param}}”长度必须小于等于 {{max}}',
        'ArrLenGeLe' => '“{{param}}”长度必须在 {{min}} ~ {{max}} 之间',
        // 文件
        'FileExt' => '“{{param}}”必须是文件',

        // Date & Time
        'Date' => '“{{param}}”必须符合日期格式YYYY-MM-DD',
        'DateFrom' => '“{{param}}”不得早于 {{from}}',
        'DateTo' => '“{{param}}”不得晚于 {{to}}',
        'DateFromTo' => '“{{param}}”必须在 {{from}} ~ {{to}} 之间',
        'DateTime' => '“{{param}}”必须符合日期时间格式YYYY-MM-DD HH:mm:ss',
        'DateTimeFrom' => '“{{param}}”不得早于 {{from}}',
        'DateTimeTo' => '“{{param}}”必须早于 {{to}}',
        'DateTimeFromTo' => '“{{param}}”必须在 {{from}} ~ {{to}} 之间',
//        'Time' => '“{{param}}”必须符合时间格式HH:mm:ss或HH:mm',
//        'TimeZone' => 'TimeZone:timezone_identifiers_list()',

        // 其它
        'Required' => '必须提供参数{{param}}',
        'Alias' => '',
        '>>>' => '',
    ];

    // 所有验证器格式示例
    static private $sampleFormats = [
        // 整型（不提供length检测,因为负数的符号位会让人混乱, 可以用大于小于比较来做到这一点）
        'Int' => 'Int',
        'IntEq' => 'IntEq:100',
        'IntGt' => 'IntGt:100',
        'IntGe' => 'IntGe:100',
        'IntLt' => 'IntLt:100',
        'IntLe' => 'IntLe:100',
        'IntGtLt' => 'IntGtLt:1,100',
        'IntGeLe' => 'IntGeLe:1,100',
        'IntGtLe' => 'IntGtLe:1,100',
        'IntGeLt' => 'IntGeLt:1,100',
        'IntIn' => 'IntIn:2,3,5,7,11',
        'IntNotIn' => 'IntNotIn:2,3,5,7,11',

        // 浮点型（内部一律使用double来处理）
        'Float' => 'Float',
        'FloatGt' => 'FloatGt:1.0',
        'FloatGe' => 'FloatGe:1.0',
        'FloatLt' => 'FloatLt:1.0',
        'FloatLe' => 'FloatLe:1.0',
        'FloatGtLt' => 'FloatGtLt:0,1.0',
        'FloatGeLe' => 'FloatGeLe:0,1.0',
        'FloatGtLe' => 'FloatGtLe:0,1.0',
        'FloatGeLt' => 'FloatGeLt:0,1.0',

        // bool型
        'Bool' => 'Bool', // 忽略大小写
        'BoolSmart' => 'BoolSmart',

        // 字符串
        'Str' => 'Str',
        'StrEq' => 'StrEq:abc',
        'StrEqI' => 'StrEqI:abc',
        'StrNe' => 'StrNe:abc',
        'StrNeI' => 'StrNeI:abc',
        'StrIn' => 'StrIn:abc,def,g',
        'StrInI' => 'StrInI:abc,def,g',
        'StrNotIn' => 'StrNotIn:abc,def,g',
        'StrNotInI' => 'StrNotInI:abc,def,g',
        'StrLen' => 'StrLen:8',
        'StrLenGe' => 'StrLenGe:8',
        'StrLenLe' => 'StrLenLe:8',
        'StrLenGeLe' => 'StrLenGeLe:6,8',
        'ByteLen' => 'ByteLen:8',
        'ByteLenGe' => 'ByteLenGe:8',
        'ByteLenLe' => 'ByteLenLe:8',
        'ByteLenGeLe' => 'ByteLenGeLe:6,8',
        'Letters' => 'Letters',
        'Alphabet' => 'Alphabet', // 同Letters
        'Numbers' => 'Numbers',
        'Digits' => 'Digits', // 同Numbers
        'LettersNumbers' => 'LettersNumbers',
        'Numeric' => 'Numeric',
        'VarName' => 'VarName',
        'Email' => 'Email',
        'Url' => 'Url',
        'Ip' => 'Ip',
        'Mac' => 'Mac',
        'Regexp' => 'Regexp:/^abc$/', // Perl正则表达式匹配

        // 数组. 如何检测数组长度为0
        'Arr' => 'Arr',
        'ArrLen' => 'ArrLen:5',
        'ArrLenGe' => 'ArrLenGe:1',
        'ArrLenLe' => 'ArrLenLe:9',
        'ArrLenGeLe' => 'ArrLenGeLe:1,9',

        // 对象
        'Obj' => 'Obj',

        // 文件
        'File' => 'File',
        'FileMaxSize' => 'FileMaxSize:10mb',
        'FileMinSize' => 'FileMinSize:100kb',
        'FileImage' => 'FileImage',
        'FileVideo' => 'FileVideo',
        'FileAudio' => 'FileAudio',
        'FileMimes' => 'FileMimes:mpeg,jpeg,png',

        // Date & Time
        'Date' => 'Date',
        'DateFrom' => 'DateFrom:2017-04-13',
        'DateTo' => 'DateTo:2017-04-13',
        'DateFromTo' => 'DateFromTo:2017-04-13,2017-04-13',
        'DateTime' => 'DateTime',
        'DateTimeFrom' => 'DateTimeFrom:2017-04-13 12:00:00',
        'DateTimeTo' => 'DateTimeTo:2017-04-13 12:00:00',
        'DateTimeFromTo' => 'DateTimeFromTo:2017-04-13 12:00:00,2017-04-13 12:00:00',
//        'Time' => 'Time',
//        'TimeZone' => 'TimeZone:timezone_identifiers_list()',

        // 其它
        'Required' => 'Required',

        // 条件判断
        'If' => 'If:selected', // 值是否等于 1, true, '1', 'true', 'yes', 'y'(字符串忽略大小写)
        'IfNot' => 'IfNot:selected', // 值是否等于 0, false, '0', 'false', 'no', 'n'(字符串忽略大小写)
        'IfTrue' => 'IfTrue:selected', // 值是否等于 true 或 'true'(忽略大小写)
        'IfFalse' => 'IfFalse:selected', // 值是否等于 false 或 'false'(忽略大小写)
        'IfExist' => 'IfExist:var', // 参数 var 是否存在
        'IfNotExist' => 'IfNotExist:var', // 参数 var 是否不存在
        'IfIntEq' => 'IfIntEq:var,1', // if (type === 1)
        'IfIntNe' => 'IfIntNe:var,2', // if (state !== 2). 特别要注意的是如果条件参数var的数据类型不匹配, 那么If条件是成立的; 而其它几个IfIntXx当条件参数var的数据类型不匹配时, If条件不成立
        'IfIntGt' => 'IfIntGt:var,0', // if (var > 0)
        'IfIntLt' => 'IfIntLt:var,1', // if (var < 0)
        'IfIntGe' => 'IfIntGe:var,6', // if (var >= 6)
        'IfIntLe' => 'IfIntLe:var,8', // if (var <= 8)
        'IfIntIn' => 'IfIntIn:var,2,3,5,7', // if (in_array(var, [2,3,5,7]))
        'IfIntNotIn' => 'IfIntNotIn:var,2,3,5,7', // if (!in_array(var, [2,3,5,7]))
        'IfStrEq' => 'IfStrEq:var,waiting', // if (type === 'waiting')
        'IfStrNe' => 'IfStrNe:var,editing', // if (state !== 'editing'). 特别要注意的是如果条件参数var的数据类型不匹配, 那么If条件是成立的; 而其它几个IfStrXx当条件参数var的数据类型不匹配时, If条件不成立
        'IfStrGt' => 'IfStrGt:var,a', // if (var > 'a')
        'IfStrLt' => 'IfStrLt:var,z', // if (var < 'z')
        'IfStrGe' => 'IfStrGe:var,A', // if (var >= '0')
        'IfStrLe' => 'IfStrLe:var,Z', // if (var <= '9')
        'IfStrIn' => 'IfStrIn:var,normal,warning,error', // if (in_array(var, ['normal', 'warning', 'error'], true))
        'IfStrNotIn' => 'IfStrNotIn:var,warning,error', // if (!in_array(var, ['warning', 'error'], true))
    ];
}