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
    public static $url = 'http://192.168.43.105/tp/uploads/';

    public function testwy()
    {
//        $url='http://openapi.lrts.me/freeflow/qrySubList?partnerId=170615002001&token=b61e3b7691a41ccf4e11fd7c7aa54089&phone=13143431174';
//        $html = file_get_contents($url);


        $url = 'http://openapi.lrts.me/freeflow/qrySubList?partnerId=170615002001&token=b61e3b7691a41ccf4e11fd7c7aa54089&phone=13143431174';
        $html = file_get_contents($url);
        echo $html;
//        echo md5('/freeflow/qrySubList?phone=13143431174CKpvB6xXR2mVQZ7oiNSV');
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


    public function testPass()
    {
        echo hash("SHA256", "94682431");

    }

    /**  方法说明 ：1.1  请求成功的状态（1）返回信息 有数据就顺便返回数据
     * @action /register
     * @method   get
     * @url_test http://localhost/tp/home/index/successStatusEcho/msg/login success
     * @param $msg String
     * @param $data String
     * @return  array(status,msg)
     */

    public static function successStatusEcho($msg = null, $data = null)
    {
        if ($data) {
            $arr = array('status' => 1, 'msg' => $msg, 'data' => $data);
            echo json_encode($arr);
        } else {
            $arr = array('status' => 1, 'msg' => $msg);
            echo json_encode($arr);
        }


    }


    /**  方法说明 ：1.2  请求失败的状态（0）返回信息
     * @action /register
     * @method   get
     * @url_test http://localhost/tp/home/index/successStatusEcho/msg/login success
     * @param $msg String
     * @return  array(status,msg)
     */

    public static function errorStatusEcho($msg = null)
    {
        $arr = array('status' => 0, 'msg' => $msg);
        echo json_encode($arr);
    }


    /**  工具说明 ：1.4 判断token是否为空
     * @url_test http://localhost/tp/home/index/tokenIsEmpty/user_phone/18942433927/user_token/MTg5NDI0MzM5Mjc=
     * @param  String $user_token
     * @return boolean
     */
    public static function tokenIsEmpty($user_token = null)
    {
        if ($user_token == '' && $user_token == null) {
            self::errorStatusEcho('提交的token参数名错误或者token数据为空');
//            $this->errorStatusEcho('提交的token参数名错误或者token数据为空');
            exit(0);
        } else {
            $User = M("User");
            $user_token = $User->where("user_token='%s'", $user_token)->getField('user_token');
            if ($user_token) {
                return true;
            } else {
                $arr = array('status' => -1, 'msg' => 'token过期,请重新登录',);
                echo json_encode($arr);
            }
        }

    }



//TODO  1、用户管理

    /**  接口说明 ：5.1用户注册
     * @action /register
     * @method   get
     * @url_test http://localhost/tp/home/index/register/user_phone/18942433927/user_pass/123
     * @param $user_phone String
     * @param $user_pass String
     * @return  array(status,msg)
     */
    public function register($user_phone = null, $user_pass = null)
    {
        $pass = hash("SHA256", $user_pass);
        $User = M("User");
        $haveUser = $User->where("user_phone = '%s'", $user_phone)->find();
        if ($haveUser) {
            self::errorStatusEcho('注册失败,该手机已经注册 this phone has  register ！');
        } else {
            $User->user_phone = $user_phone;
            $User->user_pass = $pass;
            $status = $User->add();
            if ($status) {
                self::successStatusEcho('注册成功');
            } else {
                self::errorStatusEcho('注册失败');
            }
        }

    }

    /**  接口说明 ：5.2 重置密码
     * @action /resetPass
     * @method  get
     * @url_test http://localhost/tp/home/index/resetPass/
     * @param $user_phone String
     * @param $user_pass String
     * @return  array(status,msg)
     */
    public function resetPass($user_phone = null, $user_pass = null)
    {
        $pass = hash("SHA256", $user_pass);
        $User = M("User");
        $us = $User->where('user_phone= %s', $user_phone)->find();
        if ($us) {
            $User->user_pass = $pass;
            $status = $User->where('user_phone= %s', $user_phone)->save();
            if ($status) {
                self::successStatusEcho('重置密码成功');
            } else {
                self::errorStatusEcho('重置密码失败');
            }
        } else {
            self::errorStatusEcho('重置密码失败,不存在这个账号');
        }

    }


    /**  接口说明 ：5.3用户登陆
     * @action /login
     * @method  get
     * @url_test http://localhost/tp/home/index/login/user_phone/123/user_pass/123/
     * @param  String $user_phone
     * @param  string $user_pass
     * @return array(status,msg,token)
     */
    public function login($user_phone = null, $user_pass = null)
    {
        $pass = hash("SHA256", $user_pass);
        $token = null;
        $User = M("User");
        $pass_db = $User->where("user_phone='%s'", $user_phone)->getField('user_pass');
        if ($pass_db) {
            if ($pass === $pass_db) {
                $dbToken = $User->where("user_phone='%s'", $user_phone)->getfield('user_token');
                if ($dbToken) {//token 数据库已经存在
                    $arr = array('status' => 1, 'msg' => '登陆成功 success', 'token' => $dbToken);
                    echo json_encode($arr);
                } else {
                    $token = base64_encode($user_phone);
                    $User->user_token = $token;
                    $status = $User->where("user_phone='%s'", $user_phone)->save();
                    if ($status) {//把token存入数据库
                        $arr = array('status' => 1, 'msg' => '登陆成功 success', 'token' => $token);
                        echo json_encode($arr);
                    } else {
                        self::errorStatusEcho('token服务器生成失败');
                    }
                }
            } else {
                self::errorStatusEcho('登陆失败,密码错误');
            }
        } else {
            self::errorStatusEcho('该用户未注册');
        }


    }


    /**  接口说明 ：5.4注销登录
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
            self::successStatusEcho('注销成功');
        } else {
            self::errorStatusEcho('注销失败');
        }
    }


    /**  接口说明 ：5.5 用户修改资料(学历 性别 年龄等 一次修改一次)
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

        if (self::tokenIsEmpty($user_token)) {
            $User = M("User");
            $User->$user_info = $user_data;

            $status = $User->where("user_token='%s'", $user_token)->save();
            if ($status) {
                self::successStatusEcho('修改信息成功');
            } else {
                self::errorStatusEcho('修改信息失败');
            }
        }

    }


    /**  接口说明 ：5.6修改头像 （上传图片）
     * @action /update_icon
     * @method  get
     * http://localhost/tp/home/index/update_icon/user_token/MTg5NDI0MzM5Mjc=
     * @param string $user_token
     * @return  array(status,msg)
     */

    // 带图片请求 为了防止图片名重名 存储图片时加个时间戳存储
    function update_icon($user_token = null)
    {
        $user_icon = self::$url . $this->uploadPic_ReturnPicName();

        if (self::tokenIsEmpty($user_token)) {
            $User = M("User");
//            $user_icon =  $this->uploadTest();
            $User->user_icon = $user_icon;
            $status = $User->where("user_token='%s'", $user_token)->save();
            if ($status) {
//                self::successStatusEcho('用户修改头像成功');
            } else {
//                self::errorStatusEcho('用户修改头像失败');
            }
        }

    }

    /**  接口说明 ：5.7用户修改密码
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
        $pass = hash("SHA256", $user_pass);
//        echo $pass;
        $newPass = hash("SHA256", $user_newPass);
        if (self::tokenIsEmpty($user_token)) {
            $User = M("User");
            $num = $User->where("user_token='%s' and user_pass='%s'", array($user_token, $pass))->find();
            if ($num) {
                $User->user_pass = $newPass;
                $status = $User->where("user_token='%s'", $user_token)->save();
                if ($status) {
                    self::successStatusEcho('修改密码成功');
                } else {
                    self::errorStatusEcho('修改密码失败');
                }
            } else {
                self::errorStatusEcho('输入的原始密码不正确');
            }
        }

    }


    /**  接口说明 ：5.8获取用户个人信息
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
      user_skill,user_hobby,user_address,user_money,user_card,user_coupon,is_cooking,is_manager,longitude,latitude')->find();
            if ($usersList) {
                $arr = array('status' => 1, 'msg' => '获取用户个人信息成功', 'users' => $usersList);
                echo json_encode($arr);

            } else {
                self::errorStatusEcho('获取用户个人信息失败');
            }

        }
    }


    /**  接口说明 ：5.9获取用户位置信息
     * @action /get_userLocation
     * @method  get
     * @url_test http://localhost/tp/home/index/get_userLocation/user_token/MTg5NDI0MzM5Mjc=/longitude/456465465456/latitude/48646545646
     * @param  String $user_token
     * @param  String $longitude
     * @param  String $latitude
     * @param  String $area
     * @return msg and status
     */
    public function get_userLocation($user_token = null, $longitude = null, $latitude = null, $area = null)
    {
        if (true) {
            $User = M("User");
            $User->longitude = $longitude;
            $User->latitude = $latitude;
            $User->area = $area;
            $status = $User->where("user_token='%s'", $user_token)->save();
            if ($status) {
                $arr = array('status' => 1, 'msg' => '获取用户位置成功');
                echo json_encode($arr);

            } else {
                self::errorStatusEcho('获取用户位置失败');
            }

        }
    }




    //TODO  2、社区共享

    /**  接口说明 ：6.1 发布公告
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
        if (self::tokenIsEmpty($user_token)) {
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
                self::successStatusEcho('');
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => '管理员发布公告失败');
                echo json_encode($arr);
            }
        }
    }


    /**  接口说明 ：6.2 获取公告列表
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

        }
    }


    /**  接口说明 ：6.3 发布话题（帖子） 上传图片   -4.22修改
     * @action /pub_topic
     * @method  get
     * @url_test http://localhost/tp/home/index/pub_topic/user_phone/18942433927/user_id/111122/user_token/MTg5NDI0MzM5Mjc=/topic_title/topic_title/topic_content/topic_content/topic_picture/topic_picture
     * @param  string $topic_title
     * @param  string $topic_content
     * @param  string $user_token
     * @return array(status,msg)
     */

    public function pub_topic($topic_title = null, $topic_content = null,
                              $user_token = null)
    {
        $topic_picture = self::$url . $this->uploadPic_ReturnPicName();
//        echo $topic_picture;

        //存入数据库
        $topic = M('Topic');
        if (self::tokenIsEmpty($user_token)) {
//            echo "不为空";//判断token是否为空
            $topic->user_token = $user_token;
            $topic->topic_picture = $topic_picture;
            $topic->topic_title = $topic_title;
            $topic->topic_content = $topic_content;
            // 发布需求任务
            $status = $topic->add();
            if ($status) {
//                    $arr = array('status' => $status, 'msg' => '用户话题公告成功');
//                    echo json_encode($arr);
            } else {
//                    $arr = array('status' => $status, 'msg' => '用户话题公告失败');
//                    echo json_encode($arr);
            }

        }

    }


    /**  接口说明 ：6.4发表评论（to话题）
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
        if (self::tokenIsEmpty($user_token)) {
            $Comment = M('Topic_comment');
//            echo "不为空";//判断token是否为空
//            $Comment->user_name = $user_phone;
            $Comment->user_token = $user_token;
            $Comment->topic_id = $topic_id;
            $Comment->content = $content;
            // 发布需求任务
            $status = $Comment->add();
            $Topic = M('Topic');
            $Topic->comment_num = $Topic->getfield(comment_num) + 1;
            $Topic->where('topic_id=%d', $topic_id)->save();
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
        }
    }


    /**  接口说明 ：6.5 删除话题（帖子）      - 4.22 楼主可以删除该话题和下面的评论
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
            $Topic->topic_status = 0;
            $status = $Topic->where('topic_id=%d', $topic_id)->save();

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


    /**  接口说明 ：6.6 删除一条评论（只有楼主可以删除话题下面的评论）
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


    /**  接口说明 ：6.7 获取话题列表数据
     * @action /get_topicS
     * @method  get
     * @url_test http://localhost/tp/home/index/get_topicS/
     * @table Topic
     * @return array(topic_id,topic_title,topic_content,topic_picture,topic_time,user_id,comment_num)
     */
    public function get_topicS()
    {

        $topics = M()->table(array('user' => 'us', 'topic' => 'tp'))->
        where(array('us.user_token = tp.user_token', 'topic_status = 1'))->
        field('us.user_token ,us.user_name , us.user_icon , tp.topic_title , tp.topic_content , tp.topic_picture , tp.topic_id , tp.comment_num , tp.pub_topic_time')->
        select();

        if ($topics) {
            $arr = array('status' => 1, 'msg' => '获取话题数据成功', 'topics' => $topics);
            echo json_encode($arr);
        } else {
            $arr = array('status' => 0, 'msg' => '获取话题数据失败', 'topics' => $topics);
            echo json_encode($arr);
        }

    }


    /**  接口说明 ：6.8浏览指定话题包括下面的评论
     * @action /look_topic
     * @method  get
     * @url_test http://localhost/tp/home/index/look_topic/topic_id/2
     * @table topic && topic_comment
     * @param  String $topic_id
     * @return array( us.user_name , us.user_icon ,tp.content,tp.comment_time,tp.comment_id )
     */
    public function look_topic($topic_id = null)
    {

        //获取评论
//            $Topic_comment = M("Topic_comment");
//            $topic_commentS = $Topic_comment->where("topic_id='%d'", $topic_id)->select();

        $topic_commentS = M()->table(array('user' => 'us', 'topic_comment' => 'tp'))->order('comment_time ')->where(array('tp.topic_id =' . $topic_id, 'us.user_token = tp.user_token'))->
        field('us.user_token , us.user_name , us.user_icon ,tp.content,tp.comment_time,tp.comment_id')->select();

        if ($topic_commentS) {

            $arr = array('status' => 1, 'msg' => '获取话题数据成功', 'topic_commentS' => $topic_commentS);
            echo json_encode($arr);
        } else {
            $arr = array('status' => 0, 'msg' => '获取话题数据失败', 'topic_commentS' => $topic_commentS);
            echo json_encode($arr);
        }

    }


    /**  接口说明 ：6.9发布共享需求
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
        if (self::tokenIsEmpty($user_token)) {
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
        }
    }


    /**  接口说明 ：6.11获取对应的共享需求列表
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
        $needList = M()->table(array('user' => 'us', 'need' => 'nd'))->where(array('us.user_token = nd.user_token', 'need_type= ' . $need_type, 'need_status = 1'))->
        field('us.longitude ,us.latitude ,us.user_name , us.user_icon ,  us.user_phone ,  us.user_address , nd.need_id , nd.need_title , nd.need_content , nd.need_price ,nd.need_status , nd.response_time ,  nd.expect_time,  nd.pub_time')->select();

        if ($needList) {
            $arr = array('status' => 1, 'msg' => '获取对应的共享需求列表成功', 'needList' => $needList);
            echo json_encode($arr);
        } else {
            $arr = array('status' => 0, 'msg' => '获取对应的共享需求列表失败', 'needList' => $needList);
            echo json_encode($arr);
        }

    }

    /**  接口说明 ：6.12我要帮
     * @action /take_need
     * @method  get
     * @url_test http://localhost/tp/home/index/take_need/need_id/1
     * @param  string $need_id
     * @return array(status,msg)
     */
//fixme  我要帮待定
    public function take_need($need_id = null)
    {
        $Need = M('Need');
        $Need->need_status = 2;  //用户选择我要帮
        $status = $Need->where('need_id=%d', $need_id)->save();
        if ($status) {
            $arr = array('status' => 1, 'msg' => '我要帮成功');
            echo json_encode($arr);
        } else {
            $arr = array('status' => 0, 'msg' => '我要帮失败');
            echo json_encode($arr);
        }

    }







    //TODO  3、首页

//7.1我要吃
    /**  接口说明 ：7.1.1用户发布我要吃的饭菜
     * @action /pub_eat
     * @method  get
     * @url_test http://localhost/tp/home/index/pub_eatFood/user_token/MTg5NDI0MzM5Mjc=/order_price/150/order_title/eat%20food/order_type/1/order_res_time/252525/order_expect_time/303030/order_note/do%20quick/strArr
     * @param  String $user_token
     * @param  String $order_title
     * @param  String $order_price
     * @param  String $order_type
     * @param  String $order_expect_time
     * @param  String $order_res_time
     * @param  String $order_note
     * @param  String $eatStrArr
     * @table Eat_order
     * @return array(status,msg)
     */
//       URL test 不允许有特殊字符 所以eatARR默认有值

    public function pub_eatFood($user_token = null, $order_title = null, $order_price = null,
                                $order_type = null, $order_res_time = null, $order_expect_time = null, $order_note = null, $eatStrArr = "紫菜comma0comma3period青菜comma1comma3period白菜comma2comma4")

    {
        if (self::tokenIsEmpty($user_token)) {
//
            $food_id = time(); //取当前时间戳作为菜品编号
            $food_name = null;
            $food_size = null;
            $food_num = null;
            $foodStatus = null;
            $foodArr = explode('period', $eatStrArr);// 红烧茄子,1,2    烧鸭腿,2,3   烧鸡腿,0,4
            for ($index = 0; $index < count($foodArr); $index++) {
                $cai = explode('comma', $foodArr[$index]);
                for ($ind = 0; $ind < count($cai); $ind++) {
                    switch ($ind) {
                        case 0:
                            $food_name = $cai[$ind];
                            break;
                        case 1:
                            $food_size = $cai[$ind];
                            break;
                        case 2:
                            $food_num = $cai[$ind];
                            break;
                    }
                }
                $Eat_food = M("Eat_food");
                $Eat_food->food_name = $food_name;
                $Eat_food->food_size = $food_size;
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
                $Eat_order->order_expect_time = $order_expect_time;
                $Eat_order->eatStrArr = $eatStrArr;
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
        }
    }


    // 7.2我要做
    /**  接口说明 ：7.2.1获取我要吃饭的列表List
     * @action /get_eatFoodList
     * @method  get
     * @url_test http://localhost/tp/home/index/get_eatFoodList/
     * @table notice
     * @return array(notice_id,notice_title,notice_content,notice_picture,notice_time,user_id)
     */

    public function get_eatFoodList()
    {

        $eat_orderS = M()->table(array('Eat_order' => 'eo', 'user' => 'us'))->where(array('us.user_token = eo.user_token', 'order_status=1'))->
        field('us.user_name,us.user_phone,us.user_address,eo.order_id,eo.order_title,eo.order_price,eo.order_type,eo.order_note,eo.eatStrArr,eo.order_res_time,eo.order_expect_time')->select();
        if ($eat_orderS) {

            $arr = array('status' => 1, 'msg' => 'successful 获取所有定做饭订单成功', 'eat_orderS' => $eat_orderS);
            echo json_encode($arr);
        } else {
            $arr = array('status' => 0, 'msg' => 'failed 获取所有定做饭订单失败', 'eat_orderS' => $eat_orderS);
            echo json_encode($arr);
        }

    }


    /**  接口说明 ：7.2.2 厨师接单  厨师觉得自己可以做，可以接单
     * @action /take_order
     * @method  get
     * @url_test http://localhost/tp/home/index/take_order/user_token/MTIzNA==/order_id/59
     * @table notice
     * @param  String $order_id
     * @param  String $user_token
     * @return array(food_name,food_num)
     */

    public function take_order($order_id = null, $user_token)
    {

        $Eat_order = M("Eat_order");
        $Eat_order->order_status = 2;//标识厨师已经接单
        $Eat_order->cook_user_token = $user_token;//标识用户已经接单
        $status = $Eat_order->where("order_id=%d", $order_id)->save();
        if ($status) {
            $arr = array('status' => 1, 'msg' => 'successful 厨师接单成功');
            echo json_encode($arr);
        } else {
            $arr = array('status' => 0, 'msg' => 'failed     厨师接单失败');
            echo json_encode($arr);
        }

    }

    //7.3 推荐

    /**  接口说明 ：7.3.1获取所有厨师的菜品列表List
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
            $makeOrderList = M()->table(array('user' => 'us', 'make_order' => 'mk'))->order('order_pub_time desc')->where(array('`us`.user_token = `mk`.user_token', 'order_status=2'))->field('us.longitude ,us.latitude ,us.user_name , mk.order_id , mk.order_title ,mk.food_description ,mk.order_price, mk.star ,mk.order_num ,mk.order_pic, mk.order_type ')->select();
//            $makeOrderList = $Make_order->order('order_pub_time desc')->where('order_status=%d',1)->field('user_token,order_id,order_title, food_description ,order_price, order_num , order_pic')->select();
            if ($makeOrderList) {
                $arr = array('status' => 1, 'msg' => 'successful 获得所有厨师发布的菜品成功', 'makeOrderList' => $makeOrderList);
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => 'failed 获得所有厨师发布的菜品失败', 'makeOrderList' => $makeOrderList);
                echo json_encode($arr);
            }
        } else {

        }
    }

    /**  接口说明 ：7.3.2查看做饭菜品详情页(单个)（make）
     * @action /get_eatFoodItem
     * @method  get
     * @url_test http://localhost/tp/home/index/look_makeFood/order_id/43
     * @table notice
     * @param  String $order_id
     * @return array(food_name,food_num)
     */

    public function look_makeFood($order_id = null)
    {

        $user_token = M('Make_order')->where('order_id=%d', $order_id)->getfield('user_token');//获取token 得到厨师的信息
//        echo $user_token;

        $condition['mk.order_status'] = 2;//只能查看该厨师其它已经上架的菜色
        $condition['mk.user_token'] = $user_token;
        $condition['us.user_token'] = $user_token;
        $condition['_logic'] = 'AND';

        $make_orderList = M()->table(array('user' => 'us', 'make_order' => 'mk'))->where($condition)->field('us.user_name , mk.order_id , mk.order_title ,mk.food_description ,mk.order_price, mk.star ,mk.order_num ,mk.order_pic, mk.order_type')->select();

//        dump($make_orderList);
        $orderComment = M()->table(array('user' => 'us', 'order_comment' => 'oc'))->where(array('oc.order_id =' . $order_id, 'us.user_token = oc.user_token'))->
        field('us.user_name , us.user_icon ,oc.comment_content,oc.average ,oc.comment_time')->select();
//        dump($orderComment);

        if ($orderComment) {

            $arr = array('status' => 1, 'msg' => 'successful 查看做饭菜品详情页成功', 'commentList' => $orderComment, 'makeOrderList' => $make_orderList);
            echo json_encode($arr);
        } else {
            $arr = array('status' => 0, 'msg' => 'failed    查看做饭菜品详情页失败', 'commentList' => $orderComment, 'makeOrderList' => $make_orderList);
            echo json_encode($arr);
        }

    }


    function testlihe($a = null)
    {
        if ($a == 0) {
            echo "我是零";
            return;
        }
        echo "你好";
    }

    // 7.4 购物车

    /**  接口说明 ：7.4.1 加入购物车(一个菜品)======如果是同一个商品点击多次只有数量加1 ，否者就是一个新的购物车订单
     * @action /add_shopCart
     * @method  get
     * @url_test http://localhost/tp/home/index/add_shopCart/user_token/MTg5NDI0MzM5Mjc=/order_id/43/order_title/红烧鱼/order_pic/58f203d365a99.jpg/order_price/100/order_type/1/order_num/3
     * @table Make_order
     * @param  String $user_token
     * @param  int $order_id
     * @param  String $order_title
     * @param  String $order_pic
     * @param  String $order_price
     * @param  String $order_type
     * @param  String $order_num
     * @return array(mk.order_id , mk.order_title ,mk.order_food_description ,mk.order_price ,mk.order_num ,mk.order_pic )
     */

    public function add_shopCart($user_token = null, $order_id = null, $order_title = null, $order_pic = null, $order_price = null, $order_num = null, $order_type = null)
    {
        if (self::tokenIsEmpty($user_token)) {
            if ($order_num == 0) {
                $Shopping_cart = M('Shopping_cart');
                $condition['user_token'] = $user_token;
                $condition['order_id'] = $order_id;
                $condition['_logic'] = 'AND';
                $Shopping_cart->where($condition)->delete();
                $arr = array('status' => 1, 'msg' => ' successful 提交数量是0,已经清理购物车');
                echo json_encode($arr);
                return;
            }
            $Shopping_cart = M('Shopping_cart');
            //使用数组作为查询条件
            $condition['user_token'] = $user_token;
            $condition['order_id'] = $order_id;
            $condition['_logic'] = 'AND';
            $data = M('Shopping_cart')->where($condition)->field('order_id')->find();
//            echo $data['order_id']."是否存在重复的order_id";
            if ($data['order_id'] == $order_id) {
//                echo "已经存在该订单,只更新数量";
                $Shopping_cart->order_num = $order_num;
                $Shopping_cart->order_allPrice = $order_num * $order_price;
                $status = $Shopping_cart->where("user_token='%s'", $user_token)->save();
                if ($status) {
                    $arr = array('status' => 1, 'msg' => 'successful 已经存在该订单,只更新数量和总价');
                    echo json_encode($arr);
                } else {
                    $arr = array('status' => 0, 'msg' => 'failed 已经存在该订单,更新数量和总价失败');
                    echo json_encode($arr);
                }
            } else {
//                echo "正在创建新的购物车订单";
                $Shopping_cart->user_token = $user_token;
                $Shopping_cart->order_id = $order_id;
                $Shopping_cart->order_title = $order_title;
                $Shopping_cart->order_pic = $order_pic;
                $Shopping_cart->order_type = $order_type;
                $Shopping_cart->order_num = $order_num;
                $Shopping_cart->order_price = $order_price;
                $Shopping_cart->order_allPrice = $order_num * $order_price;
                $status = $Shopping_cart->add();
                if ($status) {
                    $arr = array('status' => 1, 'msg' => 'successful 加入购物车成功,正在创建新的购物车订单');
                    echo json_encode($arr);
                } else {
                    $arr = array('status' => 0, 'msg' => 'failed 加入购物车失败，创建新的购物车订单失败');
                    echo json_encode($arr);
                }
            }

        } else {

        }
    }

    /**  接口说明 ：7.4.2 查看购物车
     * @action /look_shopCart
     * @method  get
     * @url_test http://localhost/tp/home/index/look_shopCart/user_token/MTg5NDI0MzM5Mjc=
     * @table Make_order
     * @param  String $user_token
     * @return array( )
     */
    public function look_shopCart($user_token = null)
    {
        if (self::tokenIsEmpty($user_token)) {
//            方法一 用tp 框架的方法
            $Model = M()->table(array('Shopping_cart' => 'sc', 'make_order' => 'mo'));
            $field = 'sc.sc_id , sc.order_id , sc.order_title , sc.order_pic ,sc.order_price ,sc.order_allPrice , sc.order_type, sc.order_num ,mo.order_num AS max_num';
            $condition['sc.user_token'] = $user_token;
            $condition['_string'] = 'sc.order_id = mo.order_id';
            $condition['_logic'] = 'AND';
            $scList = $Model->where($condition)->field($field)->select();

            //方法二 用原生sql语句
            /* $Model = M()->table(array('Shopping_cart' => 'sc', 'make_order' => 'mo'));//或者 $Model = D(); 或者 $Model = M();
 //            只是需要new一个空的模型继承Model中的方法。
             $sql = "SELECT sc.sc_id , sc.order_id , sc.order_title , sc.order_pic ,sc.order_price ,sc.order_allPrice , sc.order_type, sc.order_num ,mo.order_num AS max_num FROM `shopping_cart`  AS sc , make_order  AS  mo  WHERE sc.user_token = '$user_token' AND sc.order_id = mo.order_id";
             $scList = $Model->query($sql);*/

            if ($scList) {
                $arr = array('status' => 1, 'msg' => 'successful 查看购物车成功', 'scList' => $scList);
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => 'failed 查看购物车失败', 'scList' => $scList);
                echo json_encode($arr);
            }
        } else {

        }
    }


    /**  接口说明 ：7.4.3 更新购物车订单数量
     * @action /update_shopCart
     * @method  get
     * @url_test http://localhost/tp/home/index/update_shopCart/sc_id/2/order_num/10/order_price/100
     * @table Make_order
     * @param  String $sc_id
     * @param  String $order_num
     * @param  String $order_price
     * @return array( )
     */
    public
    function update_shopCart($sc_id = null, $order_num = null, $order_price = null)
    {
        if (true) {
            $Shopping_cart = M('Shopping_cart');
            $Shopping_cart->order_num = $order_num;
            $Shopping_cart->order_allPrice = $order_price * $order_num;
            $scList = $Shopping_cart->where("sc_id='%s'", $sc_id)->save();
            if ($scList) {
                $arr = array('status' => 1, 'msg' => 'successful 更新购物车订单数量成功');
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => 'failed 更新购物车订单数量失败');
                echo json_encode($arr);
            }
        } else {

        }
    }




    /**  接口说明 ：7.4.4 删除购物车订单
     * @action /delete_shopCart
     * @method  get
     * @url_test http://localhost/tp/home/index/delete_shopCart/sc_id/4
     * @table Make_order
     * @param  String array $sc_id
     * @return array()
     */
    //$scIdArr是一个连接订单的数组字符串 举例 4next5next6next7 订单号之间用next分割标识有4 5 6 号订单被选中了
    public function delete_shopCart($sc_id = null)
    {
        if (true) {
            $Shopping_cart = M('Shopping_cart');
            $status = $Shopping_cart->where("sc_id=%d", $sc_id)->delete();
            if ($status) {
                $arr = array('status' => 1, 'msg' => 'successful 删除购物车订单成功');
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => 'failed 删除购物车订单失败');
                echo json_encode($arr);
            }
        } else {

        }
    }


    /**  接口说明 ：7.4.5 结算购物车
     * @action /pay_shopCart
     * @method  get
     * @url_test http://localhost/tp/home/index/pay_shopCart/$user_token/MTg5NDI0MzM5Mjc=/address/佛职院/expect_time/232323/orderArrStr
     * @table Make_order
     * @param  String $user_token
     * @param  String $address
     * @param  String $expect_time
     * @param  String $orderArrStr
     * @return array( )
     */

    //$OrderArrInfo="订单编号comma菜名comma图片comma数量comma价钱comma类型period菜名comma图片comma数量comma价钱comma类型period订单编号comma菜名comma图片comma数量comma价钱comma类型")
