<?php


//这是系统的规范要求，表示当前类是Home模块下的控制器类，命名空间和实际的控制器文件所在的路径是一致的，
//也就是说： Home\Controller\IndexController类
// 对应的控制器文件位于应用目录下面的 Home/Controller/IndexController.class.php，
//如果你改变了当前的模块名，那么这个控制器类的命名空间也需要随之修改。
//注意：命名空间定义必须写在所有的PHP代码之前声明，而且之前不能有任何输出，否则会出错
namespace Home\Controller;


//表示引入 Think\Controller 类库便于直接使用。 所以，
//namespace Home\Controller;
//use Think\Controller;
//class IndexController extends Controller
//等同于使用：
//namespace Home\Controller;
//class IndexController extends \Think\Controller
use Home\Controller\Index\index;
use Org\Util\Date;
use Think\Controller;


class IndexController extends Controller
{
    public function index()
    {

        $username = 'liheming';
        $email = '1325789491@qq.com';
        $age = 22;
        $this->assign('user', $username);
        $this->assign('email', $email);
        $this->assign('age', $age);
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


    public function api_home()
    {
        $this->display();
    }

    function publish_eat_order()
    {
        echo "i m publish ";
//        $Verify = new \Think\Verify();
//        $Verify->entry();
    }

    public function test()
    {     // 实例化admin对象，对应数据库中的表名
//        $User = M("User");
//        $userCount = $User->count();
//        $user = $User->getByUser_id(40,'user_phone'); //表示根据用户的id获取该条数据的值。
//        $user = $User->getFieldByUser_id(40,'user_phone'); //表示根据用户的id获取用户的phone的值。
//        dump($user);

    }

    function register_test()
    {
        $user = D("user");
        $data['user_phone'] = '18942433927';
        $data['user_pass'] = '94682431';
        $time = new Date();
//        $data['reg_time'] = $time;
        $result = $user->add($data);
        if ($result) {
            $this->success("数据添加成功", U('index'));
        } else {
            $this->error("数据添加失败");
        }

    }

    function list_user()

    {
        dump(getTestData());

        /**  接口说明 ：测试接口
         *<code>
         * URL地址：/index
         * 提交方式：POST
         * </code>
         * --------------------------------------------------
         * @title 测试接口
         * @action /index
         * @method  get
         */

        // 实例化admin对象，对应数据库中的表名
        $User = M("admin");

        //读取数据集其实就是获取数据表中的多行记录（以及关联数据）
//        $data = $User->where('id=134')->select();


        //读取数据是指读取数据表中的一行数据（或者关联数据）
        $data = $User->where('id=134')->find();

        //读取字段值
//        $data = $User->where('id=134')->getField('username');

//        echo $data;
        foreach ($data as $k => $v) {
            echo $k . "=>" . $v;
        }

        dump($data);
    }

    function get_md5()
    {
        $name = md5("黎合明gj浩瀚的复合弓和递归klsajdgkljdslkgjlkfdg54g654sfd654g56s放松放松");
        echo '解码前' . $name;

//       echo '解码后'.$namehou;
    }

    function get_base64()
    {

        $name = base64_encode("黎合明");
        echo '解码前' . $name;

        $namehou = base64_decode($name);
        echo '解码后' . $namehou;
    }

    public function read($id = 0, $ui = 0)
    {
        echo 'id=' . $id . $ui;
    }

    function login_test($username = null, $password = null)
    {


        $User = M("User");
        $num = $User->where("user_phone='%s' and user_pass='%s'", array($username, $password))->getField('user_phone');
        if ($num) {
            echo '登陆用户是' . $username;
        } else {
            echo '登陆失败用户名是' . $username . '密码是' . $password . $num;
        }

//        $admin = M("admin");
//        $username = $admin->where("username='%s' and password='%s'", array($user, $pass))->getField('username');
//        if ($user === 'liheming')
//            echo '姓名是:'.$user.'pass:'.$pass;
//        else
//            echo "登陆失败,who are you ".$user.$pass;
//        dump($username);
    }

    public function get_user_info()
    {

    }

    /**  接口说明 ：用户注销登录
     * @action /logout
     * @method  get
     * @param string $user_phone
     * @return  array(status,msg)
     */
    public function logout($user_phone = null)
    {
        $User = M("User");
        $User->user_token = '';
        // 删除数据库中token信息
        $status = $User->where("user_phone='%s'", $user_phone)->save();
        if ($status) {
            $arr = array('status' => $status, 'msg' => '注销成功');
            echo json_encode($arr);
        } else {
            $arr = array('status' => $status, 'msg' => '注销失败');
            echo json_encode($arr);
        }
    }

    /**  接口说明 ：用户修改头像
     * @action /update_icon
     * @method  get
     * @param string $user_phone
     * @param string $user_icon
     * @return  array(status,msg)
     */
//    TODO 为了防止图片名重名 存储图片时加个时间戳存储
    public function update_icon($user_phone = null, $user_icon = null)
    {
        $User = M("User");
        $User->user_icon = $user_icon;
        $status = $User->where("user_phone='%s'", $user_phone)->save();
        if ($status) {
            $arr = array('status' => $status, 'msg' => '用户修改头像成功');
            echo json_encode($arr);
        } else {
            $arr = array('status' => $status, 'msg' => '用户修改头像失败');
            echo json_encode($arr);
        }
    }

    /**  接口说明 ：用户修改密码
     * @action /update_pass
     * @method  get
     * @url_test http://localhost/tp/home/index/update_pass/user_phone/18942433927/user_pass/123/user_newPass/94682431
     * @param string $user_phone
     * @param string $user_pass
     * @param string $user_newPass
     * @return  array(status,msg)
     */
    public function update_pass($user_phone = null, $user_pass = null, $user_newPass = null)
    {
        $User = M("User");
        $num = $User->where("user_phone='%s' and user_pass='%s'", array($user_phone, $user_pass))->find();
        if ($num) {
            $User->user_pass = $user_newPass;
            $status = $User->where("user_phone='%s'", $user_phone)->save();
            if ($status) {
                $arr = array('status' => $status, 'msg' => '修改密码成功');
                echo "修改密码成功";
                echo json_encode($arr);
            } else {
                $arr = array('status' => $num, 'msg' => '修改密码失败');
                echo "修改密码失败";
                echo json_encode($arr);
            }
        } else {
            $arr = array('status' => 0, 'msg' => '输入的原始密码不正确');
            echo "输入的原始密码不正确";
            echo json_encode($arr);
        }
    }

    /**  接口说明 ：用户登录
     * @action /login
     * @method  get
     * @param  String $user_phone
     * @param  string $user_pass
     * @return array(status,msg,token)
     */

    public function login($user_phone = null, $user_pass = null)
    {
        $token = null;
        $User = M("User");
        $num = $User->where("user_phone='%s' and user_pass='%s'", array($user_phone, $user_pass))->getField('user_phone');
        if ($num) {
            $token = base64_encode($num);
            $User->user_token = $token;
            $status = $User->where("user_phone='%s'", $user_phone)->save();
            if ($status) {
                $msg = '登陆成功 success';//返回信息
                $arr = array('status' => $status, 'msg' => $msg, 'token' => $token);
                echo json_encode($arr);
            } else {
                $msg = '登陆成功 failed';//返回信息
                $arr = array('status' => 1, $user_phone . 'pass' . $user_pass, 'msg' => $msg, 'token' => $token);
                echo json_encode($arr);
            }

        } else {
            $msg = '用户名或密码不正确 failed';//返回信息
            $arr = array('status' => $num . 'phone' . $user_phone . 'pass' . $user_pass, 'msg' => $msg, 'token' => $token);
            echo json_encode($arr);
//            dump(json_encode($arr))   ;
        }

    }

    /**  接口说明 ：用户修改资料
     * @action /update_userInfo
     * @method  get
     * @param  String $user_phone
     * @param  string $user_name
     * @param  string $user_alias
     * @param  string $user_sex
     * @param  string $user_age
     * @param  string $user_education
     * @param  string $user_skill
     * @param  string $user_hobby
     * @param  string $user_address
     * @return array(status,msg)
     */
    //$user_token=null
    public function update_userInfo($user_phone = null, $user_name = null, $user_alias = null, $user_sex = null,
                                    $user_age = null, $user_education = null, $user_skill = null, $user_hobby = null, $user_address = null)
    {
        $User = M("User");
        $User->user_name = $user_name;
        $User->user_alias = $user_alias;
        $User->user_sex = $user_sex;
        $User->user_age = $user_age;
        $User->user_education = $user_education;
        $User->user_skill = $user_skill;
        $User->user_hobby = $user_hobby;
        $User->user_address = $user_address;
        // 修改数据库中个人信息
        $status = $User->where("user_phone='%s'", $user_phone)->save();
        if ($status) {
            $arr = array('status' => $status, 'msg' => '修改信息成功');
            echo json_encode($arr);
        } else {
            $arr = array('status' => $status, 'msg' => '修改信息失败');
            echo json_encode($arr);
        }

    }

    /**  接口说明 ：用户发布共享需求
     * @action /pub_need
     * @method  get
     * @url_test http://localhost/tp/home/index/pub_need/user_token/MTg5NDI0MzM5Mjc=/user_id/1231/need_title
    /%E8%B7%91%E6%AD%A5/need_content/need_content/need_price/250/response_time/132123/need_type/type/need_status/need_status
     * @param  String $user_token
     * @param  string $user_id
     * @param  string $need_title
     * @param  string $need_content
     * @param  string $need_price
     * @param  string $response_time
     * @param  string $need_type
     * @param  string $need_status
     * @return array(status,msg)
     */

    public function pub_need( $user_id = null, $need_title = null, $need_content = null,
                             $need_price = null, $response_time = null, $need_type = null, $need_status = null, $user_token = null)
    {
        $Need = M('Need');
        if ($this->tokenIsEmpty($user_token)) {
//            echo "不为空";//判断token是否为空
            $Need->user_id = $user_id;
            $Need->need_title = $need_title;
            $Need->need_content = $need_content;
            $Need->need_price = $need_price;
            $Need->response_time = $response_time;
            $Need->need_type = $need_type;
            $Need->need_status = $need_status;
            // 发布需求任务
            $status = $Need->add();
            if ($status) {
                $arr = array('status' => $status, 'msg' => '发布共享需求成功');
                echo json_encode($arr);
            } else {
                $arr = array('status' => $status, 'msg' => '发布共享需求失败');
                echo json_encode($arr);
            }
        } else {
            $arr = array('status' => -1, 'msg' => 'token过期');
            echo json_encode($arr);
        }


    }

    /**  接口说明 ：管理员发布公告
     * @action /pub_notice
     * @method  get
     * @url_test http://localhost/tp/home/index/pub_notice/user_phone/18942433927/user_token
     * /MTg5NDI0MzM5Mjc=/notice_title/notice_title/notice_content/notice_content/notice_picture/notice_picture
     * @param  string $user_id
     * @param  string $notice_title
     * @param  string $notice_content
     * @param  string $notice_picture
     * @param  string $user_token
     * @return array(status,msg)
     */

    public function pub_notice( $user_id = null, $notice_title = null, $notice_content = null,
                               $notice_picture = null, $user_token = null)
    {
        $Notice = M('Notice');
        if ($this->tokenIsEmpty($user_token)) {
//            echo "不为空";//判断token是否为空
            $Notice->user_id = $user_id;
            $Notice->notice_title = $notice_title;
            $Notice->notice_content = $notice_content;
            $Notice->notice_picture = $notice_picture;
            // 发布需求任务
            $status = $Notice->add();
            if ($status) {
                $arr = array('status' => $status, 'msg' => '管理员发布公告成功');
                echo json_encode($arr);
            } else {
                $arr = array('status' => $status, 'msg' => '管理员发布公告失败');
                echo json_encode($arr);
            }
        } else {
            $arr = array('status' => -1, 'msg' => 'token过期');
            echo json_encode($arr);
        }
    }

    /**  接口说明 ：用户发布话题帖子
     * @action /pub_topic
     * @method  get
     * @url_test http://localhost/tp/home/index/pub_topic/user_phone/18942433927/user_id/111122/user_token%20/
     * MTg5NDI0MzM5Mjc=/topic_title/topic_title/topic_content/topic_content/topic_picture/topic_picture
     * @param  string $user_id
     * @param  string $topic_title
     * @param  string $topic_content
     * @param  string $topic_picture
     * @param  string $user_token
     * @return array(status,msg)
     */

    public function pub_topic( $user_id = null, $topic_title = null, $topic_content = null,
                              $topic_picture = null, $user_token = null)
    {
        $topic = M('Topic');
        if ($this->tokenIsEmpty($user_token)) {
//            echo "不为空";//判断token是否为空
            $topic->user_id = $user_id;
            $topic->topic_title = $topic_title;
            $topic->topic_content = $topic_content;
            $topic->topic_picture = $topic_picture;
            // 发布需求任务
            $status = $topic->add();
            if ($status) {
                $arr = array('status' => $status, 'msg' => '用户话题公告成功');
                echo json_encode($arr);
            } else {
                $arr = array('status' => $status, 'msg' => '用户话题公告失败');
                echo json_encode($arr);
            }

        } else {

            $arr = array('status' => -1, 'msg' => 'token过期');
            echo json_encode($arr);
        }
    }

    /**  接口说明 ：发布我要吃饭
     * @action /pub_eat
     * @method  get
     * @url_test http://localhost/tp/home/index/pub_eat/user_token/MTg5NDI0MzM5Mjc=/order_title
     * /eat%20food/order_type/1/order_res_time/01113/order_note/do%20quick/order_pub_userId/142/strArr/红烧茄子,2/烧鸭腿,3/烧鸡腿,4/
     * @param  String $user_token
     * @param  String $order_title
     * @param  String $order_type
     * @param  String $order_res_time
     * @param  String $order_note
     * @param  String $order_pub_userId
     * @param  String $eatStrArr
     * @table Eat_order
     * @return array(status,msg)
     */
    public function pub_eat( $user_token = null, $order_title = null,
                            $order_type = null, $order_res_time = null, $order_note = null, $order_pub_userId = null, $eatStrArr = "红烧茄子,2/烧鸭腿,3/烧鸡腿,4")

    {
        if ($this->tokenIsEmpty($user_token)) {
//
            $food_id = null;
            $food_name = null;
            $food_num = null;
            $foodStatus = null;
            $foodArr = explode('/', $eatStrArr);// 红烧茄子,2   烧鸭腿,3   烧鸡腿,4
            for ($index = 0; $index < count($foodArr); $index++) {
                $cai = explode(',', $foodArr[$index]);
                for ($ind = 0; $ind < count($cai); $ind++) {
                    switch ($ind) {
                        case 0:
                            $food_name = $cai[$ind];
                            break;
                        case 1:
                            $food_num = $cai[$ind];
                            break;
                    }
                }
                $Eat_food = M("Eat_food");
                $food_id = time(); //取当前时间戳作为菜品编号
                $Eat_food->food_name = $food_name;
                $Eat_food->food_num = $food_num;
                $Eat_food->food_id = $food_id;
                $foodStatus = $Eat_food->add();
            }
//                    $eat_id = $Eat->where("food_id='%d'" , $user_token)->getField('eat_id');
            if ($foodStatus) {
                $Eat_order = M("Eat_order");
                $Eat_order->order_title = $order_title;
                $Eat_order->food_id = $food_id;
                $Eat_order->order_type = $order_type;
                $Eat_order->order_res_time = $order_res_time;
                $Eat_order->order_note = $order_note;
                $Eat_order->order_pub_userId = $order_pub_userId;
                // 发布需求任务
                $status = $Eat_order->add();
                if ($status) {
                    $arr = array('status' => 1, 'msg' => '发布我要吃饭成功');
                    echo json_encode($arr);
                } else {
                    $arr = array('status' => 0, 'msg' => '发布我要吃饭失败 , Eat_food数据表插入失败');
                    echo json_encode($arr);
                }
            } else {
                $arr = array('status' => 0, 'msg' => '发布我要吃饭失败 Eat_order数据表插入失败');
                echo json_encode($arr);
            }
            $food_id = null;
        } else {
            $arr = array('status' => -1, 'msg' => 'token过期');
            echo json_encode($arr);
        }
    }

    function tete($phone = null)
    {
        if (isset($phone)) {

            echo "yes";
        } else {
            echo "no";
        }

    }

    /**  工具说明 ：解析字符串数组
     * @url_test http://localhost/tp/home/index/test_shuZu
     * @param  String $strArr
     * @return boolean
     */


    function test_shuZu($strArr = null)
    {
//        $strArr = "红烧茄子,2/烧鸭腿,3/烧鸡腿,4";
        $foodArr = explode('/', $strArr);// 红烧茄子,2   烧鸭腿,3   烧鸡腿,4
        for ($index = 0; $index < count($foodArr); $index++) {
            $cai = explode(',', $foodArr[$index]);
            $food_name = null;
            $food_num = null;
            for ($ind = 0; $ind < count($cai); $ind++) {
                switch ($ind) {
                    case 0:
                        $food_name = $cai[$ind];
                        break;
                    case 1:
                        $food_num = $cai[$ind];
                        break;
                }
            }
            echo $food_name . $food_num . "<br>";
        }
    }


    /**  接口说明 ：发布我要做饭
     * @action /pub_make_food
     * @method  get
     * @url_test http://localhost/tp/home/index/pub_make_food/user_phone/18942433927/user_token/MTg5NDI0MzM5Mjc=/
     * order_title/IWillMakeFood/order_num/2/order_price/100/order_pic/000123/food_description/good/order_type/2/
     * order_makeFood_time/01321321/order_status/1/makeFood_res/fish/makeFood_float/killFish/makeFood_note/wait/order_pub_userId/1234
     * @table Make_order
     * @param  String $user_token
     * @param  String $order_title
     * @param  String $order_num
     * @param  String $order_price
     * @param  String $order_pic
     * @param  String $food_description
     * @param  String $order_type
     * @param  String $order_makeFood_time
     * @param  String $order_pub_userId
     * @param  String $order_status
     * @param  String $makeFood_res
     * @param  String $makeFood_float
     * @param  String $makeFood_note
     * @return array(status,msg)
     * http://localhost/tp/home/index/pub_make_food/user_phone/18942433927/user_token/MTg5NDI0MzM5Mjc=/
     * order_title/IWillMakeFood/order_num/2/order_price/100/order_pic/000123/food_description/good/order_type/1
     */


    public
    function pub_make_food( $user_token = null, $order_title = null, $order_num = null,
                           $order_price = null, $order_pic = null, $food_description = null, $order_type = null, $order_makeFood_time = null, $order_pub_userId = null,
                           $order_status = null, $makeFood_res = null, $makeFood_float = null, $makeFood_note = null)
    {

        if ($this->tokenIsEmpty($user_token)) {
            $Make = M("Make_order");
            $Make->order_title = $order_title;
            $Make->order_num = $order_num;
            $Make->order_price = $order_price;
            $Make->order_pic = $order_pic;
            $Make->food_description = $food_description;
            $Make->order_type = $order_type;
            $Make->order_makeFood_time = $order_makeFood_time;
            $Make->order_pub_userId = $order_pub_userId;
            $Make->order_status = $order_status;
            $Make->makeFood_res = $makeFood_res;
            $Make->makeFood_float = $makeFood_float;
            $Make->makeFood_note = $makeFood_note;
            // 发布需求任务
            $status = $Make->add();

            if ($status) {
                $arr = array('status' => 1, 'msg' => 'success 发布我要做饭成功');
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => 'insert Make_order failed发布我要做饭失败');
                echo json_encode($arr);
            }

        } else {
            $arr = array('status' => -1, 'msg' => 'token过期');
            echo json_encode($arr);
        }
    }


