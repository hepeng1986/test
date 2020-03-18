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
    public static function validate(&$params,$validations,$isContiue = false){
        $sMsg = "";
        //确保本函数不抛异常
        try{
            if(!is_array($params) || !is_array($validations)){
                throw new Exception("校验参数必须是数组");
            }
            self::check($params,$validations,$isContiue);
        }catch (Exception $e){
            $sMsg = $e->getMessage();
        }
        return $sMsg;
    }
    //真正校验函数
    private static function _validate(&$params,$validations,$isContiue = false){
        //遍历
        foreach ($validations as $field=>$validator){
            $aConf = explode("|",$validator);
        }
    }
    /**********************************Int*********************************************/
    //判断是否是整数
    public static function validateInt($value)
    {
        if (is_int($value)) {
            return true;
        }
        if(is_string($value) && preg_match('/^(-?[1-9][0-9]*|0)$/',$value)){
                return true;
        }
        return false;
    }
    //判断是否等于整数
    public static function validateIntEq($value,$equalVal){
        if(!self::validateInt($value)){
            return false;
        }
        if($value == $equalVal){
            return true;
        }
        return false;
    }
    //判断是否不等于整数
    public static function validateIntNe($value,$equalVal){
        if(!self::validateInt($value)){
            return false;
        }
        if($value != $equalVal){
            return true;
        }
        return false;
    }
    //大于
    public static function validateIntGt($value,$min){
        if(!self::validateInt($value)){
            return false;
        }
        if($value > $min){
            return true;
        }
        return false;
    }
    //大于等于
    public static function validateIntGe($value,$min){
        if(!self::validateInt($value)){
            return false;
        }
        if($value >= $min){
            return true;
        }
        return false;
    }
    //小于
    public static function validateIntLt($value,$max){
        if(!self::validateInt($value)){
            return false;
        }
        if($value < $max){
            return true;
        }
        return false;
    }
    //小于等于
    public static function validateIntLe($value,$max){
        if(!self::validateInt($value)){
            return false;
        }
        if($value <= $max){
            return true;
        }
        return false;
    }
    //大于小于
    public static function validateIntGtLt($value,$min,$max){
        if(!self::validateInt($value)){
            return false;
        }
        if($value < $max && $value > $min){
            return true;
        }
        return false;
    }
    //大于等于小于
    public static function validateIntGeLt($value,$min,$max){
        if(!self::validateInt($value)){
            return false;
        }
        if($value < $max && $value >= $min){
            return true;
        }
        return false;
    }
    //大于小于等于
    public static function validateIntGtLe($value,$min,$max){
        if(!self::validateInt($value)){
            return false;
        }
        if($value <= $max && $value > $min){
            return true;
        }
        return false;
    }
    //大于等于小于等于
    public static function validateIntGeLe($value,$min,$max){
        if(!self::validateInt($value)){
            return false;
        }
        if($value <= $max && $value >= $min){
            return true;
        }
        return false;
    }
    //取值列表
    public static function validateIntIn($value,$valueList){
        if(!self::validateInt($value)){
            return false;
        }
        if(in_array($value,$valueList)){
            return true;
        }
        return false;
    }
    //不在取值列表
    public static function validateIntNotIn($value,$valueList){
        if(!self::validateInt($value)){
            return false;
        }
        if(!in_array($value,$valueList)){
            return true;
        }
        return false;
    }
    /**********************************Float*********************************************/
    //判断是否是浮点数
    public static function validateFloat($value)
    {
        if(is_float($value)) {
            return true;
        }
        if(is_string($value) && is_numeric($value)){
            return true;
        }
        return false;
    }
    //大于
    public static function validateFloatGt($value,$min)
    {
        if(!self::validateFloat($value)){
            return false;
        }
        if($value > $min){
            return true;
        }
        return false;
    }
    //大于等于
    public static function validateFloatGe($value,$min)
    {
        if(!self::validateFloat($value)){
            return false;
        }
        if($value >= $min){
            return true;
        }
        return false;
    }
    //小于
    public static function validateFloatLt($value,$max)
    {
        if(!self::validateFloat($value)){
            return false;
        }
        if($value < $max){
            return true;
        }
        return false;
    }
    //小于等于
    public static function validateFloatLe($value,$max)
    {
        if(!self::validateFloat($value)){
            return false;
        }
        if($value <= $max){
            return true;
        }
        return false;
    }
    //大于小于
    public static function validateFloatGtLt($value,$min,$max)
    {
        if(!self::validateFloat($value)){
            return false;
        }
        if($value < $max && $value > $min){
            return true;
        }
        return false;
    }
    //大于等于小于
    public static function validateFloatGeLt($value,$min,$max)
    {
        if(!self::validateFloat($value)){
            return false;
        }
        if($value < $max && $value >= $min){
            return true;
        }
        return false;
    }
    //大于小于等于
    public static function validateFloatGtLe($value,$min,$max)
    {
        if(!self::validateFloat($value)){
            return false;
        }
        if($value <= $max && $value > $min){
            return true;
        }
        return false;
    }
    //大于等于小于等于
    public static function validateFloatGeLe($value,$min,$max)
    {
        if(!self::validateFloat($value)){
            return false;
        }
        if($value <= $max && $value >= $min){
            return true;
        }
        return false;
    }
    /**********************************Date*********************************************/
    //年月日
    public static function validateDate($value)
    {
        if (preg_match('/^([0-9]{4})-(0?[1-9]|1[0-2])-(0?[1-9]|[1-2][0-9]|3[01])$/',$value,$matches)){
            return checkdate(intval($matches[2]), intval($matches[3]), intval($matches[1]));
        }
        return false;
    }
    //严格形式
    public static function validateDateEx($value)
    {
        if (preg_match('/^([0-9]{4})-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[01])$/',$value,$matches)){
            return checkdate(intval($matches[2]), intval($matches[3]), intval($matches[1]));
        }
        return false;
    }
    //年月
    public static function validateYmDate($value)
    {
        if (preg_match('/^([0-9]{4})-(0?[1-9]|1[0-2])$/',$value,$matches)){
           return true;
        }
        return false;
    }
    //严格形式
    public static function validateYmDateEx($value)
    {
        if (preg_match('/^([0-9]{4})-(0[1-9]|1[0-2])$/',$value,$matches)){
            return true;
        }
        return false;
    }
    //From
    public static function validateDateFrom($value,$fromDate)
    {
        if(!self::validateDate($value)){
            return false;
        }
        $timestamp = strtotime($value);
        $deststamp = strtotime($fromDate);
        if ($timestamp >= $deststamp){
            return true;
        }
        return false;
    }
    //To
    public static function validateDateTo($value,$ToDate)
    {
        if(!self::validateDate($value)){
            return false;
        }
        $timestamp = strtotime($value);
        $deststamp = strtotime($ToDate);
        if ($timestamp <= $deststamp){
            return true;
        }
        return false;
    }
    //FromTo
    public static function validateDateFromTo($value,$fromDate,$ToDate)
    {
        if(!self::validateDate($value)){
            return false;
        }
        $timestamp = strtotime($value);
        $deststamp = strtotime($fromDate);
        $deststamp2 = strtotime($ToDate);
        if ($timestamp <= $deststamp2 && $timestamp >= $deststamp){
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
    public static function validateStrEq($value,$equalsValue)
    {
        if (is_string($value) && $value === $equalsValue) {
            return true;
        }
        return false;
    }
    //不等于
    public static function validateStrNe($value,$equalsValue)
    {
        if (is_string($value) && $value !== $equalsValue) {
            return true;
        }
        return false;
    }
    //In
    public static function validateStrIn($value,$valueList)
    {
        if (is_string($value) && in_array($value,$valueList)) {
            return true;
        }
        return false;
    }
    //NotIn
    public static function validateStrNotIn($value,$valueList)
    {
        if (is_string($value) && !in_array($value,$valueList)) {
            return true;
        }
        return false;
    }
    //length
    public static function validateStrLen($value,$length)
    {
        if (is_string($value) && mb_strlen($value) == $length) {
            return true;
        }
        return false;
    }
    //大于等于
    public static function validateStrLenGe($value,$length)
    {
        if (is_string($value) && mb_strlen($value) >= $length) {
            return true;
        }
        return false;
    }
    //小于等于
    public static function validateStrLenLe($value,$length)
    {
        if (is_string($value) && mb_strlen($value) <= $length) {
            return true;
        }
        return false;
    }
    //大于小于
    public static function validateStrLenGeLe($value,$min,$max)
    {
        if(is_string($value)){
            $length = mb_strlen($value);
            if($length >= $min && $length <= $max){
                return true;
            }
        }
        return false;
    }
    //length
    public static function validateByteLen($value,$length)
    {
        if (is_string($value) && strlen($value) == $length) {
            return true;
        }
        return false;
    }
    //大于等于
    public static function validateByteLenGe($value,$length)
    {
        if (is_string($value) && strlen($value) >= $length) {
            return true;
        }
        return false;
    }
    //小于等于
    public static function validateByteLenLe($value,$length)
    {
        if (is_string($value) && strlen($value) <= $length) {
            return true;
        }
        return false;
    }
    //大于小于
    public static function validateByteLenGeLe($value,$min,$max)
    {
        if(is_string($value)){
            $length = strlen($value);
            if($length >= $min && $length <= $max){
                return true;
            }
        }
        return false;
    }
    //字母
    public static function validateLetters($value)
    {
        if(is_string($value) && preg_match('/^[a-zA-Z]+$/', $value)){
            return true;
        }
        return false;
    }
    //数字
    public static function validateNumbers($value)
    {
        if(is_string($value) && preg_match('/^[0-9]+$/', $value)){
            return true;
        }
        return false;
    }
    //word
    public static function validateWords($value)
    {
        if(is_string($value) && preg_match('/^[0-9a-zA-Z]+$/', $value)){
            return true;
        }
        return false;
    }
    //var
    public static function validateVarName($value)
    {
        if(is_string($value) && preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $value)){
            return true;
        }
        return false;
    }
    //email
    public static function validateEmail($value)
    {
        if(is_string($value) && filter_var($value, FILTER_VALIDATE_EMAIL)){
            return true;
        }
        return false;
    }
    //ip
    public static function validateIp($value)
    {
        if(is_string($value) && filter_var($value, FILTER_VALIDATE_IP)){
            return true;
        }
        return false;
    }
    //url
    public static function validateUrl($value)
    {
        if(is_string($value) && filter_var($value, FILTER_VALIDATE_URL)){
            return true;
        }
        return false;
    }
    //phone
    public static function validateMobile($value)
    {
        if(is_string($value) && preg_match('/^1[0-9]{10}$/', $value)){
            return true;
        }
        return false;
    }
    //Regexp
    public static function validateRegexp($value,$regexp)
    {
        if(is_string($value) && preg_match($regexp, $value)){
            return true;
        }
        return false;
    }
    /**********************************Arr*********************************************/
    //数组
    public static function validateArr($value)
    {
        if(is_array($value)){
            return true;
        }
        return false;
    }
    //严格索引数组
    public static function validateArrIndex($value)
    {
        if(is_array($value)){
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
    public static function validateArrLen($value,$length)
    {
        if(!self::validateArrIndex($value)){
            return false;
        }
        if(count($value) == $length){
            return true;
        }
        return false;
    }
    //大于
    public static function validateArrLenGe($value,$length)
    {
        if(!self::validateArrIndex($value)){
            return false;
        }
        if(count($value) >= $length){
            return true;
        }
        return false;
    }
    //小于
    public static function validateArrLenLe($value,$length)
    {
        if(!self::validateArrIndex($value)){
            return false;
        }
        if(count($value) <= $length){
            return true;
        }
        return false;
    }
    //大于小于
    public static function validateArrLenGeLe($value,$min,$max)
    {
        if(!self::validateArrIndex($value)){
            return false;
        }
        $length = count($value);
        if($length >= $min && $length <= $max){
            return true;
        }
        return false;
    }
    //in
    public static function validateArrIn($value,$valueList)
    {
        if(!self::validateArrIndex($value)){
            return false;
        }
        $aDest = explode(",",$valueList);
        $result = array_diff($value, $aDest);
        if(empty($result)){
            return true;
        }
        return false;
    }
    /**********************************File*********************************************/
    //后缀名
    public static function validateFileExt($value,$valueList)
    {
        if(!is_string($value)){
            return false;
        }
        $info = pathinfo($value,PATHINFO_EXTENSION );
        if($info && in_array($info,$valueList)){
            return true;
        }
        return false;
    }
}