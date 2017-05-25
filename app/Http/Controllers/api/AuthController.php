<?php

namespace App\Http\Controllers\api;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\api\Controller;
use Illuminate\Support\Facades\Redis;
use App\Models\Members;
use App\Models\MemberToken;
use Hash;

use App\Http\Requests\api\Auth\registPost;
use App\Http\Requests\api\Auth\loginPost;
use App\Http\Requests\api\Auth\refreshTokenPost;
use App\Http\Requests\api\Auth\bindAccountPost;
use App\Http\Requests\api\Auth\registAccountPost;



class AuthController extends Controller
{
    public function __construct(){
        parent::__construct();
    }
    /**
     * 用户注册
     *
     * @param string mobile   手机号
     * @param string password md5加密后的密码
     * @param type var Description
     **/
    public function regist(registPost $request)
    {
        $mobile = $request->input('mobile');
        $password = $request->input('password');
        $code = $request->input('code');
        $key = $request->input('sms_key');

        $isExist = Members::where('mobile',$mobile)->first();
        if($isExist != null){
            return parent::respError('10001');
        }
        # 获取Redis中的记录
        if(!parent::verifySMS($mobile,$code,$key)){
            return parent::respError('400', '验证码错误');
        }
        $Member = new Members;
        $Member->mobile = $mobile;
        $Member->password = Hash::make($password);
        $Member->nickname = '新会员'.substr($mobile,7,4);
        if(!$Member->save()){
            return parent::respError('500');
        }
        if(!$respMemberInfo = self::respMemberInfo($Member)){
            return parent::respError('500');
        }
        return parent::respSuccess($respMemberInfo);
    }
    /**
     * 登录
     *
     * @param type var Description
     **/
    public function login(loginPost $request)
    {
        $mobile = $request->input('mobile');
        $password = $request->input('password');

        $Member = Members::where('mobile',$mobile)->first();
        if($Member){
            if(Hash::check($password, $Member->password)){
                if(!$respMemberInfo = self::respMemberInfo($Member)){
                    return parent::respError('500');
                }
                return parent::respSuccess($respMemberInfo);
            }
        }
        return parent::respError('400','手机号或密码不正确');
    }
    /**
     * 刷新登录信息
     *
     * @param type var Description
     **/
    public function refreshToken(refreshTokenPost $request)
    {
        if(!$request->header('token')){
            return parent::respError('401','登录凭证缺失');
        }
        $where = [
            'access_token' => self::$data['token'],
            'refresh_token' => $request->input('refresh_token')
        ];
        $MemberToken = MemberToken::where($where)->first();
        if($MemberToken != null){
            $respMemberInfo = self::respMemberInfo($MemberToken->Member);
            if(!$respMemberInfo){
                return parent::respError('500');
            }else{
                return parent::respSuccess($respMemberInfo);
            }
        }
        return parent::respError('400','提供的凭证无效');
    }
    /**
     * 第三方登录后绑定原有账户
     *
     * @param type var Description
     **/
    public function bindAccount(bindAccountPost $request)
    {
        $type = $request->input('type');
        $openid = $request->input('openid');
        $mobile = $request->input('mobile');
        $password = $request->input('password');
        $Member = Members::where('mobile',$mobile)->first();
        if($Member != null){
            if(!Hash::check($password, $Member->password)){
                return parent::respError('400' , '用户名或密码错误');
            }
        }else{
            return parent::respError('400' , '用户名或密码错误');
        }
        switch ($type) {
            case 'sina':
                if($Member->sina_openid != null){
                    return parent::respError('400', '新浪微博已经被绑定');
                }
                $isExist = Members::where('sina_openid',$openid)->first();
                if($isExist != null){
                    return parent::respError('400', '该微博已被其他账号绑定');
                }
                $Member->sina_openid = $openid;
                break;
            case 'qq':
                if($Member->qq_openid != null){
                    return parent::respError('400', 'QQ已经被绑定');
                }
                $isExist = Members::where('qq_openid',$openid)->first();
                if($isExist != null){
                    return parent::respError('400', '该QQ已被其他账号绑定');
                }
                $Member->qq_openid = $openid;
                break;
            case 'wechat':
                if($Member->wechat_openid != null){
                    return parent::respError('400', '微信已经被绑定');
                }
                $isExist = Members::where('wechat_openid',$openid)->first();
                if($isExist != null){
                    return parent::respError('400', '该微信已被其他账号绑定');
                }
                $Member->wechat_openid = $openid;
                break;
            default:
                return parent::respError('400', '授权类型不正确');
                break;
        }
        if(!$Member->save()){
            return parent::respError('500');
        }
        if(!$respMemberInfo = self::respMemberInfo($Member)){
            return parent::respError('500');
        }
        return parent::respSuccess($respMemberInfo);
    }
    /**
     * 第三方登录后注册新账户
     *
     * @param type var Description
     **/
    public function registAccount(registAccountPost $request)
    {
        $mobile = $request->input('mobile');
        $password = $request->input('password');
        $code = $request->input('code');
        $key = $request->input('sms_key');
        $type = $request->input('type');
        $openid = $request->input('openid');

        $isExist = Members::where('mobile',$mobile)->first();
        if($isExist != null){
            return parent::respError('10001');
        }
        // # 获取Redis中的记录
        // if(!parent::verifySMS($mobile,$code,$key)){
        //     return parent::respError('400', '验证码错误');
        // }
        $Member = new Members;
        $Member->mobile = $mobile;
        $Member->password = Hash::make($password);
        $Member->nickname = $request->has('nickname') ? $request->input('nickname') : '新会员'.substr($mobile,7,4);
        $Member->avatar = $request->has('avatar') ? $request->input('avatar') : NULL;
        switch ($type) {
            case 'sina':
                $isExist = Members::where('sina_openid',$openid)->first();
                if($isExist != null){
                    return parent::respError('400', '该微博已被其他账号绑定');
                }
                $Member->sina_openid = $openid;
                break;
            case 'qq':
                $isExist = Members::where('qq_openid',$openid)->first();
                if($isExist != null){
                    return parent::respError('400', '该QQ已被其他账号绑定');
                }
                $Member->qq_openid = $openid;
                break;
            case 'wechat':
                $isExist = Members::where('wechat_openid',$openid)->first();
                if($isExist != null){
                    return parent::respError('400', '该微信已被其他账号绑定');
                }
                $Member->wechat_openid = $openid;
                break;
            default:
                return parent::respError('400', '授权类型不正确');
                break;
        }

        if(!$Member->save()){
            return parent::respError('500');
        }
        if(!$respMemberInfo = self::respMemberInfo($Member)){
            return parent::respError('500');
        }
        return parent::respSuccess($respMemberInfo);
    }