    /**  接口说明 ：用户对话题发表评论
     * @action /pub_comment
     * @method  get
     * @url_test http://localhost/tp/home/index/pub_topic_comment/user_token/MTg5NDI0MzM5Mjc=/user_id/111122/
     * user_token%20/MTg5NDI0MzM5Mjc=/content/content/topic_id/4654
     * @param  string $user_id
     * @param  string $user_token
     * @param  string $topic_id
     * @param  string $content
     * @return array(status,msg)
     */

    public
    function pub_topic_comment( $user_id = null, $topic_id = null, $content = null, $user_token = null)
    {
        if ($this->tokenIsEmpty($user_token)) {
            $Comment = M('Topic_comment');
//            echo "不为空";//判断token是否为空
//            $Comment->user_name = $user_phone;
            $Comment->user_id = $user_id;
            $Comment->topic_id = $topic_id;
            $Comment->content = $content;
            // 发布需求任务
            $status = $Comment->add();
            if ($status) {
                $arr = array('status' => $status, 'msg' => '用户发表评论成功');
                echo json_encode($arr);
            } else {
                $arr = array('status' => $status, 'msg' => '用户发表评论失败');
                echo json_encode($arr);
            }
        } else {
            $arr = array('status' => -1, 'msg' => 'token过期');
            echo json_encode($arr);
        }
    }