//43comma红烧鱼comma叉烧图片1comma2comma100comma1period10comma番茄炒蛋2comma番茄炒蛋2comma4comma200comma2

    public function pay_shopCart($user_token = null, $user_pass = null, $address = null, $expect_time = null, $orderArrStr = null)
    {
//        dump($orderArrStr);
        if (true) {
            $status = null;
            $id = null;
            $title = null;
            $pic = null;
            $num = null;
            $price = null;
            $type = null;
            $Food_order = M('Food_order');
            $food = explode('period', $orderArrStr);
            for ($i = 0; $i < count($food) - 1; $i++) {
                $arr = explode('comma', $food[$i]);
                for ($n = 0; $n < count($arr); $n++) {
                    switch ($n) {
                        case 0:
                            $id = $arr[$n];
                            break;
                        case 1:
                            $num = $arr[$n];
                            break;

                    }
                }
                $orderInfo = M('Make_order')->where('order_id = %d', $id)->field('user_token,order_title ,order_price, order_pic, order_type ')->find();//查询厨师的token存入订单数据库
//                dump($orderInfo);
                $cook_user_token = $orderInfo['user_token'];
                $title = $orderInfo['order_title'];
                $pic = $orderInfo['order_pic'];
                $type = $orderInfo['order_type'];
                $price = $orderInfo['order_price'];
                $Food_order->order_id = $id;
                $Food_order->title = $title;
                $Food_order->pic = $pic;
                $Food_order->price = $price;
                $Food_order->all_price = $price * $num;
                $Food_order->num = $num;
                $Food_order->type = $type;
                $Food_order->user_token = $user_token;
                $Food_order->cook_user_token = $cook_user_token;
                $Food_order->address = $address;
                $Food_order->expect_time = $expect_time;
                // 添加购物车 插入订单数据库
                $status = $Food_order->add();
                if ($status) {
//                    同时删除购物车记录
                    M('Shopping_cart')->where('order_id=%d', $id)->delete();
                }

            }
            if ($status) {
                $arr = array('status' => 1, 'msg' => 'successful 结算购物车成功');
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => 'failed 结算购物车失败');
                echo json_encode($arr);
            }
        }
    }



