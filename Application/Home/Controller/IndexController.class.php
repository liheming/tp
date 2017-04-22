<?php


/**这是系统的规范要求，表示当前类是Home模块下的控制器类，命名空间和实际的控制器文件所在的路径是一致的，
 * 也就是说： Home\Controller\IndexController类
 * 对应的控制器文件位于应用目录下面的 Home/Controller/IndexController.class.php，
 * 如果你改变了当前的模块名，那么这个控制器类的命名空间也需要随之修改。
 * 注意：命名空间定义必须写在所有的PHP代码之前声明，而且之前不能有任何输出，否则会出错
 */
namespace Home\Controller;

/**
 *表示引入 Think\Controller 类库便于直接使用。 所以,
 *namespace Home\Controller;
 *use Think\Controller;
 *class IndexController extends Controller
 *等同于使用：
 *namespace Home\Controller;
 *class IndexController extends \Think\Controller
 */

use Org\Util\Date;
use Think\Controller;


class IndexController extends Controller
{

    /**  utils ：token过期
     * @return array(status,msg,token)
     */

    public static function tokenExpire()
    {
        $arr = array('status' => -1, 'msg' => 'token过期', 'result' => null);
        return json_encode($arr);
    }

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







//TODO  1、用户管理
    /**  接口说明 ：用户登录
     * @action /login
     * @method  get
     * @url_test http://localhost/tp/home/index/login/user_phone/18942433927/user_pass/94682431/
     * @param  String $user_phone
     * @param  string $user_pass
     * @return array(status,msg,token)
     */
    public function login($user_phone = null, $user_pass = null)
    {
        $token = null;
        $User = M("User");
        $pass = $User->where("user_phone='%s'", $user_phone)->getField('user_pass');

        if ($user_pass === $pass) {
//            echo '登陆成功';
//            手机号码和IMEI生成base64随机数作token返回给客户端
            $token = base64_encode($user_phone);
            $User->user_token = $token;
            $user_info = $User->where("user_token='%s'", $token)->Field('user_phone,user_name,user_icon,user_alias,user_sex,user_age,user_education,
      user_skill,user_hobby,user_address,user_money,user_card,user_coupon,is_cooking,is_manager')->select();

            $status = $User->where("user_phone='%s'", $user_phone)->save();
            $arr = array('status' => 1, 'msg' => '登陆成功 success', 'token' => $token);
            echo json_encode($arr);

        } else {
            $arr = array('status' => 0, 'msg' => '登陆失败 failed', 'token' => $token);
//                echo json_encode($arr);
        }
//        if (true) {
//
//            //手机号码和IMEI生成base64随机数作token返回给客户端
//            $token = base64_encode( $user_phone);
//            $User->user_token = $token;
//            $status = $User->where("user_phone='%s'", $user_phone)->save();
//            if (true) {
//                $arr = array('status' => $status, 'msg' => '登陆成功 success', 'token' => $token);
//                echo json_encode($arr);
//            } else {
//                $arr = array('status' => 0, 'msg' => '登陆失败 failed', 'token' => $token);
//                echo json_encode($arr);
//            }
//
//        } else {
//            $arr = array('status' => -1 , 'msg' => '用户名或密码不正确 failed', 'token' => $token);
//            echo json_encode($arr);
////            dump(json_encode($arr))   ;
//        }
    }

    /**  接口说明 ：用户注册账号
     * @action /register
     * @method  get
     * @url_test http://localhost/tp/home/index/register/
     * @param $user_phone String
     * @param $user_pass String
     * @return  array(status,msg)
     */
    public function register($user_phone = null, $user_pass = null)
    {
        $User = M("User");
        $User->user_phone = $user_phone;
        $User->user_pass = $user_pass;
        $status = $User->add();
        if ($status) {
            $arr = array('status' => 1, 'msg' => '注册成功');
            echo json_encode($arr);
        } else {
            $arr = array('status' => 0, 'msg' => '注册失败');
            echo json_encode($arr);
        }
    }

    /**  接口说明 ：用户重置密码
     * @action /resetPass
     * @method  get
     * @url_test http://localhost/tp/home/index/resetPass/
     * @param $user_phone String
     * @param $user_pass String
     * @return  array(status,msg)
     */
    public function resetPass($user_phone = null, $user_pass = null)
    {

        $User = M("User");
        $User->user_pass = $user_pass;
        $status = $User->where('user_phone= %s', $user_phone)->save();
        if ($status) {
            $arr = array('status' => 1, 'msg' => '重置密码成功');
            echo json_encode($arr);
        } else {
            $arr = array('status' => 0, 'msg' => '重置密码失败');
            echo json_encode($arr);
        }
    }


    /**  接口说明 ：用户注销登录
     * @action /logout
     * @method  get
     * @url_test http://localhost/tp/home/index/logout/user_token/MTg5NDI0MzM5Mjc=
     * @param string $user_token
     * @return  array(status,msg)
     */
    public function logout($user_token = null)
    {
        $User = M("User");
        $User->user_token = '';
        // 删除数据库中token信息
        $status = $User->where("user_token='%s'", $user_token)->save();
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
     * http://localhost/tp/home/index/update_icon/user_token/MTg5NDI0MzM5Mjc=
     * @param string $user_token
     * @return  array(status,msg)
     */

    // 带图片请求 为了防止图片名重名 存储图片时加个时间戳存储
    function update_icon($user_token = null)
    {
        $user_icon = '' . $this->uploadPic_ReturnPicName();
        if ($this->tokenIsEmpty($user_token)) {
            $User = M("User");
//            $user_icon =  $this->uploadTest();
            $User->user_icon = $user_icon;
            $status = $User->where("user_token='%s'", $user_token)->save();
            if ($status) {
                $arr = array('status' => $status, 'msg' => '用户修改头像成功');
                echo json_encode($arr);
            } else {
                $arr = array('status' => $status, 'msg' => '用户修改头像失败');
                echo json_encode($arr);
            }
        } else {
            $arr = array('status' => -1, 'msg' => 'token过期');
            echo json_encode($arr);
        }

    }

    /**  接口说明 ：用户修改密码
     * @action /update_pass
     * @method  get
     * @url_test http://localhost/tp/home/index/update_pass/user_phone/18942433927/user_pass/123/user_newPass/94682431
     * @param string $user_token
     * @param string $user_pass
     * @param string $user_newPass
     * @return  array(status,msg)
     */
    public function update_pass($user_token = null, $user_pass = null, $user_newPass = null)
    {
        if ($this->tokenIsEmpty()) {
            $User = M("User");
            $num = $User->where("user_token='%s' and user_pass='%s'", array($user_token, $user_pass))->find();
            if ($num) {
                $User->user_pass = $user_newPass;
                $status = $User->where("user_token='%s'", $user_token)->save();
                if ($status) {
                    $arr = array('status' => 1, 'msg' => '修改密码成功');
                    echo json_encode($arr);
                } else {
                    $arr = array('status' => 0, 'msg' => '修改密码失败');
                    echo json_encode($arr);
                }
            } else {
                $arr = array('status' => 0, 'msg' => '输入的原始密码不正确');
                echo json_encode($arr);
            }
        } else {
            $arr = array('status' => -1, 'msg' => 'token过期');
            echo json_encode($arr);

        }

    }


    /**  接口说明 ：用户修改资料(学历 性别 年龄等 一次修改一次)
     * @action /update_userInfo
     * @method  get
     * @@url_test http://localhost/tp/index.php/home/index/update_userInfo/user_token/MTg5NDI0MzM5Mjc=/user_info//user_data/
     * @param  String $user_token
     * @param  String $user_info
     * @param  String $user_data
     * @return array(status,msg)
     */

    public function update_userInfo($user_token = null, $user_info = null, $user_data = null)
    {
        if ($this->tokenIsEmpty($user_token)) {
            $User = M("User");
            $User->$user_info = $user_data;

            $status = $User->where("user_token='%s'", $user_token)->save();
            if ($status) {
                $arr = array('status' => $status, 'msg' => '修改信息成功');
                echo json_encode($arr);
            } else {
                $arr = array('status' => $status, 'msg' => '修改信息失败');
                echo json_encode($arr);
            }
        } else {
            $arr = array('status' => -1, 'msg' => 'token过期');
            echo json_encode($arr);
        }

    }


    /**  接口说明 ：获取用户个人信息
     * @action /get_userInfo
     * @method  get
     * @url_test http://localhost/tp/home/index/get_userInfo/user_token/MTg5NDI0MzM5Mjc=
     * @param  String $user_token
     * @return array(user_phone,user_name,user_icon,user_alias,user_sex,user_age,user_education,
     * user_skill,user_hobby,user_address,user_money,user_card,user_coupon,is_cooking,is_manager)
     */
    public function get_userInfo($user_token = null)
    {
        $user_info = null;
        if (true) {
            $User = M("User");
            $usersList = $User->where("user_token='%s'", $user_token)->Field('user_phone,user_name,user_icon,user_alias,user_sex,user_age,user_education,
      user_skill,user_hobby,user_address,user_money,user_card,user_coupon,is_cooking,is_manager')->find();
            if ($usersList) {
//               $dd  = json_encode($user_info);
                $arr = array('status' => 1, 'msg' => '获取用户个人信息成功', 'users' => $usersList);
                echo json_encode($arr);

            } else {
                $arr = array('status' => 0, 'msg' => '获取用户个人信息失败', 'users' => $usersList);
                echo json_encode($arr);
            }

        } else {
            $arr = array('status' => -1, 'msg' => 'token过期');
            echo json_encode($arr);
        }
    }

    //TODO  2、社区共享

    /**  接口说明 ：管理员发布公告
     * @action /pub_notice
     * @method  get
     * @url_test http://localhost/tp/home/index/pub_notice/user_phone/18942433927/user_token
     * /MTg5NDI0MzM5Mjc=/notice_title/notice_title/notice_content/notice_content/notice_picture/notice_picture
     * @param  string $notice_title
     * @param  string $notice_content
     * @param  string $notice_picture
     * @param  string $user_token
     * @return array(status,msg)
     */

    public function pub_notice($notice_title = null, $notice_content = null,
                               $notice_picture = null, $user_token = null)
    {
        if ($this->tokenIsEmpty($user_token)) {
            $Notice = M('Notice');
//            echo "不为空";//判断token是否为空
            $Notice->user_token = $user_token;
            $Notice->notice_title = $notice_title;
            $Notice->notice_content = $notice_content;
            $Notice->notice_picture = $notice_picture;
            // 发布需求任务
            $status = $Notice->add();
            if ($status) {
                $arr = array('status' => 1, 'msg' => '管理员发布公告成功');
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => '管理员发布公告失败');
                echo json_encode($arr);
            }
        } else {
            $arr = array('status' => -1, 'msg' => 'token过期');
            echo json_encode($arr);
        }
    }


    /**  接口说明 ：获取公告数据
     * @action /get_notices
     * @method  get
     * @url_test http://localhost/tp/home/index/get_notices/user_token/MTg5NDI0MzM5Mjc=
     * @table notice
     * @param  String $user_token
     * @return array(notice_id,notice_title,notice_content,notice_picture,notice_time,user_id)
     */
    public
    function get_notices($user_token = null)
    {
        if (true) {
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


    /**  接口说明 ：用户发布话题帖子
     * @action /pub_topic
     * @method  get
     * @url_test http://localhost/tp/home/index/pub_topic/user_phone/18942433927/user_id/111122/user_token/MTg5NDI0MzM5Mjc=/topic_title/topic_title/topic_content/topic_content/topic_picture/topic_picture
     * @param  string $topic_title
     * @param  string $topic_content
     * @param  string $topic_picture
     * @param  string $user_token
     * @return array(status,msg)
     */

    public function pub_topic($topic_title = null, $topic_content = null,
                              $user_token = null)
    {
        $topic = M('Topic');
        if ($this->tokenIsEmpty($user_token)) {
//            echo "不为空";//判断token是否为空
            $topic->user_token = $user_token;
            $topic->topic_title = $topic_title;
            $topic->topic_content = $topic_content;
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

    /**  接口说明 ：用户发布共享需求
     * @action /pub_need
     * @method  get
     * @url_test http://localhost/tp/home/index/pub_need/user_token/MTg5NDI0MzM5Mjc=/need_title
     * /%E8%B7%91%E6%AD%A5/need_content/need_content/need_price/250/response_time/132123/need_type/type/need_status/need_status
     * @param  String $user_token
     * @param  string $need_title
     * @param  string $need_content
     * @param  string $need_price
     * @param  string $response_time
     * @param  string $need_type
     * @param  string $expect_time
     * @return array(status,msg)
     */

    public function pub_need($need_title = null, $need_content = null,
                             $need_price = null, $response_time = null, $need_type = null, $expect_time = null, $user_token = null)
    {
        $Need = M('Need');
        if ($this->tokenIsEmpty($user_token)) {
//            echo "不为空";//判断token是否为空
            $Need->user_token = $user_token;
            $Need->need_title = $need_title;
            $Need->need_content = $need_content;
            $Need->need_price = $need_price;
            $Need->response_time = $response_time;
            $Need->expect_time = $expect_time;
            $Need->need_type = $need_type;
//            $Need->need_status = $need_status;
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


    /**  接口说明 ：获取对应的共享需求列表
     * @action /get_needList
     * @method  get
     * @url_test http://localhost/tp/home/index/get_needList/need_type/1
     * @param  string $need_type
     * @return array(status,msg)
     */

    public function get_needList($need_type = null)
    {
//      $Need = M('Need');
//      $needList = $Need->where('need_type=%d', $need_type)->select();

        $needList = M()->table(array('user' => 'us', 'need' => 'nd'))->
        where(array('us.user_token = tp.user_token'))->
        field('us.user_name , us.user_icon ,  us.user_phone  us.user_address , nd.need_title , nd.need_content , nd.need_price , nd.topic_id , nd.comment_num , nd.pub_topic_time')->select();


        if ($needList) {
            $arr = array('status' => 1, 'msg' => '获取对应的共享需求列表成功', 'needList' => $needList);
            echo json_encode($arr);
        } else {
            $arr = array('status' => 0, 'msg' => '获取对应的共享需求列表失败', 'needList' => $needList);
            echo json_encode($arr);
        }

    }







    //TODO  3、首页


    /**  接口说明 ：发布我要吃饭
     * @action /pub_eat
     * @method  get
     * @url_test http://localhost/tp/home/index/pub_eatFood/user_token/MTg5NDI0MzM5Mjc=/order_price/150/order_title/eat%20food/order_type/1/order_res_time/01113/order_note/do%20quick/order_pub_userId/142/strArr
     * @param  String $user_token
     * @param  String $order_title
     * @param  String $order_price
     * @param  String $order_type
     * @param  String $order_res_time
     * @param  String $order_note
     * @param  String $eatStrArr
     * @table Eat_order
     * @return array(status,msg)
     */
//       URL test 不允许有特殊字符 所以eatARR默认有值

    public function pub_eatFood($user_token = null, $order_title = null, $order_price = null,
                                $order_type = null, $order_res_time = null, $order_note = null, $eatStrArr = "红烧茄子,2/烧鸭腿,3/烧鸡腿,4")

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
                $Eat_order->order_price = $order_price;
                $Eat_order->order_type = $order_type;
                $Eat_order->order_res_time = $order_res_time;
                $Eat_order->order_note = $order_note;
                $Eat_order->user_token = $user_token;
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


    /**  接口说明 ：获取所有定做饭订单list
     * @action /get_eatFoodList
     * @method  get
     * @url_test http://localhost/tp/home/index/get_eatFoodList/
     * @table notice
     * @return array(notice_id,notice_title,notice_content,notice_picture,notice_time,user_id)
     */

    public function get_eatFoodList()
    {

        $Eat_order = M("Eat_order");
        $eat_orderS = $Eat_order->select();
        if ($eat_orderS) {
            //获取food_id对应的菜品数组
            $Eat_order = M("Eat_food");

            $Eat_order->select();

            foreach ($eat_orderS as $k => $v) {
                echo $k . 'k是什么' . $v['food_id'] . "<br>";
            }
            $arr = array('status' => 1, 'msg' => 'successful 获取所有定做饭订单成功', 'eat_orderS' => $eat_orderS);
            echo json_encode($arr);
        } else {
            $arr = array('status' => 0, 'msg' => 'failed 获取所有定做饭订单失败', 'eat_orderS' => $eat_orderS);
            echo json_encode($arr);
        }

    }


    /**  接口说明 ：厨师查看定做订单详情(单个) （eat）
     * @action /get_eatFoodItem
     * @method  get
     * @url_test http://localhost/tp/home/index/get_eatFoodItem/food_id/1492084342
     * @table notice
     * @param  String $food_id
     * @return array(food_name,food_num)
     */

    public function get_eatFoodItem($food_id = null)
    {

        $Eat_food = M("Eat_food");
        $eat_food = $Eat_food->where("food_id=%d", $food_id)->field('food_name,food_num')->select();
        if ($eat_food) {
            $arr = array('status' => 1, 'msg' => 'successful 获取所有定做饭订单成功', 'eat_food' => $eat_food);
            echo json_encode($arr);
        } else {
            $arr = array('status' => 0, 'msg' => 'failed 获取所有定做饭订单失败', 'eat_food' => $eat_food);
            echo json_encode($arr);
        }

    }

    /**  接口说明 ：获得所有厨师发布的菜品
     * @action /get_makeFoodList
     * @method  get
     * @url_test http://localhost/tp/home/index/get_makeFoodList/
     * @table Make_order
     * @return array(`order_title`, `order_price`,`order_id`)
     */
    public function get_makeFoodList()
    {
        if (true) {
//            $Make_order = M("Make_order");
            $makeOrderList = M()->table(array('user' => 'us', 'make_order' => 'mk'))->order('order_pub_time desc')->where(array('`us`.user_token = `mk`.user_token', 'order_status=1'))->field('us.user_name , mk.order_id , mk.order_title ,mk.food_description ,mk.order_price ,mk.order_num ,mk.order_pic  ')->select();
//            $makeOrderList = $Make_order->order('order_pub_time desc')->where('order_status=%d',1)->field('user_token,order_id,order_title, food_description ,order_price, order_num , order_pic')->select();
            if ($makeOrderList) {
                $arr = array('status' => 1, 'msg' => 'successful 获得所有厨师发布的菜品成功', 'makeOrderList' => $makeOrderList);
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => 'failed 获得所有厨师发布的菜品失败', 'makeOrderList' => $makeOrderList);
                echo json_encode($arr);
            }
        } else {
            self::tokenExpire();
        }
    }

    /**  接口说明 ：查看做饭菜品详情页(单个) （make）
     * @action /get_eatFoodItem
     * @method  get
     * @url_test http://localhost/tp/home/index/look_makeFood/order_id/43
     * @table notice
     * @param  String $order_id
     * @return array(food_name,food_num)
     */

    public function look_makeFood($order_id = null)
    {


        $user_token = M('Make_order')->where('order_id=%d', $order_id)->getfield('user_token');//获取token 得到用户的信息
        $make_orderList = M('Make_order')->where("user_token='%s'", $user_token)->field('order_title , order_pic , order_num, order_id')->select();

//        SELECT order_comment.comment_content , order_comment.average , user.user_icon , user.user_name FROM `order_comment` ,user WHERE order_comment.order_id = 43 AND user.user_token = order_comment.user_token

        $orderComment = M()->table(array('user' => 'us', 'order_comment' => 'oc'))->where(array('oc.order_id =' . $order_id, 'us.user_token = oc.user_token'))->
        field('us.user_name , us.user_icon ,oc.comment_content,oc.average ,oc.comment_time')->select();


        if ($orderComment) {

            $arr = array('status' => 1, 'msg' => 'successful 查看做饭菜品详情页成功', 'commentList' => $orderComment, 'makeOrderList' => $make_orderList);
            echo json_encode($arr);
        } else {
            $arr = array('status' => 0, 'msg' => 'failed    查看做饭菜品详情页失败', 'commentList' => $orderComment, 'makeOrderList' => $make_orderList);
            echo json_encode($arr);
        }

    }


    /**  接口说明 ：获取首页今天广告位的饭菜轮播图 3张图片和对应的order_id
     * @action /get_ad_photo
     * @method  get
     * @url_test http://localhost/tp/home/index/get_ad_photo/
     * @table Make_order
     * @return array(mk.order_id , mk.order_title ,mk.order_food_description ,mk.order_price ,mk.order_num ,mk.order_pic )
     */
    public
    function get_ad_photo()
    {
        if (true) {
//            where(array('`us`.user_token = `mk`.user_token' , 'order_status=1'))
            $Ad_photo = M()->table(array('ad_photo' => 'ad', 'make_order' => 'mk', 'user' => 'us'))->where(array('`ad`.order_id = `mk`.order_id', '`us`.user_token = `mk`.user_token'))->field('us.user_name, mk.order_id , mk.order_title ,mk.food_description ,mk.order_price ,mk.order_num ,mk.order_pic ')->select();
//            $Ad_photo=M()->table(array('ad_photo'=>'ad','make_order'=>'mk'))->where('`ad`.order_id = `mk`.order_id')->field('mk.order_id , mk.order_title ,mk.food_description ,mk.order_price ,mk.order_num ,mk.order_pic ')->select();
            if ($Ad_photo) {
                $arr = array('status' => 1, 'msg' => 'successful 获得所有厨师发布的菜品成功', 'makeOrderList' => $Ad_photo);
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => 'failed 获得所有厨师发布的菜品失败', 'makeOrderList' => $Ad_photo);
                echo json_encode($arr);
            }
        } else {
            self::tokenExpire();
        }
    }




    //TODO  4、我的  


    //8.1 订单中心 TODO 可能需要调用 get_eatFoodItem 公共接口进去查看订单详情

    /**  接口说明 ：8.1.1 待接单
     * @action /get_wait_order
     * @method  get
     * @url_test http://localhost/tp/home/index/get_wait_order/user_token/MTg5NDI0MzM5Mjc=
     * @table Eat_order
     * @param  String $user_token
     * @return array(status,msg,waitOrderList)
     */
    public function get_wait_order($user_token = null)
    {
        if (true) {
            $Eat_order = M("Eat_order");
            $waitOrderList = $Eat_order->where("user_token=%d  and  order_status=%d", array($user_token, 1))->select();
            if ($waitOrderList) {
                $arr = array('status' => 1, 'msg' => 'success 获取待接单成功', 'waitOrderList' => $waitOrderList);
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => 'failed 获取待接单失败', 'waitOrderList' => $waitOrderList);
                echo json_encode($arr);
            }

        } else {
            self::tokenExpire();
        }
    }


    /**  接口说明 ：8.1.2 待送餐
     * @action /get_deliver_order
     * @method  get
     * @url_test http://localhost/tp/home/index/get_deliver_order/user_token/MTg5NDI0MzM5Mjc=
     * @table Eat_order
     * @param  String $user_token
     * @return array(status,msg,deliverOrderList)
     */
    public function get_deliver_order($user_token = null)
    {
        if (true) {
            $Eat_order = M("Eat_order");
            $deliverOrderList = $Eat_order->where("user_token=%d  and  order_status=%d", array($user_token, 2))->select();
            if ($deliverOrderList) {
                $arr = array('status' => 1, 'msg' => 'success 获取待送餐成功', 'deliverOrderList' => $deliverOrderList);
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => 'failed 获取待送餐失败', 'deliverOrderList' => $deliverOrderList);
                echo json_encode($arr);
            }

        } else {
            self::tokenExpire();
        }
    }


    /**  接口说明 ：8.1.3 待确认
     * @action /get_confirm_order
     * @method  get
     * @url_test http://localhost/tp/home/index/get_confirm_order/user_token/MTg5NDI0MzM5Mjc=
     * @table Eat_order
     * @param  String $user_token
     * @return array(status,msg,confirmOrderList)
     */
    public function get_confirm_order($user_token = null)
    {
        if (true) {
            $Eat_order = M("Eat_order");
            $confirmOrderList = $Eat_order->where("user_token=%d  and  order_status=%d", array($user_token, 3))->select();
            if ($confirmOrderList) {
                $arr = array('status' => 1, 'msg' => 'success 获取待确认成功', 'confirmOrderList' => $confirmOrderList);
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => 'failed 获取待确认失败', 'confirmOrderList' => $confirmOrderList);
                echo json_encode($arr);
            }

        } else {
            self::tokenExpire();
        }
    }


    /**  接口说明 ：8.1.4 待评价
     * @action /get_comment_order
     * @method  get
     * @url_test http://localhost/tp/home/index/get_comment_order/user_token/MTg5NDI0MzM5Mjc=
     * @table Eat_order
     * @param  String $user_token
     * @return array(status,msg,commentOrderList)
     */
    public function get_comment_order($user_token = null)
    {
        if (true) {
            $Eat_order = M("Eat_order");
            $commentOrderList = $Eat_order->where("user_token=%d  and  order_status=%d", array($user_token, 4))->select();
            if ($commentOrderList) {
                $arr = array('status' => 1, 'msg' => 'success 获取待评价成功', 'commentOrderList' => $commentOrderList);
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => 'failed 获取待评价失败', 'commentOrderList' => $commentOrderList);
                echo json_encode($arr);
            }

        } else {
            $arr = array('status' => -1, 'msg' => 'token过期', 'commentOrderList' => null);
            echo json_encode($arr);
        }
    }


    /**  接口说明 ：8.1.4.1 评价订单  客户 (eat)
     * @action /pub_order_comment
     * @method  get
     * @url_test http://localhost/tp/home/index/pub_order_comment/user_token/MTg5NDI0MzM5Mjc=/comment_content/很好吃呀/comment_describe/4/comment_service/4/comment_taste/3/order_id/2
     * @table Order_comment
     * @param  String $user_token
     * @param  String $comment_content
     * @param  String $comment_describe
     * @param  String $comment_service
     * @param  String $comment_taste
     * @param  String $order_id
     * @return array(status,msg)
     */
    public function pub_order_comment($user_token = null, $comment_content = null, $comment_describe = null, $comment_service = null, $comment_taste = null, $order_id = null)
    {
        if (true) {

            $average = ($comment_describe + $comment_service + $comment_taste) / 3;
            $Order_comment = M("Order_comment");
            $Order_comment->user_token = $user_token;
            $Order_comment->comment_content = $comment_content;
            $Order_comment->comment_describe = $comment_describe;
            $Order_comment->comment_service = $comment_service;
            $Order_comment->comment_taste = $comment_taste;
            $Order_comment->average = $average;
            $Order_comment->order_id = $order_id;

            $commentOrderList = $Order_comment->add();
            if ($commentOrderList) {
                $arr = array('status' => 1, 'msg' => 'success 评价订单成功');
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => 'failed 评价订单失败');
                echo json_encode($arr);
            }

        } else {
            $arr = array('status' => -1, 'msg' => 'token过期');
            echo json_encode($arr);
        }
    }




    //8.2地址管理
    /**  接口说明 ：8.2.1 添加地址
     * @action /add_address
     * @method  get
     * @url_test http://localhost/tp/home/index/add_address/user_token/MTg5NDI0MzM5Mjc=/name/黎合明/phone/18942433927/area/广东佛山/community/佛职院/address/职教路3
     * http://localhost/tp/home/index/add_address/user_token/MTg5NDI0MzM5Mjc=/name/小明/phone/1325789491/area/广东韶关/community/梅花/address/坪阶头
     *
     * @table Order_comment
     * @param  String $user_token
     * @param  String $name
     * @param  String $phone
     * @param  String $area
     * @param  String $community
     * @param  String $address
     * @return array(status,msg)
     */
    public function add_address($user_token = null, $name = null, $phone = null, $area = null, $community = null, $address = null)
    {
        if ($this->tokenIsEmpty($user_token)) {
            $Address_manager = M("Address_manager");
            $Address_manager->user_token = $user_token;
            $Address_manager->name = $name;
            $Address_manager->phone = $phone;
            $Address_manager->area = $area;
            $Address_manager->community = $community;
            $Address_manager->address = $address;
            $status = $Address_manager->add();
            if ($status) {
                $arr = array('status' => 1, 'msg' => 'success 添加地址成功');
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => 'failed 添加地址失败');
                echo json_encode($arr);
            }
        } else {
            $arr = array('status' => -1, 'msg' => 'token过期');
            echo json_encode($arr);
        }
    }


