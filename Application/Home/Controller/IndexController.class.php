<?php
namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller
{
    public function index()
    {


        $username = 'liheming';
        $email = '1325789491@qq.com';
        $age = 22;
        $this->assign('user',$username);
        $this->assign('email',$email);
        $this->assign('age',$age);
        $this->display();


//        $this->display();
//        echo "你好";
        //取出所有的配置
//        $config = C('');
//        dump($config);

        //重定向
//        $this->redirect('testPub',2,2,'');

        //成功跳转
//        $this->success('成功跳转',U('testPub'),3);

        //失败跳转
//        $this->error('出错了 正在跳转',U('testPub'),5);

        //ajax 数据返回
//        $this->ajaxReturn(getTestData(),'xml');

        //I函数获取提交的数据
//        $server= I('server.HTTP_HOST');
//        dump($server);

    }

    function login()
    {
        $user =  I('get.user');
        if ($user === 'haily') {
            echo '你是'.$user;
//            $this->sucess('欢迎黎合明先生',U('index'),1);
        } else {
            echo '你是谁'.$user;

//            $this->error('你是谁',U('index'),1);
        }
    }
    public function hello($name='thinkphp'){
        $this->assign('name',$name);
        $this->display();
    }






    public function testPub()
    {
        echo "i m public error";

    }

    protected function testPro()
    {

        echo "i m protected";
    }

}