    /**  接口说明 ：获取用户个人信息
     * @action /get_user
     * @method  get
     * @url_test http://localhost/tp/home/index/get_user/user_token/MTg5NDI0MzM5Mjc=
     * @param  String $user_token
     * @return array(user_phone,user_name,user_icon,user_alias,user_sex,user_age,user_education,
     * user_skill,user_hobby,user_address,user_money,user_card,user_coupon,is_cooking,is_manager)
     */
    public function get_user( $user_token = null)
    {
        $user_info = null;
        if ($this->tokenIsEmpty($user_token)) {
            $User = M("User");
            $user_info = $User->where("user_token='%s'", $user_token)->Field('user_phone,user_name,user_icon,user_alias,user_sex,user_age,user_education,
      user_skill,user_hobby,user_address,user_money,user_card,user_coupon,is_cooking,is_manager')->select();

            if ($user_info) {
                $arr = array('status' => 1, 'msg' => '获取用户个人信息成功', 'userInfo' => $user_info);
                echo json_encode($arr);

            } else {
                $arr = array('status' => 0, 'msg' => '获取用户个人信息失败', 'userInfo' => $user_info);
                echo json_encode($arr);
            }

        } else {
            $arr = array('status' => -1, 'msg' => 'token过期', 'userInfo' => $user_info);
            echo json_encode($arr);
        }
    }

    /**  接口说明 ：获取公告数据
     * @action /get_notices
     * @method  get
     * @url_test http://localhost/tp/home/index/get_notices/user_phone/18942433927/user_token/MTg5NDI0MzM5Mjc=
     * @table notice
     * @param  String $user_token
     * @return array(notice_id,notice_title,notice_content,notice_picture,notice_time,user_id)
     */
    public
    function get_notices( $user_token = null)
    {
        if ($this->tokenIsEmpty($user_token)) {
            $Notice = M("Notice");
            $notices = $Notice->select();
            if ($notices) {
                $arr = array('status' => 1, 'msg' => '获取公告数据成功', 'notices' => $notices);
                echo json_encode($arr);
                dump($notices);

            } else {
                $arr = array('status' => 0, 'msg' => '获取公告数据失败', 'notices' => $notices);
                echo json_encode($arr);
            }

        } else {
            $arr = array('status' => -1, 'msg' => 'token过期', 'userInfo' => null);
            echo json_encode($arr);
        }
    }

    /**  接口说明 ：获取话题数据
     * @action /get_topics
     * @method  get
     * @url_test http://localhost/tp/home/index/get_topics/user_phone/18942433927/user_token/MTg5NDI0MzM5Mjc=
     * @table notice
     * @param  String $user_token
     * @return array(notice_id,notice_title,notice_content,notice_picture,notice_time,user_id)
     */
    public
    function get_topics( $user_token = null)
    {
        if ($this->tokenIsEmpty($user_token)) {
            $Topic = M("Topic");
            $topics = $Topic->select();


            if ($topics) {
                $arr = array('status' => 1, 'msg' => '获取话题数据成功', 'notices' => $topics);
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => '获取话题数据失败', 'notices' => $topics);
                echo json_encode($arr);
            }
        } else {
            $arr = array('status' => -1, 'msg' => 'token过期', 'userInfo' => null);
            echo json_encode($arr);
        }
    }


