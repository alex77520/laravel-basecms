<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\web\Controller;

use App\Models\Groups;

class GroupController extends Controller
{
    public function __construct(){
        parent::__construct();
    }
    /**
     * 机构列表展示
     *
     * @param type var Description
     **/
    public function index(Request $request){
        $Groups = Groups::paginate(10);
        $data = [
            'groups' => $Groups
        ];
        return view('web.v1.admin.group.index' , $data);
    }
    /**
     * 基本信息修改
     *
     * @param type var Description
     **/
    public function edit(Request $request)
    {
        if(!$request->has('gid')){
            return parent::_error('操作异常，请刷新后重试','modal');
        }
        $Group = Groups::where('gid',$request->input('gid'))->first();
        if(!$Group){
            return parent::_error('该机构不存在','modal');
        }
        $data = [
            'group' => $Group
        ];
        return view('web.v1.admin.group.actions.edit',$data);
    }
    /**
     * 修改基本信息[Action]
     *
     * 修改机构的名称+简介
     *
     * @param type var Description
     **/
    public function doEdit(Request $request)
    {
        if(!$request->has('gid')){
            return parent::ajaxError('操作异常，请刷新重试');
        }
        if(!$request->has('name')){
            return parent::ajaxError('名称必填');
        }
        $Group = Groups::where('gid',$request->input('gid'))->first();
        if(!$Group){
            return parent::ajaxError('机构不存在');
        }
        $Group->name = $request->input('name');
        if($request->has('intro')){
            $Group->intro = $request->input('intro');
        }
        if(!$Group->save()){
            return parent::ajaxError('服务器异常，请稍后重试');
        }else{
            return parent::ajaxSuccess('保存成功');
        }
    }
    /**
     * 员工数量/资源大小
     *
     * @param type var Description
     **/
    public function sizeSetting(Request $request)
    {
        if(!$request->has('gid')){
            return parent::_error('操作异常，请刷新后重试','modal');
        }
        $Group = Groups::where('gid',$request->input('gid'))->first();
        if(!$Group){
            return parent::_error('该机构不存在','modal');
        }
        $data = [
            'group' => $Group
        ];
        return view('web.v1.admin.group.actions.size',$data);
    }
    /**
     * 修改员工数量/资源大小[Action]
     *
     * 修改员工数量和可用资源大小
     *
     * @param type var Description
     **/
    public function doSizeSetting(Request $request)
    {
        if(!$request->has('gid')){
            return parent::ajaxError('操作异常，请刷新重试');
        }
        // if(!$request->has('size')){
        //     return parent::ajaxError('资源可用量必须设置');
        // }
        if(!$request->has('user_num')){
            return parent::ajaxError('员工数量必须设置');
        }
        // if(!is_numeric($request->input('size')) || !is_numeric($request->input('user_num'))){
        //     return parent::ajaxError('请输入正确的数字');
        // }
        if(!is_numeric($request->input('user_num'))){
            return parent::ajaxError('请输入正确的数字');
        }
        $Group = Groups::where('gid',$request->input('gid'))->first();
        if(!$Group){
            return parent::ajaxError('机构不存在');
        }
        // $Group->resource_size = $request->input('size');
        $Group->user_num = $request->input('user_num');
        if(!$Group->save()){
            return parent::ajaxError('服务器异常，请稍后重试');
        }else{
            return parent::ajaxSuccess('保存成功');
        }
    }

    /**
     * 发布文件是否需要审核
     *
     * @param type var Description
     **/
    public function limitSetting(Request $request)
    {
        if(!$request->has('gid')){
            return parent::_error('操作异常，请刷新后重试','modal');
        }
        $Group = Groups::where('gid',$request->input('gid'))->first();
        if(!$Group){
            return parent::_error('该机构不存在','modal');
        }
        $data = [
            'group' => $Group
        ];
        return view('web.v1.admin.group.actions.limit',$data);
    }
    /**
     * 修改员工数量/资源大小[Action]
     *
     * 修改员工数量和可用资源大小
     *
     * @param type var Description
     **/
    public function dolimitSetting(Request $request)
    {
        if(!$request->has('gid')){
            return parent::ajaxError('操作异常，请刷新重试');
        }
        if(!$request->has('audit')){
            return parent::ajaxError('请确认发布文章是否需要审核');
        }
        $audit = $request->input('audit');
        if($audit === '1'){
            $audit = true;
        }else{
            $audit = false;
        }
        $Group = Groups::where('gid',$request->input('gid'))->first();
        if(!$Group){
            return parent::ajaxError('机构不存在');
        }
        $Group->post_audit = $audit;
        if(!$Group->save()){
            return parent::ajaxError('服务器异常，请稍后重试');
        }else{
            return parent::ajaxSuccess('保存成功');
        }
    }
    /**
     * 禁用/启用账号
     *
     * 根据传过来的ID进行逆向操作。
     *
     * @param type var Description
     **/
    public function doEnable(Request $request)
    {
        if(!$request->has('gid')){
            return parent::ajaxError('操作异常，请稍后重试');
        }
        $Group = Groups::where('gid',$request->input('gid'))->first();
        if(!$Group){
            return parent::ajaxError('机构不存在');
        }
        $Group->status = $Group->status == '1' ? false : true;
        if(!$Group->save()){
            return parent::ajaxError('服务器异常，请稍后重试');
        }else{
            return parent::ajaxSuccess('操作成功');
        }
    }
    /**
     * 设为/取消推荐账号
     *
     * 根据传过来的ID进行逆向操作。
     *
     * @param type var Description
     **/
    public function doRecommend(Request $request)
    {
        if(!$request->has('gid')){
            return parent::ajaxError('操作异常，请稍后重试');
        }
        $Group = Groups::where('gid',$request->input('gid'))->first();
        if(!$Group){
            return parent::ajaxError('机构不存在');
        }
        $Group->recommend = $Group->recommend == '1' ? false : true;
        if(!$Group->save()){
            return parent::ajaxError('服务器异常，请稍后重试');
        }else{
            return parent::ajaxSuccess('推荐成功');
        }
    }
}
