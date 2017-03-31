<?php
/**
 * Created by PhpStorm.
 * User: haily
 * Date: 2017/3/31
 * Time: 16:38
 */


namespace Home\Controller;

use Think\Controller;

class EmptyController extends Controller
{
    public function _empty($massage='你是谁')
    {
        $str = "<script> alert(\" $massage  \") </script>";
        echo $str;
    }
}