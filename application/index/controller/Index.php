<?php
namespace app\index\controller;
use think\View;
use think\Db;
use think\Request;

class Index
{
    public function index() 
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="ad_bd568ce7058a1091"></think>';
    }

    public function data() 
    {
        return 'hello, thinkphp777';
    }

    public function testRender()    // 模板渲染 
    {
        // return view('../view/index', 'buyTitle', 'good');    //[使用的 view 助手渲染模板] 
        
        // $view = new view();                          // 动态创建
        $view = View::instance();                       // 静态创建
        $view -> assign('buyTitle','福建制作');          // 模板赋值
        return $view -> fetch('../view/index');         // 渲染模板
    }

    public function queryUser($pageRows=3, $pageNumb=1) // 默认 第一页， 每页一条
    {
         /* 下面这种方法，在前端指定每页显示数据具体条数后，不好使 */

        $queryData = Db::table('bought_user') -> paginate($pageRows, false, [
            'type' => 'Bootstrap',  
            'var_page' => 'pageNumb',                // 分页变量
            'page' => $pageNumb,                     // 当前页数
            'path' => "/index/Index/queryUser"
        ]);   // total , per_page, current_page, last_page, data:Array
        
        if (count($queryData) < 1) {
            echo "没数据了";
        } else {
            $view = new view();
            $view -> assign([                           // 多条数据的赋值
                'table_pagination' => $queryData,
                'buyTitle' => '福建制作',
                'users_data' => json_encode($queryData)
            ]);
            return $view -> fetch('../view/index');
        }
    }

    public function insertUser()    // create 方法 || 添加方法
    {
        if (request() -> isGet()) {
            return 'get请求是什么怦';
        } else {
            // return $name;
            $data = request() -> param(); // form 表单的时候，用这种，ajax 没有效果 ， 获取POST 过来的数据
            // return dump($data);
            $res = Db::table('bought_user') -> insert([
                'name' => $data['name'],
                'id_number' => $data['id_number'],
                'mobile_number' => $data['mobile_number'],
                'province' => $data['province'],
                'city' => $data['city'],
                'county' => $data['county'],
                'address_detail' => $data['address_detail'],
                'emergency_contact' => $data['emergency_contact'],
                'emergency_contact_number' => $data['emergency_contact_number'],
                'created_time' => $data['created_time']
            ]);

            if ($res > 0) {
                return 200;  // 直接返回 $data 是不行的， ajax 会报 500错误
            } else {
                return 'error';
            }
        }
    }

    public function deleteUser()    // delete 方法
    {
        if (request() -> isGet()) {
            $data = request() -> param();
            $res = Db::table('bought_user') -> where('id', $data['user_id']) -> delete();
            
            if($res > 0) {
                return 'success';
            } else {
                return 'failed';
            }
        }
        
    }

    public function updateUser()    // Update 方法
    {
        if (request() -> isGet()) {
            return 'get请求不接受';
        } else {
            $data = request() -> param();

            $res = Db::table('bought_user') -> where('id', $data['id']) -> update([
                'name' => $data['name'],
                'id_number' => $data['id_number'],
                'mobile_number' => $data['mobile_number'],
                'province' => $data['province'],
                'city' => $data['city'],
                'county' => $data['county'],
                'address_detail' => $data['address_detail'],
                'emergency_contact' => $data['emergency_contact'],
                'emergency_contact_number' => $data['emergency_contact_number'],
                'created_time' => $data['created_time']
            ]);

            if ($res > 0) {
                return "yes";
            } else {
                return "no";
            }

        }
    }

    public function bunchInsertUser () {        // bunch insert user information
        if (request() -> isGet()) {
            return "denided get";
        } else {
            $data = request() -> param();
            $dataLen = count($data);            // 总数据长度 【 单条数据的长度是 9 】
            $tempI = 0;
            for ($i = 0; $i < $dataLen; $i++) {
                
                $tempUser = explode(",", $data[$i]);
                $res = Db::table('bought_user') -> insert([
                    'name' => $tempUser[0],
                    'id_number' => $tempUser[1],
                    'mobile_number' => $tempUser[2],
                    'province' => $tempUser[3],
                    'city' => $tempUser[4],
                    'county' => $tempUser[5],
                    'address_detail' => $tempUser[6],
                    'emergency_contact' => $tempUser[7],
                    'emergency_contact_number' => $tempUser[8],
                    'created_time' => $tempUser[9]
                ]);

                if ($res > 0) {
                    $tempI++;
                }
            }

            if ($tempI < 1) {
                return "一条数据也没有成功";
            } else {
                return "success: ".$tempI." 条数据";
            }

        }
    }

}