    /**
     * 打印用户信息
     *
     * @param type var Description
     **/
    public function respMemberInfo($Member,$cache = false)
    {
        if(!parent::setMemberInfo($Member,$access_token)){
            return false;
        }
        # 存储Token到数据库
        $where = [
            'client_id' => parent::$data['appid'],
            'mid' => $Member->mid
        ];
        $MemberToken = MemberToken::where($where)->first();
        $refresh_token = str_random(38);
        if(!$MemberToken){
            $MemberToken = new MemberToken;
            $MemberToken->mid = $Member->mid;
            $MemberToken->access_token = $access_token;
            $MemberToken->refresh_token = $refresh_token;
            $MemberToken->client_id = parent::$data['appid'];
            $MemberToken->expire = 3600 * 24;
            if(!$MemberToken->save()){
                parent::removeMemberInfo($token);
                return false;
            }
        }else{
            # 清除之前的登录信息
            parent::removeMemberInfo($MemberToken->access_token);

            # 重置当前的登录信息
            $MemberToken->access_token = $access_token;
            $MemberToken->refresh_token = $refresh_token;
            if(!$MemberToken->save()){
                parent::removeMemberInfo($token);
                return false;
            }
        }
        $trueResp = [
            'access_token' => $access_token,
            'refresh_token' => $refresh_token,
            'expire' => $MemberToken->expire,
            'userinfo' => [
                'mid' => $Member->mid,
                'mobile' => $Member->mobile == null ? '' : $Member->mobile,
                'avatar' => $Member->avatar == null ? '' : $Member->avatar,
                'nickname' => $Member->nickname
            ]
        ];
        return $trueResp;
    }
}