//7.5  轮播图
    /**  接口说明 ：7.5.1获取轮播图广告位
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
            $Ad_photo = M()->table(array('ad_photo' => 'ad', 'make_order' => 'mk', 'user' => 'us'))->where(array('`ad`.order_id = `mk`.order_id', '`us`.user_token = `mk`.user_token'))->field('us.longitude,us.latitude,us.user_name, mk.order_id , mk.order_title ,mk.food_description ,mk.order_price ,mk.star ,mk.order_num,mk.order_type ,mk.order_pic ')->select();
//            $Ad_photo=M()->table(array('ad_photo'=>'ad','make_order'=>'mk'))->where('`ad`.order_id = `mk`.order_id')->field('mk.order_id , mk.order_title ,mk.food_description ,mk.order_price ,mk.order_num ,mk.order_pic ')->select();
            if ($Ad_photo) {
                $arr = array('status' => 1, 'msg' => 'successful 获得所有厨师发布的菜品成功', 'makeOrderList' => $Ad_photo);
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => 'failed 获得所有厨师发布的菜品失败', 'makeOrderList' => $Ad_photo);
                echo json_encode($arr);
            }
        } else {

        }
    }


    /**  接口说明 ：7.6 搜索菜品
     * @action /search_MakeFood
     * @method  get
     * @url_test http://localhost/tp/home/index/search_MakeFood/key/烧鸡    烧鸭    鸡腿
     * @table Make_order
     * @param  String $key
     * @return array(mk.order_id , mk.order_title ,mk.order_food_description ,mk.order_price ,mk.order_num ,mk.order_pic )
     */