    /**  接口说明 ：8.2.2 显示用户添加的地址
     * @action /show_address
     * @method  get
     * @url_test http://localhost/tp/home/index/show_address/user_token/MTg5NDI0MzM5Mjc=
     * @table Order_comment
     * @param  String $user_token
     */
    public function show_address($user_token = null)
    {
        if ($this->tokenIsEmpty($user_token)) {
            $Address_manager = M("Address_manager");
            $addressList = $Address_manager->field('name , phone , area , community , address ,status ')->select();
            if ($addressList) {
                $arr = array('status' => 1, 'msg' => 'success 显示用户添加的地址成功', 'addressList' => $addressList);
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => 'failed 显示用户添加的地址失败', 'addressList' => $addressList);
                echo json_encode($arr);
            }
        } else {
            $arr = array('status' => -1, 'msg' => 'token过期');
            echo json_encode($arr);
        }
    }


    /**  接口说明 ：8.2.3 修改用户地址
     * @action /update_address
     * @method  get
     * @url_test http://localhost/tp/home/index/show_address/user_token/MTg5NDI0MzM5Mjc=
     * @table Order_comment
     * @param  String $id
     * @param  String $name
     * @param  String $phone
     * @param  String $area
     * @param  String $community
     * @param  String $address
     * @return array(status,msg)
     */
    public function update_address($id = null, $name = null, $phone = null, $area = null, $community = null, $address = null)
    {
        $Address_manager = M("Address_manager");
        $Address_manager->name = $name;
        $Address_manager->phone = $phone;
        $Address_manager->area = $area;
        $Address_manager->community = $community;
        $Address_manager->address = $address;
        $status = $Address_manager->where('id=%d', $id)->save();
        if ($status) {
            $arr = array('status' => 1, 'msg' => 'success 修改用户地址成功');
            echo json_encode($arr);
        } else {
            $arr = array('status' => 0, 'msg' => 'failed 修改用户地址失败');
            echo json_encode($arr);
        }
    }


    /**  接口说明 ：8.2.4 删除用户地址
     * @action /delete_address
     * @method  get
     * @url_test http://localhost/tp/home/index/delete_address/id/
     * @table Order_comment
     * @param  String $id
     */
    public function delete_address($id = null)
    {

        $Address_manager = M("Address_manager");
        $status = $Address_manager->where('id=%d', $id)->delete();
        if ($status) {
            $arr = array('status' => 1, 'msg' => 'success 删除用户地址成功');
            echo json_encode($arr);
        } else {
            $arr = array('status' => 0, 'msg' => 'failed 删除用户地址失败');
            echo json_encode($arr);
        }
    }


    /**  接口说明 ：8.2.5  用户设置默认地址
     * @action /setDefault_address
     * @method  get
     * @url_test http://localhost/tp/home/index/setDefault_address/user_token/14543/id/2
     * @table Order_comment
     * @param  String $user_token
     * @param  String $id
     */
    public function setDefault_address($user_token = nulll, $id = null)
    {

        $Address_manager = M("Address_manager");
        $Address_manager->status = 0;
        $status1 = $Address_manager->where("user_token='%s'", $user_token)->save(); // 把之前的默认地址状态清理

        $Address_manager->status = 1;
        $status = $Address_manager->where('id=%d', $id)->save(); // 设置默认地址状态
        if ($status && $status1) {
            $arr = array('status' => 1, 'msg' => 'success 用户设置默认地址成功');
            echo json_encode($arr);
        } else {
            $arr = array('status' => 0, 'msg' => 'failed 用户设置默认地址失败');
            echo json_encode($arr);
        }
    }















    /**  接口说明 ：发布我要做饭
     * @action /pub_makeFood
     * @method  get
     * @url_test http://localhost/tp/home/index/pub_makeFood/user_phone/18942433927/user_token/MTg5NDI0MzM5Mjc=/
     * order_title/IWillMakeFood/order_num/2/order_price/100/order_pic/000123/food_description/good/order_type/2/
     * order_makeFood_time/01321321/order_status/1/makeFood_res/fish/makeFood_float/killFish/makeFood_note/wait/order_pub_userId/1234
     * @table Make_order
     * @param  String $user_token
     * @param  String $order_title
     * @param  int $order_num = 0  上架今日菜色时在决定要上架多少数量
     * @param  String $order_price
     * @param  String $food_description
     * @param  String $order_type
     * @param  String $order_status 0   默认值为0 只显示在我的厨房内
     * @param  String $makeFood_res
     * @param  String $makeFood_float
     * @param  String $makeFood_note
     * @return array(status,msg)
     * http://localhost/tp/home/index/pub_make_food/user_phone/18942433927/user_token/MTg5NDI0MzM5Mjc=/
     * order_title/IWillMakeFood/order_num/2/order_price/100/order_pic/000123/food_description/good/order_type/1
     */