    /**  接口说明 ：获取所有定做饭订单
     * @action /get_eatFood
     * @method  get
     * @url_test http://localhost/tp/home/index/get_topics/user_phone/18942433927/user_token/MTg5NDI0MzM5Mjc=
     * @table notice
     * @param  String $user_token
     * @return array(notice_id,notice_title,notice_content,notice_picture,notice_time,user_id)
     */
    public
    function get_eatFood( $user_token = null)
    {
        if ($this->tokenIsEmpty($user_token)) {
            $Topic = M("Topic");
            $topics = $Topic->select();


            if ($topics) {
                $arr = array('status' => 1, 'msg' => '获取话题数据成功', 'notices' => $topics);
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => '获取话题数据失败', 'notices' => $topics);
                echo json_encode($arr);
            }
        } else {
            $arr = array('status' => -1, 'msg' => 'token过期', 'userInfo' => null);
            echo json_encode($arr);
        }
    }


    /**  工具说明 ：判断token是否为空
     * @url_test http://localhost/tp/home/index/tokenIsEmpty/user_phone/18942433927/user_token/MTg5NDI0MzM5Mjc=
     * @param  String $user_token
     * @return boolean
     */
    public
    function tokenIsEmpty($user_token = null)
    {
        $User = M("User");
        $user_token = $User->where("user_token='%s'", $user_token)->getField('user_token');
//        $user_token = $User->where("user_phone='%s' and user_token='%s'", array($user_phone, $user_token))->getField('user_token');
        if ($user_token) {
//            echo true . "成功";
            return true;
        } else {
//            echo false . "失败";
            return false;
        }

    }

    public
    function _empty($msg)
    {
        //把所有城市的操作解析到city方法
        $this->city($msg);
    }

    public
    function city($msg)
    {
        echo "这是空方法跳转过来的" . $msg;
    }

    function login_te()
    {
        echo I('get.user');
        echo I('get.name');
        $user = I('get.user');
        if ($user === 'haily') {
            echo '你是' . $user;
            $this->sucess('欢迎黎合明先生', U('index'), 1);
        } else {
            echo '你是谁' . $user;

            $this->error('你是谁', U('index'), 1);
        }
    }

    public
    function hello($name = 'thinkphp')
    {
        $this->assign('name', $name);
        //display方法中我们没有指定任何模板，所以按照系统默认的规则输出了Index/hello.html模板文件。
        $this->display();
//        $this->display('index');
    }

}