//        $noBlank =   preg_replace('# #', '',$key);//去除空格
//        $a =  str_split($noBlank,3);//切割字符串
    public function search_MakeFood($key = '烧鸡 键')
    {
        $str = explode(' ', $key);
        for ($i = 0; $i < count($str); $i++) {


        }
        if (true) {
//            $makeOrder = M()->table(array('user' => 'us', 'make_order' => 'mk'));
//            $condition['order_status'] = 2;
//            $condition['order_title']= array('like','%猪%');
////            $condition['order_title']=array('like',array('%猪%','%红%'),'OR');
//            $condition['_string'] = '`us`.user_token = `mk`.user_token';
//            $condition['_logic'] = 'AND';
//
//            $makeOrderList =$makeOrder->order('order_pub_time desc')->where($condition)->field('us.user_name , mk.order_id , mk.order_title ,mk.food_description ,mk.order_price ,mk.order_num ,mk.order_pic, mk.order_type ')->select();

            //方法二 用原生sql语句
            $Model = M()->table(array('user' => 'us', 'make_order' => 'mk'));//或者 $Model = D(); 或者 $Model = M();
            //            只是需要new一个空的模型继承Model中的方法。
            $fsa = '猪';
            $sql = "SELECT us.longitude ,us.latitude ,us.user_name , mk.order_id , mk.order_title ,mk.food_description ,mk.order_price ,mk.star ,mk.order_num ,mk.order_pic, mk.order_type FROM `make_order` AS mk , user AS us  WHERE mk.order_title LIKE  '%$key%'  AND  `us`.user_token = `mk`.user_token AND `order_status` = 2";
            $makeOrderList = $Model->query($sql);


            if ($makeOrderList) {
                $arr = array('status' => 1, 'msg' => 'successful  搜索菜品成功', 'makeOrderList' => $makeOrderList);
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => 'failed  搜索菜品失败', 'makeOrderList' => $makeOrderList);
                echo json_encode($arr);
            }
        } else {

        }
    }


    /**@declared  接口说明 ：7.5.2更新轮播图广告位 ======(后台接口)
     * @action /get_ad_photo
     * @method  get
     * @url_test http://localhost/tp/home/index/update_adPhoto/
     * @table Make_order
     * @param  $user_token String
     * @return String result
     */
    public function update_adPhoto($user_token)
    {
        if (self::tokenIsEmpty($user_token)) {
            $isManager = M()->where("user_token ='%s'", $user_token)->getfield('is_manager');
            if ($isManager) {

            } else {
                return;
            }
        } else {

        }
    }




    //TODO  4、我的


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
        if (self::tokenIsEmpty($user_token)) {


            $Address_manager = M("Address_manager");
            $Address_manager->user_token = $user_token;
            $Address_manager->name = $name;
            $Address_manager->phone = $phone;
            $Address_manager->area = $area;
            $Address_manager->community = $community;
            $Address_manager->address = $address;
            $status = $Address_manager->add();
            if ($status) {
                $User = M('User');
                $User->user_address = $address;
                $User->where("user_token='%s'", $user_token)->save();//保存地址到用户表的地址中 用于交易
                $arr = array('status' => 1, 'msg' => 'success 添加地址成功');
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => 'failed 添加地址失败');
                echo json_encode($arr);
            }
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
        if (self::tokenIsEmpty($user_token)) {
            $Address_manager = M("Address_manager");
            $addressList = $Address_manager->where("user_token ='%s'", $user_token)->field('id, name , phone , area , community , address ,status ')->select();
            if ($addressList) {
                $arr = array('status' => 1, 'msg' => 'success 显示用户添加的地址成功', 'addressList' => $addressList);
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => 'failed 显示用户添加的地址失败', 'addressList' => $addressList);
                echo json_encode($arr);
            }
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


//8.3 我买到的

//8.3.1共享需求
// TODO 8.3.1 吃饭订单 （买家角色）
    /**@declare 8.3.3 做饭订单 （买家角色）
     *
     * 吃饭类型status 字段特别说明
     *
     * 状态码    执行者和动作------------------买家--------------卖家
     * 0       客户自己取消的订单-------------全部订单---------全部订单
     * 1       客户发布成功的订单-------------待接单-------------无
     * 2       厨师在我要做接单后-------------待送餐-------------进行中
     * 3       客户确认收货的订单待评价--------已完成-------------已完成
     *
     **/


//8.3.2 吃饭订单
    /**  接口说明 ：8.3.2.1 全部订单
     * @action /all_eatOrder
     * @method  get
     * @url_test http://localhost/tp/home/index/cusAllEatOrder/user_token/MTIz/
     * @table Food_order
     * @param  String $user_token
     */
    public function cusAllEatOrder($user_token = null)
    {
        $condition['us.user_token'] = $user_token; // 查询条件 当前用户的token要等于用户的token
        $condition['eo.user_token'] = $user_token; // 查询条件  当前用户的token要等于订单厨师token
        $condition['_logic'] = 'AND';
        $cusAllEatList = M()->table(array('user' => 'us', 'eat_order' => 'eo'))->where($condition)->
        field('eo.order_status  , eo.order_id ,  eo.order_title , eo.eatStrArr , eo.order_price , eo.order_type , eo.order_note , eo.order_res_time, eo.order_pub_time , us.user_name , us.user_phone , us.user_address')->select();
        if ($cusAllEatList) {
            $arr = array('status' => 1, 'msg' => 'success 查询全部吃饭订单成功', 'cusAllEatList' => $cusAllEatList);
            echo json_encode($arr);
        } else {
            $arr = array('status' => 0, 'msg' => 'failed 查询全部吃饭订单失败', 'cusAllEatList' => $cusAllEatList);
            echo json_encode($arr);
        }
    }


    /**  接口说明 ：8.3.2.2 待接单
     * @action /cusWaitEatOrder
     * @method  get
     * @url_test http://localhost/tp/home/index/cusWaitEatOrder/user_token/MTg5NDI0MzM5Mjc=/
     * @table Food_order
     * @param  String $user_token
     */
    public function cusWaitEatOrder($user_token = null)
    {
        $condition['us.user_token'] = $user_token; // 查询条件 当前用户的token要等于用户的token
        $condition['eo.user_token'] = $user_token; // 查询条件  当前用户的token要等于订单厨师token
        $condition['eo.order_status'] = 1;                    //状态码是  1  待接单
        $condition['_logic'] = 'AND';
        $cusWaitEatList = M()->table(array('user' => 'us', 'eat_order' => 'eo'))->where($condition)->
        field('eo.order_status  , eo.order_id ,  eo.order_title , eo.eatStrArr , eo.order_price , eo.order_type , eo.order_note , eo.order_res_time, eo.order_expect_time , eo.order_pub_time , us.user_name , us.user_phone , us.user_address')->select();
        if ($cusWaitEatList) {
            $arr = array('status' => 1, 'msg' => 'success 查询待接单吃饭订单成功', 'cusWaitEatList' => $cusWaitEatList);
            echo json_encode($arr);
        } else {
            $arr = array('status' => 0, 'msg' => 'failed 查询待接单吃饭订单失败', 'cusWaitEatList' => $cusWaitEatList);
            echo json_encode($arr);
        }
    }


    /**  接口说明 ：8.3.2.3 待送餐
     * @action /cusDeliveryEatOrder
     * @method  get
     * @url_test http://localhost/tp/home/index/cusDeliveryEatOrder/user_token/MTg5NDI0MzM5Mjc=/
     * @table Food_order
     * @param  String $user_token
     */
    public function cusDeliveryEatOrder($user_token = null)
    {
        $condition['us.user_token'] = $user_token; // 查询条件 当前用户的token要等于用户的token
        $condition['eo.user_token'] = $user_token; // 查询条件  当前用户的token要等于订单厨师token
        $condition['eo.order_status'] = 2;                    //状态码是  2  待送餐
        $condition['_logic'] = 'AND';
        $cusDeliveryEatList = M()->table(array('user' => 'us', 'eat_order' => 'eo'))->where($condition)->
        field('eo.order_status  , eo.order_id ,  eo.order_title , eo.eatStrArr , eo.order_price , eo.order_type , eo.order_note , eo.order_res_time, eo.order_expect_time , eo.order_pub_time , us.user_name , us.user_phone , us.user_address')->select();
        if ($cusDeliveryEatList) {
            $arr = array('status' => 1, 'msg' => 'success 查询待送餐吃饭订单成功', 'cusDeliveryEatList' => $cusDeliveryEatList);
            echo json_encode($arr);
        } else {
            $arr = array('status' => 0, 'msg' => 'failed 查询待送餐吃饭订单失败', 'cusDeliveryEatList' => $cusDeliveryEatList);
            echo json_encode($arr);
        }
    }


    /**  接口说明 ：8.3.2.4 已完成
     * @action /cusFinishEatOrder
     * @method  get
     * @url_test http://localhost/tp/home/index/cusFinishEatOrder/user_token/MTg5NDI0MzM5Mjc=/
     * @table Food_order
     * @param  String $user_token
     */
    public function cusFinishEatOrder($user_token = null)
    {
        $condition['us.user_token'] = $user_token; // 查询条件 当前用户的token要等于用户的token
        $condition['eo.user_token'] = $user_token; // 查询条件  当前用户的token要等于订单厨师token
        $condition['_string'] = 'eo.order_status = 3  '; //已完成订单状态为 3
        $condition['_logic'] = 'AND';
        $cusFinishEatList = M()->table(array('user' => 'us', 'eat_order' => 'eo'))->where($condition)->
        field('eo.order_status  , eo.order_id ,  eo.order_title , eo.eatStrArr , eo.order_price , eo.order_type , eo.order_note , eo.order_res_time, eo.order_expect_time , eo.order_pub_time , us.user_name , us.user_phone , us.user_address')->select();
        if ($cusFinishEatList) {
            $arr = array('status' => 1, 'msg' => 'success 查询待送餐吃饭订单成功', 'cusFinishEatList' => $cusFinishEatList);
            echo json_encode($arr);
        } else {
            $arr = array('status' => 0, 'msg' => 'failed 查询待送餐吃饭订单失败', 'cusFinishEatList' => $cusFinishEatList);
            echo json_encode($arr);
        }
    }


    /**  接口说明 ：8.3.2.5 取消订单
     * @action /cusCancelEatOrder
     * @method  get
     * @url_test http://localhost/tp/home/index/cusCancelEatOrder/user_token/MTg5NDI0MzM5Mjc=/order_id/
     * @table Food_order
     * @param  String $user_token
     * @param  String $order_id
     */
    public function cusCancelEatOrder($user_token = null, $order_id = null)
    {


        if (self::tokenIsEmpty($user_token)) {

            $Eat_order = M('Eat_order');
            $Eat_order->order_status = 0;
            $status = $Eat_order->where('order_id = %d ', $order_id)->save();
            if ($status) {
                $arr = array('status' => 1, 'msg' => 'success 查询待送餐吃饭订单成功');
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => 'failed 查询待送餐吃饭订单失败');
                echo json_encode($arr);
            }
        } else {


        }
    }


    /**  接口说明 ：8.3.2.6 确认收货
     * @action /cusConfirmEatOrder
     * @method  get
     * @url_test http://localhost/tp/home/index/cusConfirmEatOrder/user_token/MTg5NDI0MzM5Mjc=/order_id/
     * @table Food_order
     * @param  String $user_token
     * @param  String $order_id
     */
    public function cusConfirmEatOrder($user_token = null, $order_id = null)
    {


        if (self::tokenIsEmpty($user_token)) {

            $Eat_order = M('Eat_order');
            $Eat_order->order_status = 3;
            $status = $Eat_order->where('order_id = %d ', $order_id)->save();
            if ($status) {
                $arr = array('status' => 1, 'msg' => 'success 确认收货吃饭订单成功');
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => 'failed 确认收货吃饭订单失败');
                echo json_encode($arr);
            }
        } else {


        }
    }


    /**  接口说明 ：8.3.2.7 评价订单
     * @action /cusCommentEatOrder
     * @method  get
     * //     * @url_test http://localhost/tp/home/index/cusCommentEatOrder/user_token/MTg5NDI0MzM5Mjc=/order_id/59/comment/老板做的饭很好吃哦
     * @table Food_order
     * @param  String $user_token
     * @param  String $order_id
     * @param  String $comment
     */
    public function cusCommentEatOrder($user_token = null, $order_id = null, $comment = null)
    {
        if (self::tokenIsEmpty($user_token)) {

            $Eat_order = M('Eat_order');
//            $Eat_order->order_status = 5;
            $Eat_order->order_comment = $comment;
            $status = $Eat_order->where('order_id = %d ', $order_id)->save();
            if ($status) {
                $arr = array('status' => 1, 'msg' => 'success 卖家评价订单成功');
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => 'failed 卖家评价订单失败');
                echo json_encode($arr);
            }
        } else {


        }

    }


    /**  接口说明 ：8.3.2.8 买家查看吃饭订单评价
     * @action /cusLookCommentEatOrder
     * @method  get
     * @url_test http://localhost/tp/home/index/cusLookCommentEatOrder/user_token/MTg5NDI0MzM5Mjc=/order_id/59/
     * @table Food_order
     * @param  String $user_token
     * @param  String $order_id
     */
    public function cusLookCommentEatOrder($user_token = null, $order_id = null)
    {
        if (self::tokenIsEmpty($user_token)) {
            //买家自己评论
            $model = M()->table(array('user' => 'us', 'Eat_order' => 'eo'));
            $condition['us.user_token'] = $user_token; // 查询条件 当前用户的token要等于用户的token
            $condition['eo.order_id'] = $order_id; // 查询条件  当前用户的token要等于订单厨师token
            $condition['_logic'] = 'AND';
            $field = 'us.user_name , us.user_icon , eo.order_comment ';
            $cusComments = $model->where($condition)->field($field)->find();
//            if ($cusComments['order_comment'] == null  && $cusComments['order_comment'] == '' ) {
//                $cusComments = null;
//            }

            //卖家的评论
            $cook_token = M('Eat_order')->where('order_id=%d', $order_id)->getfield('cook_user_token');
            $model = M()->table(array('user' => 'us', 'Eat_order' => 'eo'));
            $condition['us.user_token'] = $cook_token; // 查询条件 当前用户的token要等于用户的token
            $condition['eo.order_id'] = $order_id; // 查询条件  当前用户的token要等于订单厨师token
            $condition['_logic'] = 'AND';
            $field = 'us.user_name , us.user_icon , eo.order_Cookcomment ';
            $cookComments = $model->where($condition)->field($field)->find();
//            if ($cookComments['order_cookcomment'] == null  && $cookComments['order_cookcomment'] == '' ) {
//                $cookComments = null;
//            }

            if ($cusComments || $cookComments) {
                $arr = array('status' => 1, 'msg' => 'success 卖家评价订单成功', 'cusComment' => $cusComments, 'cookComment' => $cookComments);
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => 'failed 卖家评价订单失败');
                echo json_encode($arr);
            }
        } else {


        }

    }