//todo 带图片请求
    public function pub_makeFood($user_token = null, $order_title = null,
                                 $order_price = null, $food_description = null, $order_type = null,
                                 $makeFood_res = null, $makeFood_float = null, $makeFood_note = null)
    {
        $order_pic = $this->uploadPic_ReturnPicName();
        //todo  订单数量 后面在添加 状态默认为0
        if (true) {
            $Make = M("Make_order");
            $Make->order_title = $order_title;
            $Make->order_price = $order_price;
            $Make->order_pic = $order_pic;
            $Make->food_description = $food_description;
            $Make->order_type = $order_type;
            $Make->user_token = $user_token;
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



    /**  接口说明 ：上架今日菜色,发布在推荐上显示 提交后显示在 首页>推荐
     * @action /pub_makeFood_order
     * @method  get
     * @url_test http://localhost/tp/home/index/pub_makeFood/user_phone/18942433927/user_token/MTg5NDI0MzM5Mjc=/
     * order_title/IWillMakeFood/order_num/2/order_price/100/order_pic/000123/food_description/good/order_type/2/
     * order_makeFood_time/01321321/order_status/1/makeFood_res/fish/makeFood_float/killFish/makeFood_note/wait/order_pub_userId/1234
     * @table Make_order
     * @param  String $user_token
     * @param  String $order_title
     * @param  int $order_num = 0
     * @param  String $order_price
     * @param  String $food_description
     * @param  String $order_type
     * @param  String $order_status 0
     * @param  String $makeFood_res
     * @param  String $makeFood_float
     * @param  String $makeFood_note
     * @return array(status,msg)
     * http://localhost/tp/home/index/pub_makeFood_order/user_phone/18942433927/user_token/MTg5NDI0MzM5Mjc=/
     * order_title/IWillMakeFood/order_num/2/order_price/100/order_pic/000123/food_description/good/order_type/1
     */
//todo 带图片请求
    public function pub_makeFood_order($user_token = null, $order_title = null,
                                       $order_price = null, $food_description = null, $order_type = null,
                                       $makeFood_res = null, $makeFood_float = null, $makeFood_note = null)
    {
        $order_pic = $this->uploadPic_ReturnPicName();
        //todo  订单数量 后面在添加 状态默认为0
        if (true) {
            $Make = M("Make_order");
            $Make->order_title = $order_title;
            $Make->order_price = $order_price;
            $Make->order_pic = $order_pic;
            $Make->food_description = $food_description;
            $Make->order_type = $order_type;
            $Make->user_token = $user_token;
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
     * @action /pub_topic_comment
     * @method  get
     * @url_test http://localhost/tp/home/index/pub_topic_comment/user_token/MTg5NDI0MzM5Mjc=/topic_id/2/content/我是大神，我会ky success的
     * user_token%20/MTg5NDI0MzM5Mjc=/content/content/topic_id/4654
     * @param  string $user_token
     * @param  string $topic_id
     * @param  string $content
     * @return array(status,msg)
     */

    public
    function pub_topic_comment($topic_id = null, $content = null, $user_token = null)
    {
        if (true) {
            $Comment = M('Topic_comment');
//            echo "不为空";//判断token是否为空
//            $Comment->user_name = $user_phone;
            $Comment->user_token = $user_token;
            $Comment->topic_id = $topic_id;
            $Comment->content = $content;
            // 发布需求任务
            $status = $Comment->add();
            if ($status) {
                $topic = $Comment->order('comment_time  desc')->field('comment_id , comment_time')->find();
                if ($topic) {
                    $arr = array('status' => 1, 'msg' => '用户发表评论成功', 'topic' => $topic);
                    echo json_encode($arr);
                } else {
                    $arr = array('status' => 0, 'msg' => '用户发表评论失败');
                    echo json_encode($arr);
                }
            }
        } else {
            $arr = array('status' => -1, 'msg' => 'token过期');
            echo json_encode($arr);
        }
    }


    /**  接口说明 ：楼主直接删除该话题和下面的评论
     * @action /delete_topic
     * @method  get
     * @url_test http://localhost/tp/home/index/delete_topic/topic_id/2/
     * @param  string $topic_id
     * @return array(status,msg)
     */

    public function delete_topic($topic_id = null)
    {
        if (true) {
            $Topic = M('Topic');//删除当前话题
            $status = $Topic->where('topic_id=%d', $topic_id)->delete();

//            $Comment = M('Topic_comment');//删除评论
//            $status2 = $Comment->where('topic_id=%d', $topic_id)->delete();
            if ($status) {
                $arr = array('status' => 1, 'msg' => '删除该话题和下面的评论成功');
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => '删除该话题和下面的评论失败');
                echo json_encode($arr);
            }
        }
    }


    /**  接口说明 ：楼主对该话题删除一条评论
     * @action /delete_topic_comment
     * @method  get
     * @url_test http://localhost/tp/home/index/delete_topic_comment/comment_id/2/
     * user_token%20/MTg5NDI0MzM5Mjc=/content/content/topic_id/4654
     * @param  string $comment_id
     * @return array(status,msg)
     */

    public function delete_topic_comment($comment_id = null)
    {
        if (true) {
            $Comment = M('Topic_comment');
            $status = $Comment->where('comment_id=%d', $comment_id)->delete();

            if ($status) {

                $arr = array('status' => 1, 'msg' => '楼主删除一条评论成功', 'topic' => $comment_id);
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => '楼主删除一条评论失败');
                echo json_encode($arr);
            }
        }

    }


    /**  接口说明 ：获取话题数据
     * @action /get_topicS
     * @method  get
     * @url_test http://localhost/tp/home/index/get_topicS/
     * @table Topic
     * @return array(topic_id,topic_title,topic_content,topic_picture,topic_time,user_id,comment_num)
     */
    public function get_topicS()
    {
        if (true) {
            $topics = M()->table(array('user' => 'us', 'topic' => 'tp'))->
            where(array('us.user_token = tp.user_token'))->
            field('us.user_name , us.user_icon , tp.topic_title , tp.topic_content , tp.topic_picture , tp.topic_id , tp.comment_num , tp.pub_topic_time')->
            select();

            if ($topics) {
                $arr = array('status' => 1, 'msg' => '获取话题数据成功', 'topics' => $topics);
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => '获取话题数据失败', 'topics' => $topics);
                echo json_encode($arr);
            }
        } else {
            $arr = array('status' => -1, 'msg' => 'token过期', 'userInfo' => null);
            echo json_encode($arr);
        }
    }


    /**  接口说明 ：查看浏览指定话题包括下面的评论
     * @action /look_topic
     * @method  get
     * @url_test http://localhost/tp/home/index/look_topic/topic_id/2
     * @table topic && topic_comment
     * @param  String $user_token
     * @param  String $topic_id
     * @return array( us.user_name , us.user_icon ,tp.content,tp.comment_time,tp.comment_id )
     */
    public function look_topic($topic_id = null)
    {
        if (true) {
            //获取评论
//            $Topic_comment = M("Topic_comment");
//            $topic_commentS = $Topic_comment->where("topic_id='%d'", $topic_id)->select();

            $topic_commentS = M()->table(array('user' => 'us', 'topic_comment' => 'tp'))->where(array('tp.topic_id =' . $topic_id, 'us.user_token = tp.user_token'))->
            field('us.user_token , us.user_name , us.user_icon ,tp.content,tp.comment_time,tp.comment_id')->select();

            if ($topic_commentS) {

                $arr = array('status' => 1, 'msg' => '获取话题数据成功', 'topic_commentS' => $topic_commentS);
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => '获取话题数据失败', 'topic_commentS' => $topic_commentS);
                echo json_encode($arr);
            }
        } else {
            $arr = array('status' => -1, 'msg' => 'token过期', 'userInfo' => null);
            echo json_encode($arr);
        }
    }


    /**  接口说明 ：获取我的厨房菜品列表List
     * @action /get_my_makeFoodList
     * @method  get
     * @url_test http://localhost/tp/home/index/get_my_makeFoodList/user_token/MTg5NDI0MzM5Mjc=
     * @table Make_order
     * @param  String $user_token
     * @return array(`order_id`, `order_title`, `food_id`, `order_price`, `order_type`, `order_res_time`, `order_note` ,`order_pub_userId`)
     */
    public function get_my_makeFoodList($user_token = null)
    {
        if (true) {

            $Make_order = M("Make_order");
            $makeOrderList = $Make_order->where("user_token='%s'", $user_token)->select();
            if ($makeOrderList) {
                $arr = array('status' => 1, 'msg' => 'successful 获取我的厨房菜品列表List成功', 'myMakeOrderList' => $makeOrderList);
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => 'failed 获取我的厨房菜品列表List失败', 'myMakeOrderList' => $makeOrderList);
                echo json_encode($arr);
            }
        } else {
            $arr = array('status' => -1, 'msg' => 'token过期', 'myMakeOrderList' => null);
            echo json_encode($arr);
        }
    }


    /**  工具说明 ：图片上传
     * @url_test http://localhost/tp/home/index/upload
     * @param  String $user_token
     * @return boolean
     */
    public function upload($user_token = true, $file = null)
    {

        if (true) {


            $base_path = "./upload/"; //存放目录
            if (!is_dir($base_path)) {
                mkdir($base_path, 0777, true);
            }
            $target_path = $base_path . basename($_FILES [$file] ['name']);
            if (move_uploaded_file($_FILES ['file'] ['tmp_name'], $target_path)) {
                $array = array(
                    "status" => true,
                    "msg" => $_FILES ['file'] ['name']
                );
                echo json_encode($array);
            } else {
                $array = array(
                    "status" => false,
                    "msg" => "There was an error uploading the file, please try again!" . $_FILES ['file'] ['error']
                );
                echo json_encode($array);
            }

//        $upload = new \Think\Upload();// 实例化上传类
//        $upload->maxSize   =     3145728 ;// 设置附件上传大小
//        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
//        $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
//        $upload->savePath  =     ''; // 设置附件上传（子）目录
//        // 上传文件
//        $info   =   $upload->upload();
//        if(!$info) {// 上传错误提示错误信息
//            $this->error($upload->getError());
//        }else{// 上传成功
//            $this->success('上传成功！');
//        }
        } else {
            $arr = array('status' => -1, 'msg' => 'token过期');
            echo json_encode($arr);
        }
    }