//    /**  接口说明 ：8.3.2.6 查看详情
//     * @action /cusConfirmEatOrder
//     * @method  get
//     * @url_test http://localhost/tp/home/index/cusConfirmEatOrder/user_token/MTg5NDI0MzM5Mjc=/order_id/
//     * @table Food_order
//     * @param  String $user_token
//     * @param  String $order_id
//     */
//    public function cusLookEatOrder($user_token = null, $order_id = null)
//    {
//
//
//        if (self::tokenIsEmpty($user_token)) {
//
//            $Eat_order = M('Eat_order');
//            $Eat_order->order_status = 3;
//            $status = $Eat_order->where('order_id = %d ', $order_id)->save();
//            if ($status) {
//                $arr = array('status' => 1, 'msg' => 'success 确认收货吃饭订单成功');
//                echo json_encode($arr);
//            } else {
//                $arr = array('status' => 0, 'msg' => 'failed 确认收货吃饭订单失败');
//                echo json_encode($arr);
//            }
//        } else {
//
//
//        }
//    }


    // todo   8.3.3 做饭订单（买家角色）
    /**  接口说明 ：8.3.3.1 全部订单
     * @action /cusAllMakeOrder
     * @method  get
     * @url_test http://localhost/tp/home/index/cusAllMakeOrder/user_token/MTIz/
     * @table Food_order
     * @param  String $user_token
     */
    public function cusAllMakeOrder($user_token = null)
    {
        $condition['us.user_token'] = $user_token; // 查询条件 当前用户的token要等于用户的token
        $condition['fo.user_token'] = $user_token; // 查询条件  当前用户的token要等于订单厨师token
        $condition['_logic'] = 'AND';
        $cusAllEatList = M()->table(array('user' => 'us', 'food_order' => 'fo'))->where($condition)->
        field('fo.status , fo.id , fo.order_id ,  fo.title , fo.pic , fo.price ,fo.all_price , fo.num , fo.type ,  fo.expect_time , fo.pub_time, us.user_name , us.user_phone , us.user_address')->select();


        /*        $condition['fo.user_token'] = $user_token; // 查询条件   所有该用户的订单都查询出来 fo.user_token = MTg5NDI0MzM5Mjc=   AND  fo.user_token = us.user_token
        //        $condition['us.user_token'] = $user_token; // 查询条件   所有该用户的订单都查询出来
                $condition['_string'] = '`fo.user_token`= `fo.user_token`'; // 查询条件   所有该用户的订单都查询出来
                $condition['_logic'] = 'AND';

                $makeOrderList = M()->table(array('user' => 'us', 'food_order' => 'fo'))->where($condition)->
        //        $makeOrderList = M()->table(array('user' => 'us', 'food_order' => 'fo'))->where($condition)->
                field('fo.status , fo.id , fo.order_id ,  fo.title , fo.pic , fo.price ,fo.all_price , fo.num , fo.type ,  fo.expect_time  , us.user_name , us.user_phone , us.user_address')->select();
        //        echo $makeOrderList;
                $Model = D();//或者 $Model = D(); 或者 $Model = M();
                $sql = "select * from `user`";
                $voList = $Model->query($sql);*/

        if ($cusAllEatList) {
            $arr = array('status' => 1, 'msg' => 'success 查询全部订单成功', 'cusAllMakeList' => $cusAllEatList);
            echo json_encode($arr);
        } else {
            $arr = array('status' => 0, 'msg' => 'failed 查询全部订单失败', 'cusAllMakeList' => $cusAllEatList);
            echo json_encode($arr);
        }
    }


    /**  接口说明 ：8.3.3.2 待接单
     * @action /cusWaitMakeOrder
     * @method  get
     * @url_test http://localhost/tp/home/index/cusWaitMakeOrder/user_token/MTg5NDI0MzM5Mjc=/
     * @table eat_order  &&  make_order
     * @param  String $user_token
     */
    public function cusWaitMakeOrder($user_token = null)
    {
        $condition['us.user_token'] = $user_token; // 查询条件 当前用户的token要等于用户表的token
        $condition['fo.user_token'] = $user_token; // 查询条件  当前用户的token要等于订单厨师token
        $condition['fo.status'] = 1;
        $condition['_logic'] = 'AND';
        $cusEatWaitList = M()->table(array('user' => 'us', 'food_order' => 'fo'))->where($condition)->
        field('fo.status , fo.id , fo.order_id ,  fo.title , fo.pic , fo.price ,fo.all_price , fo.num , fo.type ,  fo.expect_time  ,  fo.pub_time,  us.user_name , us.user_phone , us.user_address')->select();

        if ($cusEatWaitList) {
            $arr = array('status' => 1, 'msg' => 'success 查询全部订单成功', 'cusEatWaitList' => $cusEatWaitList);
            echo json_encode($arr);
        } else {
            $arr = array('status' => 0, 'msg' => 'failed 查询全部订单失败', 'cusEatWaitList' => $cusEatWaitList);
            echo json_encode($arr);
        }
    }


    /**  接口说明 ：8.3.3.3 代送餐
     * @action /cusDeliveryMakeOrder
     * @method  get
     * @url_test http://localhost/tp/home/index/cusDeliveryMakeOrder/user_token/MTg5NDI0MzM5Mjc=/
     * @table eat_order  &&  make_order
     * @param  String $user_token
     */
    public function cusDeliveryMakeOrder($user_token = null)
    {
        $condition['us.user_token'] = $user_token; // 查询条件 当前用户的token要等于用户表的token
        $condition['fo.user_token'] = $user_token; // 查询条件  当前用户的token要等于订单厨师token
        $condition['fo.status'] = 2; // 状态为2的订单
        $condition['_logic'] = 'AND';
        $eatDeliveryOrderList = M()->table(array('user' => 'us', 'food_order' => 'fo'))->where($condition)->
        field('fo.status , fo.id , fo.order_id ,  fo.title , fo.pic , fo.price ,fo.all_price , fo.num , fo.type ,  fo.expect_time  ,   fo.pub_time, us.user_name , us.user_phone , us.user_address')->select();

        if ($eatDeliveryOrderList) {
            $arr = array('status' => 1, 'msg' => 'success 查询代送餐成功', 'deliveryOrderList' => $eatDeliveryOrderList);
            echo json_encode($arr);
        } else {
            $arr = array('status' => 0, 'msg' => 'failed 查询代送餐失败', 'deliveryOrderList' => $eatDeliveryOrderList);
            echo json_encode($arr);
        }
    }


    /**  接口说明 ：8.3.3.4 已完成
     * @action /cusFinishMakeOrder
     * @method  get
     * @url_test http://localhost/tp/home/index/cusFinishMakeOrder/user_token/MTg5NDI0MzM5Mjc=/
     * @table eat_order  &&  make_order
     * @param  String $user_token
     */
    public function cusFinishMakeOrder($user_token = null)
    {
        $condition['us.user_token'] = $user_token; // 查询条件 当前用户的token要等于用户的token
        $condition['fo.user_token'] = $user_token; // 查询条件  当前用户的token要等于订单厨师token
        $condition['_string'] = 'fo.status = 3  OR fo.status = 4'; //订单状态为 3 或 4的订单
        $condition['_logic'] = 'AND';
        $eatFinishOrderList = M()->table(array('user' => 'us', 'food_order' => 'fo'))->where($condition)->
        field('fo.status , fo.id , fo.order_id ,  fo.title , fo.pic , fo.price ,fo.all_price , fo.num , fo.type ,  fo.expect_time  ,  fo.pub_time,  us.user_name , us.user_phone , us.user_address')->select();
        if ($eatFinishOrderList) {
            $arr = array('status' => 1, 'msg' => 'success 查询已完成成功', 'finishOrderList' => $eatFinishOrderList);
            echo json_encode($arr);
        } else {
            $arr = array('status' => 0, 'msg' => 'failed 查询已完成失败', 'finishOrderList' => $eatFinishOrderList);
            echo json_encode($arr);
        }
    }


    /**  接口说明 ：8.3.3.5 确认收货
     * @action /cusConfirmMakeOrder
     * @method  get
     * @url_test http://localhost/tp/home/index/cusConfirmMakeOrder/user_token/MTg5NDI0MzM5Mjc=/id/
     * @table eat_order  &&  make_order
     * @param  String $user_token
     * @param  String $id
     */
    public function cusConfirmMakeOrder($user_token = null, $id = null)
    {

        if (self::tokenIsEmpty($user_token)) {
            $Food_order = M('Food_order');
            $Food_order->status = 3;
            $status = $Food_order->where('id=%d', $id)->save();
            if ($status) {
                $arr = array('status' => 1, 'msg' => 'success 确认收货成功');
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => 'failed 确认收货失败');
                echo json_encode($arr);
            }
        } else {

        }
    }


    /**  接口说明 ：8.3.3.6 客户评价订单
     * @action /cusCommentMakeOrder
     * @method  get
     * @url_test http://localhost/tp/home/index/cusCommentMakeOrder/user_token/MTg5NDI0MzM5Mjc=/id/3/order_id/43/comment/老板真的好棒呀第二次评价/comment_describe/3/comment_service/5/comment_taste/4
     * @table eat_order  &&  make_order
     * @param  String $user_token
     * @param  String $order_id
     * @param  String $id
     * @param  String $comment
     * @param  String $comment_describe
     * @param  String $comment_service
     * @param  String $comment_taste
     */
    public function cusCommentMakeOrder($user_token = null, $order_id, $id = null, $comment = null, $comment_describe = null, $comment_service = null, $comment_taste = null)
    {

        if (self::tokenIsEmpty($user_token)) {
            $Food_order = M('Food_order');
            $Food_order->status = 4;
            $status1 = $Food_order->where('id=%d', $id)->save();
            if ($status1) {
                $average = ($comment_describe + $comment_service + $comment_taste) / 3;     //平均评分
                $Order_comment = M('Order_comment');
                $Order_comment->id = $id;
                $Order_comment->order_id = $order_id;
                $Order_comment->user_token = $user_token;
                $Order_comment->comment_content = $comment;
                $Order_comment->comment_describe = $comment_describe;
                $Order_comment->comment_service = $comment_service;
                $Order_comment->comment_taste = $comment_taste;
                $Order_comment->average = $average;
                $status = $Order_comment->add();
                if ($status) {
                    $arr = array('status' => 1, 'msg' => 'success 评价订单成功');
                    echo json_encode($arr);
                } else {
                    $arr = array('status' => 0, 'msg' => 'failed 评价订单失败');
                    echo json_encode($arr);
                }
            }

        } else {

        }
    }

    /**  接口说明 ：8.3.3.7 客户查看评价
     * @action /cusLookCommentMakeOrder
     * @method  get
     * @url_test http://localhost/tp/home/index/cusLookCommentMakeOrder/user_token/MTg5NDI0MzM5Mjc=/id/2
     * @table eat_order  &&  make_order
     * @param  String $user_token
     * @param  String $id
     */
    public function cusLookCommentMakeOrder($user_token = null, $id = null)
    {

        if (self::tokenIsEmpty($user_token)) {
            $u_token = M('Order_comment')->where('id=%d', $id)->getfield('user_token');
            $model = M()->table(array('order_comment' => 'oc', 'user' => 'us'));
            $condition['us.user_token'] = $u_token;
            $condition['oc.id'] = $id;
            $condition['_logic'] = 'AND';
            $field = ' oc.comment_content , oc.comment_describe ,oc.comment_service , oc.comment_taste';
            $comment = $model->where($condition)->field($field)->find();
            if ($comment) {
                $arr = array('status' => 1, 'msg' => 'success 客户查看评价成功', 'comment' => $comment);
                echo json_encode($arr);
            } else {
                $arr = array('status' => 1, 'msg' => 'failed 客户查看评价失败', 'comment' => $comment);
                echo json_encode($arr);
            }
        } else {

        }
    }