//    public function uploadTest($name = null, $age = null)
//    {
//
//        $upload = new \Think\Upload();// 实例化上传类
//        $upload->maxSize = 3145728;// 设置附件上传大小
//        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
//        $upload->rootPath = './Uploads/'; // 设置附件上传根目录
//        // 上传单个文件
//        $info = $upload->uploadOne($_FILES['file']);
//        if (!$info) {// 上传错误提示错误信息
//            $this->error($upload->getError());
//        } else {// 上传成功 获取上传文件信息
//            echo $info['savepath'] . $info['savename'];
//            echo "php返回给你的数据是姓名：".$name.'年龄：'.$age;
//        }
//    }


    /**  工具说明 ：判断token是否为空
     * @url_test http://localhost/tp/home/index/tokenIsEmpty/user_phone/18942433927/user_token/MTg5NDI0MzM5Mjc=
     * @param  String $user_token
     * @return boolean
     */


//    function uploadTest($user_token = null)
//
//    {
//        $user_icon = $this->uploadPic_ReturnPicName();
//        if (true) {
//            $User = M("User");
////            $user_icon =  $this->uploadTest();
//            $User->user_icon = $user_icon;
//            $status = $User->where("user_token='%s'", $user_token)->save();
//            if ($status) {
//                $arr = array('status' => $status, 'msg' => '用户修改头像成功');
//                echo json_encode($arr);
//            } else {
//                $arr = array('status' => $status, 'msg' => '用户修改头像失败');
//                echo json_encode($arr);
//            }
//        } else {
//            $arr = array('status' => -1, 'msg' => 'token过期');
//            echo json_encode($arr);
//        }
//
//    }

    public function uploadPic_ReturnPicName()
    {
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize = 3145728;// 设置附件上传大小
        $upload->method = 'get';// 设置提交方式默认是POST
//        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型（留空为不限制），使用数组或者逗号分隔的字符串设置，默认为空
        $upload->mimes = '';// 允许上传的文件类型（留空为不限制），使用数组或者逗号分隔的字符串设置，默认为空
        $upload->saveExt = '';// 上传文件的保存后缀，不设置的话使用原文件后缀
        // $upload->saveName = '';// 上传文件的保存规则，支持数组和字符串方式定义
        $upload->rootPath = './Uploads/'; // 设置附件上传根目录
        $upload->autoSub = false;           //自动使用子目录保存上传文件 默认为true
        $upload->hash = false;           //是否生成文件的hash编码 默认为true
        $upload->savePath = ''; // 设置附件上传（子）目录
//        $upload->subName  =     ''; // 	子目录创建方式，采用数组或者字符串方式定义
//        $upload->replace  =     ''; // 	存在同名文件是否是覆盖，默认为false
//        $upload->callback  =     ''; // 	检测文件是否存在回调，如果存在返回文件信息数组
        // 上传文件
        $info = $upload->upload(); //
//        $info   =   $upload->uploadOne($_FILES['photo']); // 一次上传一个文件
        if (!$info) {// 上传错误提示错误信息
            echo "上传失败";
            $this->error($upload->getError());
        } else {// 上传成功
//         return   $data['photo'] = $info['photo']['savename']."<br>";
            $user_icon = $data['photo'] = $info['photo']['savename'];
//          echo  '创建时间'.$data['create_time'] = NOW_TIME;
//                echo '上传成功';
//                $user_token = 'MTg5NDI0MzM5Mjc=';
//                $this->update_userIcon($user_token,$user_icon);
//            $this->success('上传成功！');
            foreach ($info as $file) {
                return $file['savepath'] . $file['savename'];
            }
        }
    }


    /**  工具说明 ：判断token是否为空
     * @url_test http://localhost/tp/home/index/tokenIsEmpty/user_phone/18942433927/user_token/MTg5NDI0MzM5Mjc=
     * @param  String $user_token
     * @return boolean
     */
    public function tokenIsEmpty($user_token = null)
    {
        $User = M("User");
        $user_token = $User->where("user_token='%s'", $user_token)->getField('user_token');
//        $user_token = $User->where("user_phone='%s' and user_token='%s'", array($user_phone, $user_token))->getField('user_token');
        if ($user_token) {
//            echo true . "成功";
            return true;
        } else {
//            echo false . "失败";
            return true;
        }

    }

//  空方法进入此方法
    public function _empty($msg)
    {
        //把所有城市的操作解析到city方法
        $this->city($msg);
    }

    public function city($msg)
    {
        echo "这是空方法跳转过来的" . $msg . " action 不正确";
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

    public function hello($name = 'thinkphp')
    {
        $this->assign('name', $name);
        //display方法中我们没有指定任何模板，所以按照系统默认的规则输出了Index/hello.html模板文件。
        $this->display();
//        $this->display('index');
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

    function phpIn()
    {
        echo $this->phpInfo();
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

    }


}