//8.4 我卖出的


//8.4.1共享需求


// TODO 8.4.2 吃饭订单 （卖家角色）
    /**@declare 8.4.3 吃饭订单吃饭类型status 字段特别说明=====================
     *                                                                    =
     * 状态码           执行者和动作            买家         卖家                  =
     * 0         客户自己取消的订单          全部订单       全部订单                =
     * 1         客户发布成功的订单          待接单             无                   =
     * 2        厨师在我要做接单后          待送餐           进行中                  =
     * =
     * =
     * 3        客户确认收货的订单待评价    已完成          已完成                   =
     * 4        客户评价之后的订单已评价                                          =
     *
     *
     * =====================================================================
     **/

    /**  接口说明 ：8.4.2.1 全部订单
     * @action /cookAllEatOrder
     * @method  get
     * @url_test http://localhost/tp/home/index/cookAllEatOrder/user_token/MTIz/
     * @table Food_order
     * @param  String $user_token
     */
    public function cookAllEatOrder($user_token = null)
    {
        $condition['us.user_token'] = $user_token; // 查询条件 当前用户的token要等于用户的token
        $condition['eo.cook_user_token'] = $user_token; // 查询条件  当前用户的token要等于订单厨师token
        $condition['_logic'] = 'AND';
        $cookAllEatList = M()->table(array('user' => 'us', 'eat_order' => 'eo'))->where($condition)->
        field('eo.order_status  , eo.order_id ,  eo.order_title , eo.eatStrArr , eo.order_price , eo.order_type , eo.order_note , eo.order_res_time, eo.order_expect_time , eo.order_pub_time , us.user_name , us.user_phone , us.user_address')->select();
        if ($cookAllEatList) {
            $arr = array('status' => 1, 'msg' => 'success 查询全部吃饭订单成功', 'cookAllEatList' => $cookAllEatList);
            echo json_encode($arr);
        } else {
            $arr = array('status' => 0, 'msg' => 'failed 查询全部吃饭订单失败', 'cookAllEatList' => $cookAllEatList);
            echo json_encode($arr);
        }
    }

    /**  接口说明 ：8.4.2.2 已弃用*/

    /**  接口说明 ：8.4.2.3 进行中
     * @action /cookingEatOrder
     * @method  get
     * @url_test http://localhost/tp/home/index/cookingEatOrder/user_token/MTg5NDI0MzM5Mjc=/
     * @table Food_order
     * @param  String $user_token
     */
    public function cookingEatOrder($user_token = null)
    {
        $condition['us.user_token'] = $user_token; // 查询条件 当前用户的token要等于用户的token
        $condition['eo.cook_user_token'] = $user_token; // 查询条件  当前用户的token要等于订单厨师token
        $condition['eo.order_status'] = 2;                    //状态码是  2  待送餐
        $condition['_logic'] = 'AND';
        $cookDeliveryEatList = M()->table(array('user' => 'us', 'eat_order' => 'eo'))->where($condition)->
        field('eo.order_status  , eo.order_id ,  eo.order_title , eo.eatStrArr , eo.order_price , eo.order_type , eo.order_note , eo.order_res_time, eo.order_expect_time , eo.order_pub_time , us.user_name , us.user_phone , us.user_address')->select();
        if ($cookDeliveryEatList) {
            $arr = array('status' => 1, 'msg' => 'success 查询待送餐吃饭订单成功', 'cookDeliveryEatList' => $cookDeliveryEatList);
            echo json_encode($arr);
        } else {
            $arr = array('status' => 0, 'msg' => 'failed 查询待送餐吃饭订单失败', 'cookDeliveryEatList' => $cookDeliveryEatList);
            echo json_encode($arr);
        }
    }


    /**  接口说明 ：8.4.2.4 已完成
     * @action /cookFinishEatOrder
     * @method  get
     * @url_test http://localhost/tp/home/index/cookFinishEatOrder/user_token/MTg5NDI0MzM5Mjc=/
     * @table Food_order
     * @param  String $user_token
     */
    public function cookFinishEatOrder($user_token = null)
    {
        $condition['us.user_token'] = $user_token; // 查询条件 当前用户的token要等于用户的token
        $condition['eo.cook_user_token'] = $user_token; // 查询条件  当前用户的token要等于订单厨师token
        $condition['_string'] = 'eo.order_status = 3  '; //已完成订单状态为 3
        $condition['_logic'] = 'AND';
        $cookFinishEatList = M()->table(array('user' => 'us', 'eat_order' => 'eo'))->where($condition)->
        field('eo.order_status  , eo.order_id ,  eo.order_title , eo.eatStrArr , eo.order_price , eo.order_type , eo.order_note , eo.order_res_time, eo.order_expect_time , eo.order_pub_time ,us.user_name , us.user_phone , us.user_address')->select();
        if ($cookFinishEatList) {
            $arr = array('status' => 1, 'msg' => 'success 查询待送餐吃饭订单成功', 'cookFinishEatList' => $cookFinishEatList);
            echo json_encode($arr);
        } else {
            $arr = array('status' => 0, 'msg' => 'failed 查询待送餐吃饭订单失败', 'cookFinishEatList' => $cookFinishEatList);
            echo json_encode($arr);
        }
    }


    /**  接口说明 ：8.4.2.5 卖家评价订单
     * @action /cookCommentEatOrder
     * @method  get
     * @url_test http://localhost/tp/home/index/cookCommentEatOrder/user_token/MTIzNA==/order_id/59/comment/这位客户人也很好噢
     * @table Food_order
     * @param  String $user_token
     * @param  String $order_id
     * @param  String $comment
     */
    public function cookCommentEatOrder($user_token = null, $order_id = null, $comment = null)
    {
        if (self::tokenIsEmpty($user_token)) {

            $Eat_order = M('Eat_order');
//            $Eat_order->order_status = 5;
            $Eat_order->order_Cookcomment = $comment;
            $status = $Eat_order->where('order_id = %d ', $order_id)->save();
            if ($status) {
                $arr = array('status' => 1, 'msg' => 'success 卖家评价订单成功');
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => 'failed 卖家评价订单失败');
                echo json_encode($arr);
            }
        } else {


        }
    }


    /**  接口说明 ：8.4.2.6 卖家查看吃饭订单评价
     * @action /cookLookCommentEatOrder
     * @method  get
     * @url_test http://localhost/tp/home/index/cookLookCommentEatOrder/user_token/MTIzNA==/order_id/59/
     * @table Food_order
     * @param  String $user_token
     * @param  String $order_id
     */
    public function cookLookCommentEatOrder($user_token = null, $order_id = null)
    {
        if (self::tokenIsEmpty($user_token)) {
            //卖家自己评论
            $model = M()->table(array('user' => 'us', 'Eat_order' => 'eo'));
            $condition['us.user_token'] = $user_token; // 查询条件 当前用户的token要等于用户的token
            $condition['eo.order_id'] = $order_id; // 查询条件  当前用户的token要等于订单厨师token
            $condition['_logic'] = 'AND';
            $field = 'us.user_name , us.user_icon , eo.order_Cookcomment ';
            $cookComments = $model->field($field)->where($condition)->find();
//            if ($cookComments['order_comment'] == null  && $cookComments['order_comment'] == '' ) {
//                $cookComments = null;
//            }

            //买家的评论
            $cook_token = M('Eat_order')->where('order_id=%d', $order_id)->getfield('user_token');

//            echo $user_token .'厨师的token';
//            echo $cook_token .'客户的token';
            $model = M()->table(array('user' => 'us', 'Eat_order' => 'eo'));
            $condition['us.user_token'] = $cook_token; // 查询条件 当前用户的token要等于用户的token
            $condition['eo.order_id'] = $order_id; // 查询条件  当前用户的token要等于订单厨师token
            $condition['_logic'] = 'AND';
            $field = 'us.user_name , us.user_icon , eo.order_comment ';
            $cusComments = $model->where($condition)->field($field)->find();
//            if ($cusComments['order_comment'] == null  && $cusComments['order_comment'] == '' ) {
//                $cusComments = null;
//            }


//
            if ($cusComments) {
                $arr = array('status' => 1, 'msg' => 'success 卖家评价订单成功', 'cusComment' => $cusComments, 'cookComment' => $cookComments);
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => 'failed 卖家评价订单失败');
                echo json_encode($arr);
            }
        } else {


        }

    }















// TODO 8.4.3 做饭订单 （卖家角色）
    /**@declare 8.4.3 做饭订单
     * =============================================================
     * 做饭类型status 字段特别说明
     *
     * 状态码        执行者和动作            买家           卖家
     * 0    厨师自己取消的订单          全部订单         全部订单
     * 1    客户下单下单成功的订单      待接单             待同意
     * 2    厨师同意的订单单             待送餐           进行中
     *
     * 3    客户确认收货的订单待评价    已完成             已完成
     * 4    客户评价之后的订单已评价
     *
     *================================================================
     **/


    /**  接口说明 ：8.4.3.1 全部订单
     * @action /cookAllMakeOrder
     * @method  get
     * @url_test http://localhost/tp/home/index/cookAllMakeOrder/user_token/MTIz/
     * @table Food_order
     * @param  String $user_token
     */


    public function cookAllMakeOrder($user_token = null)
    {
        $condition['us.user_token'] = $user_token; // 查询条件 当前用户的token要等于用户的token
        $condition['fo.cook_user_token'] = $user_token; // 查询条件  当前用户的token要等于订单厨师token
        $condition['_logic'] = 'AND';
        $cookAllMakeList = M()->table(array('user' => 'us', 'food_order' => 'fo'))->where($condition)->
        field('fo.status , fo.id , fo.order_id ,  fo.title , fo.pic , fo.price ,fo.all_price , fo.num , fo.type ,  fo.expect_time  , fo.pub_time,  us.user_name , us.user_phone , us.user_address')->select();

        if ($cookAllMakeList) {
            $arr = array('status' => 1, 'msg' => 'success 查询全部订单成功', 'cookAllMakeList' => $cookAllMakeList);
            echo json_encode($arr);
        } else {
            $arr = array('status' => 0, 'msg' => 'failed 查询全部订单失败', 'cookAllMakeList' => $cookAllMakeList);
            echo json_encode($arr);
        }
    }


    /**  接口说明 ：8.4.3.2 待同意
     * @action /cookWaitMakeOrder
     * @method  get
     * @url_test http://localhost/tp/home/index/cookWaitMakeOrder/user_token/MTIz/
     * @table eat_order  &&  make_order
     * @param  String $user_token 卖家用户token
     */
    public function cookWaitMakeOrder($user_token = null)
    {
        $condition['us.user_token'] = $user_token; // 查询条件 当前用户的token要等于用户的token
        $condition['fo.cook_user_token'] = $user_token; // 查询条件  当前用户的token要等于订单厨师token
        $condition['fo.status'] = 1; //订单状态为 1
        $condition['_logic'] = 'AND';
        $cookWaitMakeList = M()->table(array('food_order' => 'fo', 'user' => 'us'))->where($condition)->
        field('fo.status , fo.id , fo.order_id ,  fo.title , fo.pic , fo.price ,fo.all_price , fo.num , fo.type ,  fo.expect_time  ,  fo.pub_time, us.user_name , us.user_phone , us.user_address')->select();
        if ($cookWaitMakeList) {
            $arr = array('status' => 1, 'msg' => 'success 查询全部订单成功', 'cookWaitMakeList' => $cookWaitMakeList);
            echo json_encode($arr);
        } else {
            $arr = array('status' => 0, 'msg' => 'failed 查询全部订单失败', 'cookWaitMakeList' => $cookWaitMakeList);
            echo json_encode($arr);
        }
    }


    /**  接口说明 ：8.4.3.3 进行中
     * @action /cookingMakeOrder
     * @method  get
     * @url_test http://localhost/tp/home/index/cookingMakeOrder/user_token/MTIz/
     * @table eat_order  &&  make_order
     * @param  String $user_token
     */
    public function cookingMakeOrder($user_token = null)
    {
        $condition['us.user_token'] = $user_token; // 查询条件 当前用户的token要等于用户的token
        $condition['fo.cook_user_token'] = $user_token; // 查询条件  当前用户的token要等于订单厨师token
        $condition['fo.status'] = 2; //订单状态为 2
        $condition['_logic'] = 'AND';
        $cookDeliveryMakeList = M()->table(array('user' => 'us', 'food_order' => 'fo'))->where($condition)->
        field('fo.status , fo.id , fo.order_id ,  fo.title , fo.pic , fo.price ,fo.all_price , fo.num , fo.type ,  fo.expect_time  , fo.pub_time,  us.user_name , us.user_phone , us.user_address')->select();
        if ($cookDeliveryMakeList) {
            $arr = array('status' => 1, 'msg' => 'success 查询代送餐成功', 'cookDeliveryMakeList' => $cookDeliveryMakeList);
            echo json_encode($arr);
        } else {
            $arr = array('status' => 0, 'msg' => 'failed 查询代送餐失败', 'cookDeliveryMakeList' => $cookDeliveryMakeList);
            echo json_encode($arr);
        }
    }


    /**  接口说明 ：8.4.3.4 已完成
     * @action /cookFinishMakeOrder
     * @method  get
     * @url_test http://localhost/tp/home/index/cookFinishMakeOrder/user_token/MTIz/
     * @table eat_order  &&  make_order
     * @param  String $user_token
     */
    public function cookFinishMakeOrder($user_token = null)
    {
        $condition['us.user_token'] = $user_token; // 查询条件 当前用户的token要等于用户的token
        $condition['fo.cook_user_token'] = $user_token; // 查询条件  当前用户的token要等于订单厨师tokenhttp://localhost/tp/home/index/cookFinishMakeOrder/user_token/MTg5NDI0MzM5Mjc=/
        $condition['_string'] = 'fo.status = 3  OR fo.status = 4'; //订单状态为 3 或 4的订单
        $condition['_logic'] = 'AND';
        $cookFinishMakeList = M()->table(array('user' => 'us', 'food_order' => 'fo'))->where($condition)->
        field('fo.status , fo.id , fo.order_id ,  fo.title , fo.pic , fo.price ,fo.all_price , fo.num , fo.type ,  fo.expect_time  , fo.pub_time,  us.user_name , us.user_phone , us.user_address')->select();
        if ($cookFinishMakeList) {
            $arr = array('status' => 1, 'msg' => 'success 查询已完成成功', 'cookFinishMakeList' => $cookFinishMakeList);
            echo json_encode($arr);
        } else {
            $arr = array('status' => 0, 'msg' => 'failed 查询已完成失败', 'cookFinishMakeList' => $cookFinishMakeList);
            echo json_encode($arr);
        }
    }


    /**  接口说明 ：8.4.3.5 卖家接受订单
     * @action /acceptMakeOrder
     * @method  get
     * @url_test http://localhost/tp/home/index/acceptMakeOrder/user_token/MTIz/id/3
     * @table eat_order  &&  make_order
     * @param  String $user_token
     * @param  String $id
     */
    public function acceptMakeOrder($user_token = null, $id = null)

    {
        if (self::tokenIsEmpty($user_token)) {
            $Food_order = M('Food_order');
            $Food_order->status = 2;
            $status = $Food_order->where('id=%d', $id)->save();
            if ($status) {
                $arr = array('status' => 1, 'msg' => 'success 卖家接受订单成功');
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => 'failed 卖家接受订单失败');
                echo json_encode($arr);
            }
        }

    }


    /**  接口说明 ：8.4.3.6 卖家取消订单
     * @action /cancelMakeOrder
     * @method  get
     * @url_test http://localhost/tp/home/index/cancelMakeOrder/user_token/MTIz/id/3
     * @table eat_order  &&  make_order
     * @param  String $user_token
     * @param  String $id
     */
    public function cancelMakeOrder($user_token = null, $id = null)

    {
        if (self::tokenIsEmpty($user_token)) {
            $Food_order = M('Food_order');
            $Food_order->status = 0;
            $status = $Food_order->where('id=%d', $id)->save();
            if ($status) {
                $arr = array('status' => 1, 'msg' => 'success卖家取消订单成功');
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => 'failed 卖家取消订单失败');
                echo json_encode($arr);
            }
        }

    }


    /**  接口说明 ：8.4.3.7 客户查看评价
     * @action /lookCommentMakeOrder
     * @method  get
     * @url_test http://localhost/tp/home/index/lookCommentMakeOrder/user_token/MTIz/id/2
     * @table eat_order  &&  make_order
     * @param  String $user_token
     * @param  String $id
     */
    public function lookCommentMakeOrder($user_token = null, $id = null)
    {

        if (self::tokenIsEmpty($user_token)) {
            $u_token = M('Order_comment')->where('id=%d', $id)->getfield('user_token');
            $model = M()->table(array('order_comment' => 'oc', 'user' => 'us'));
            $condition['us.user_token'] = $u_token;
            $condition['oc.id'] = $id;
            $condition['_logic'] = 'AND';
            $field = 'us.user_name , us.user_icon , oc.comment_content , oc.comment_describe ,oc.comment_service , oc.comment_taste';
            $comment = $model->where($condition)->field($field)->find();
            if ($comment) {
                $arr = array('status' => 1, 'msg' => 'success 客户查看评价成功', 'comment' => $comment);
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => 'failed 客户查看评价失败', 'comment' => $comment);
                echo json_encode($arr);
            }
        } else {

        }
    }











//8.5我的钱包

    /**  接口说明 ： 8.5.1 查询余额
     * @action /put_money
     * @method  get
     * @url_test http://localhost/tp/home/index/put_money/user_token/MTIz/money/1000
     * @table Order_comment
     * @param  String $user_token
     */
    public function set_money($user_token = null)
    {

        $User = M("User");
        $money = $User->where("user_token='%s'", $user_token)->getfield('user_money');
        if ($money != '' && $money != null) {
            $arr = array('status' => 1, 'msg' => 'success 查询余额成功', 'money' => $money);
            echo json_encode($arr);
        } else {
            $arr = array('status' => 0, 'msg' => 'failed 查询余额失败', 'money' => '0.0');
            echo json_encode($arr);
        }
    }


    /**  接口说明 ： 8.5.2 充值
     * @action /put_money
     * @method  get
     * @url_test http://localhost/tp/home/index/put_money/user_token/MTIz/money/1000
     * @table Order_comment
     * @param  String $user_token
     * @param  String $money
     */
    public function put_money($user_token = null, $money = null)
    {

        $User = M("User");
        $mn = $User->where("user_token='%s'", $user_token)->getfield('user_money');
        $m = $mn + $money;
        $User->user_money = $m;
        $status = $User->where("user_token='%s'", $user_token)->save();
        if ($status) {
            $arr = array('status' => 1, 'msg' => 'success 充值成功', 'money' => $m);
            echo json_encode($arr);
        } else {
            $arr = array('status' => 0, 'msg' => 'failed 充值失败');
            echo json_encode($arr);
        }
    }

    /**  接口说明 ： 8.5.3 提现
     * @action /get_money
     * @method  get
     * @url_test http://localhost/tp/home/index/get_money/user_token/MTg5NDI0MzM5Mjc=/money/500
     * @table Order_comment
     * @param  String $user_token
     * @param  String $money
     */
    public function get_money($user_token = null, $money = null)
    {

        $User = M("User");
        $mn = $User->where("user_token='%s'", $user_token)->getfield('user_money');
        $m = $mn - $money;
        if ($m >= 0) {
            $User->user_money = $m;
            $status = $User->where("user_token='%s'", $user_token)->save();
            if ($status) {
                $arr = array('status' => 1, 'msg' => 'success 提现成功', 'money' => $m);
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => 'failed 提现失败');
                echo json_encode($arr);
            }
        } else {
            $arr = array('status' => 0, 'msg' => 'failed 提现失败 提现金额超过余额');
            echo json_encode($arr);
        }

    }


    /**  接口说明 ：8.7.1 获取我的厨房菜品列表List
     * ====================状态说明===================
     *  0   厨师删除的菜色
     *  1  默认是1 添加新菜色之后的状态
     *  2  上架今日菜色  添加到首页推荐之后的状态
     *  ======================================
     * @action /get_my_makeFoodList
     * @method  get
     * @url_test http://localhost/tp/home/index/get_my_makeFoodList/user_token/MTIz
     * @table Make_order
     * @param  String $user_token
     * @return array(`order_id`, `order_title`, `food_id`, `order_price`, `order_type`, `order_res_time`, `order_note` ,`order_pub_userId`)
     */
    public function get_my_makeFoodList($user_token = null)
    {
        if (self::tokenIsEmpty($user_token)) {

            $Make_order = M("Make_order");
            $condition['user_token'] = $user_token;
            $condition['_string'] = 'order_status  <>  0';

            $makeOrderList = $Make_order->where($condition)->select();
            if ($makeOrderList) {
                $arr = array('status' => 1, 'msg' => 'successful 获取我的厨房菜品列表List成功', 'myMakeOrderList' => $makeOrderList);
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => 'failed 获取我的厨房菜品列表List失败', 'myMakeOrderList' => $makeOrderList);
                echo json_encode($arr);
            }
        } else {

        }

    }





    /**  接口说明 ：8.7.2 厨师添加新菜色 （上传图片）
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
        $order_pic = self::$url . $this->uploadPic_ReturnPicName();
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
//                $arr = array('status' => 1, 'msg' => 'success 发布我要做饭成功');  //
//                echo json_encode($arr);
            } else {
//                $arr = array('status' => 0, 'msg' => 'insert Make_order failed发布我要做饭失败');
//                echo json_encode($arr);
            }

        }
    }


    /**  接口说明 ：8.7.3 上架今日菜色  ,发布在推荐上显示 提交后显示在 首页>推荐  order_id,order_num
     * @action /put_today_makeFood
     * @method  get
     * @url_test http://localhost/tp/home/index/put_today_makeFood/order_idArr/2next3next4next/order_numArr/6next7next8next
     * @table Make_order
     * @param  int $user_token
     * @param  int $order_idArr
     * @param  int $order_numArr
     * @return array(status,msg)
     */
    public function put_today_makeFood($user_token = null, $order_idArr = null, $order_numArr = null)
    {
        if (true) {
//            $order_idArr = array(6,7,8);
//            $order_numArr = array(4,5,6);
//            for ($i = 0; $i < count($order_idArr); $i++){
//            echo $order_idArr[$i];
//            }
            $status = null;
            $Make = M("Make_order");
            $status = null;
            $idArr = explode('next', $order_idArr);
            $numArr = explode('next', $order_numArr);

            // fixme 把order_id和order_num数量封装成数组 用循环逐条更新数据库Z
            for ($i = 0; $i < count($numArr) - 1; $i++) {
                $Make->order_status = 2;
                $Make->order_num = $numArr[$i];
                $status = $Make->where("order_id='%s'", $idArr[$i])->save();
            }
            if ($status) {
                $arr = array('status' => 1, 'msg' => 'success 上架今日菜色成功');
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => 'failed  上架今日菜色失败');
                echo json_encode($arr);
            }

        }
    }


    /**  接口说明 ：8.7.4 查看今日上架的菜色
     * @action /look_today_makeFood
     * @method  get
     * @url_test http://localhost/tp/home/index/look_today_makeFood/user_token/MTIz/
     * @table Make_order
     * @param  String $user_token
     * @return array(status,msg)
     */
    public function look_today_makeFood($user_token = null)
    {
        if (self::tokenIsEmpty($user_token)) {
            $Make_order = M("Make_order");
            $condition['user_token'] = $user_token;
            $condition['order_status'] = 2;
            $condition['_logic'] = 'AND';
            $todayOrderList = $Make_order->where($condition)->select();
            if ($todayOrderList) {
                $arr = array('status' => 1, 'msg' => 'successful 查看今日上架的菜色', 'todayOrderList' => $todayOrderList);
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => 'failed查看今日上架的菜色失败');
                echo json_encode($arr);
            }
        }
    }


    /**  接口说明 ：8.7.5下架今日上架的菜色
     * @action /down_today_makeFood
     * @method  get
     * @url_test http://localhost/tp/home/index/down_today_makeFood/user_token/MTIz/order_id/8
     * @table Make_order
     * @param  int $order_id
     * @param  String $user_token
     * @return array(status,msg)
     */
    public function down_today_makeFood($user_token = null, $order_id = null)
    {
        if (self::tokenIsEmpty($user_token)) {
            $Make_order = M("Make_order");
            $condition['order_id'] = $order_id;
            $Make_order->order_status = 1;
            $status = $Make_order->where($condition)->save();
            if ($status) {
                $arr = array('status' => 1, 'msg' => 'successful 下架今日上架的菜色成功');
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => 'failed 下架今日上架的菜色失败');
                echo json_encode($arr);
            }

        } else {

        }
    }


    /**  接口说明 ：8.7.6查看没有上架到今日菜色的菜品列表
     * @action /look_today_makeFood
     * @method  get
     * @url_test http://localhost/tp/home/index/look_unLoad_makeFood/user_token/MTIz/
     * @table Make_order
     * @param  String $user_token
     * @return array(status,msg)
     */
    public function look_unLoad_makeFood($user_token = null)
    {
        if (self::tokenIsEmpty($user_token)) {
            $Make_order = M("Make_order");
            $condition['user_token'] = $user_token;
            $condition['order_status'] = 1;
            $condition['_logic'] = 'AND';
            $todayOrderList = $Make_order->where($condition)->select();
            if ($todayOrderList) {
                $arr = array('status' => 1, 'msg' => 'successful 查看今日上架的菜色', 'unloadOrderList' => $todayOrderList);
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => 'failed   查看今日上架的菜色失败');
                echo json_encode($arr);
            }
        }
    }


    /**  接口说明 ：8.7.7  删除我的菜色
     * @action /down_today_makeFood
     * @method  get
     * @url_test http://localhost/tp/home/index/delete_makeFood/user_token/MTIz/order_id/8
     * @table Make_order
     * @param  int $order_id
     * @param  String $user_token
     * @return array(status,msg)
     */
    public function delete_makeFood($user_token = null, $order_id = null)
    {
        if (self::tokenIsEmpty($user_token)) {
            $Make_order = M("Make_order");
            $condition['order_id'] = $order_id;
            $Make_order->order_status = 0;
            $status = $Make_order->where($condition)->save();
            if ($status) {
                $arr = array('status' => 1, 'msg' => 'successful 删除我的菜色成功');
                echo json_encode($arr);
            } else {
                $arr = array('status' => 0, 'msg' => 'failed 删除我的菜色失败');
                echo json_encode($arr);
            }

        } else {

        }
    }


    /**  工具说明 ：图片上传
     * @url_test http://localhost/tp/home/index/upload
     * @param  String $user_token
     * @return boolean
     */
    public function upload($user_token = true, $file = null)
    {

        if (self::tokenIsEmpty($user_token)) {


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
        }
    }


    public function uploadTest($name = null, $age = null)
    {

        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize = 3145728;// 设置附件上传大小
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath = './Uploads/'; // 设置附件上传根目录
        // 上传单个文件
        $info = $upload->uploadOne($_FILES['file']);
        if (!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        } else {// 上传成功 获取上传文件信息
//            $arr = array('status' => 0, 'msg' => 'failed 下架今日上架的菜色失败');
//            echo json_encode($arr);
            return $info['savepath'] . $info['savename'];

        }
    }


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
// fixme  上传图片不能返回response  json
    public function
    uploadPic_ReturnPicName()
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
        // 上传单个文件
        $info = $upload->upload();
//        $info = $upload->uploadOne($_FILES['topic_picture']);
        if (!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        } else {// 上传成功 获取上传文件信息
            foreach ($info as $file) {// 返回图片名用于存入数据库
                return $file['savepath'] . $file['savename'];
            }
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


    function tesOriginSQL()
    {
        $Model = D();//或者 $Model = D(); 或者 $Model = M();
        $sql = "select * from `user`";
        $voList = $Model->query($sql);

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


//    /**  接口说明 ：8.1.1 待接单
//     * @action /get_wait_order
//     * @method  get
//     * @url_test http://localhost/tp/home/index/get_wait_order/user_token/MTg5NDI0MzM5Mjc=
//     * @table Eat_order
//     * @param  String $user_token
//     * @return array(status,msg,waitOrderList)
//     */
//    public function get_wait_order($user_token = null)
//    {
//        if (true) {
//            $Eat_order = M("Eat_order");
//            $waitOrderList = $Eat_order->where("user_token=%d  and  order_status=%d", array($user_token, 1))->select();
//            if ($waitOrderList) {
//                $arr = array('status' => 1, 'msg' => 'success 获取待接单成功', 'waitOrderList' => $waitOrderList);
//                echo json_encode($arr);
//            } else {
//                $arr = array('status' => 0, 'msg' => 'failed 获取待接单失败', 'waitOrderList' => $waitOrderList);
//                echo json_encode($arr);
//            }
//
//        } else {
//
//        }
//    }
//
//
//    /**  接口说明 ：8.1.2 待送餐
//     * @action /get_deliver_order
//     * @method  get
//     * @url_test http://localhost/tp/home/index/get_deliver_order/user_token/MTg5NDI0MzM5Mjc=
//     * @table Eat_order
//     * @param  String $user_token
//     * @return array(status,msg,deliverOrderList)
//     */
//    public function get_deliver_order($user_token = null)
//    {
//        if (true) {
//            $Eat_order = M("Eat_order");
//            $deliverOrderList = $Eat_order->where("user_token=%d  and  order_status=%d", array($user_token, 2))->select();
//            if ($deliverOrderList) {
//                $arr = array('status' => 1, 'msg' => 'success 获取待送餐成功', 'deliverOrderList' => $deliverOrderList);
//                echo json_encode($arr);
//            } else {
//                $arr = array('status' => 0, 'msg' => 'failed 获取待送餐失败', 'deliverOrderList' => $deliverOrderList);
//                echo json_encode($arr);
//            }
//
//        } else {
//
//        }
//    }
//
//
//    /**  接口说明 ：8.1.3 待确认
//     * @action /get_confirm_order
//     * @method  get
//     * @url_test http://localhost/tp/home/index/get_confirm_order/user_token/MTg5NDI0MzM5Mjc=
//     * @table Eat_order
//     * @param  String $user_token
//     * @return array(status,msg,confirmOrderList)
//     */
//    public function get_confirm_order($user_token = null)
//    {
//        if (true) {
//            $Eat_order = M("Eat_order");
//            $confirmOrderList = $Eat_order->where("user_token=%d  and  order_status=%d", array($user_token, 3))->select();
//            if ($confirmOrderList) {
//                $arr = array('status' => 1, 'msg' => 'success 获取待确认成功', 'confirmOrderList' => $confirmOrderList);
//                echo json_encode($arr);
//            } else {
//                $arr = array('status' => 0, 'msg' => 'failed 获取待确认失败', 'confirmOrderList' => $confirmOrderList);
//                echo json_encode($arr);
//            }
//
//        } else {
//
//        }
//    }
//
//
//
//
//
//    /**  接口说明 ：8.1.4.1 评价订单  客户 (eat)
//     * @action /pub_order_comment
//     * @method  get
//     * @url_test http://localhost/tp/home/index/pub_order_comment/user_token/MTg5NDI0MzM5Mjc=/comment_content/很好吃呀/comment_describe/4/comment_service/4/comment_taste/3/order_id/2
//     * @table Order_comment
//     * @param  String $user_token
//     * @param  String $comment_content
//     * @param  String $comment_describe
//     * @param  String $comment_service
//     * @param  String $comment_taste
//     * @param  String $order_id
//     * @return array(status,msg)
//     */
//    public function pub_order_comment($user_token = null, $comment_content = null, $comment_describe = null, $comment_service = null, $comment_taste = null, $order_id = null)
//    {
//        if (true) {
//
//            $average = ($comment_describe + $comment_service + $comment_taste) / 3;
//            $Order_comment = M("Order_comment");
//            $Order_comment->user_token = $user_token;
//            $Order_comment->comment_content = $comment_content;
//            $Order_comment->comment_describe = $comment_describe;
//            $Order_comment->comment_service = $comment_service;
//            $Order_comment->comment_taste = $comment_taste;
//            $Order_comment->average = $average;
//            $Order_comment->order_id = $order_id;
//
//            $commentOrderList = $Order_comment->add();
//            if ($commentOrderList) {
//                $arr = array('status' => 1, 'msg' => 'success 评价订单成功');
//                echo json_encode($arr);
//            } else {
//                $arr = array('status' => 0, 'msg' => 'failed 评价订单失败');
//                echo json_encode($arr);
//            }
//
//        }
//    }
