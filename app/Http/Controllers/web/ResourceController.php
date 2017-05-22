<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\web\Controller;

use App\Models\ResourceClassify;
use App\Models\Resources;
use App\Models\Groups;
use Illuminate\Support\Facades\Storage;

class ResourceController extends Controller
{
    public function __construct(){
        parent::__construct();
    }
    /**
     * 文件管理的首页
     *
     * 默认展示所有的文件，如有ID传入，则根据ID查询指定分类的文件。
     *
     * @param type var Description
     **/
    public function index(Request $request )
    {
        $cid = null;
        # 获取所有分组
        $ResourceClassifys = ResourceClassify::where('gid',parent::$user['gid'])->orderBy('sort','asc')->get();
        if($ResourceClassifys){
            foreach($ResourceClassifys as $classify)
                $ids[] = $classify->id;
        }
        if($request->has('cid')){
            $cid = $request->get('cid');
            if(!in_array($cid,$ids)){
                return parent::_error('分类不存在');
            }
            $Resources = Resources::where('cid',$cid)->paginate(10);
        }else{
            $Resources = Resources::whereIn('cid',$ids)->paginate(10);
        }
        $data = [
            'cid' => $cid,
            'classifys' => $ResourceClassifys,
            'resources' => $Resources
        ];
        return view('web.v1.admin.resource.index',$data);
    }
    /**
     * 上传文件
     *
     * 向用户展示上传文件的视图。
     *
     * @param type var Description
     **/
    public function upload(Request $request)
    {
        # 获取所有分组
        $ResourceClassifys = ResourceClassify::where('gid',parent::$user['gid'])->get();
        $data = [
            'classifys' => $ResourceClassifys
        ];
        return view('web.v1.admin.resource.upload',$data);
    }
    /**
     * 存储文件信息
     *
     * 根据AetherUpload上传文件后的回调，进行关键信息的存储。
     *
     * @param type var Description
     **/
    public function saveFileInfo(Request $request)
    {
        if(!$request->has('path') || !$request->has('filename') || !$request->has('name') || !$request->has('type') || !$request->has('size') ){
            return parent::ajaxError('数据不完整,请重新提交');
        }
        # 获取一个默认分组
        $ResourceClassify = ResourceClassify::where('gid',parent::$user['gid'])->first();

        $Resource = new Resources;
        $Resource->path = $request->input('path');
        $Resource->filename = $request->input('filename');
        $Resource->name = $request->input('name');
        $Resource->type = $request->input('type');
        $Resource->size = $request->input('size');
        $Resource->cid = $ResourceClassify->id;
        $rs = $Resource->save();
        if(!$rs){
            return parent::ajaxError('服务器异常，请稍后重试');
        }else{
            parent::GroupLogger('insert',$Resource->id,"文件：".$Resource->filename."[随机名称]","RESOURCE");
            // $name = str_replace('.'.$Resource->type,'',$Resource->name);
            return parent::ajaxSuccess(
                [
                    'id' => $Resource->id,
                    'cid' => $Resource->cid,
                    'name' => $Resource->name,
                    'type' => $Resource->type,
                ]
            );
        }
    }
    /**
     * 修改文件信息
     *
     * @param type var Description
     **/
    public function edit(Request $request)
    {
        # 获取所有分组
        $ResourceClassifys = ResourceClassify::where('gid',parent::$user['gid'])->get();
        # 获取文件信息
        if(!$request->has('id')){
            return parent::_error('文件信息不存在','modal');
        }
        $Resource = Resources::where('id',$request->input('id'))->first();
        if(!$Resource){
            return parent::_error('文件信息不存在','modal');
        }
        # 检查这个文件的信息是否是本人所有
        $isSelf = ResourceClassify::where('id',$Resource->cid)->where('gid',parent::$user['gid'])->first();
        if(!$isSelf){
            return parent::_error('文件信息不存在','modal');
        }
        $data = [
            'resource' => $Resource,
            'classifys' => $ResourceClassifys
        ];
        return view('web.v1.admin.resource.edit',$data);
    }
    /**
     * 修改文件信息[Action]
     *
     * 用途1：楼上的方法调用过后，可在UI界面上接着马上修改。
     * 用途2：就是单纯的对某个文件进行修改。
     *
     * @param type var Description
     **/
    public function doEdit(Request $request)
    {
        if(!$request->has('id')){
            return parent::ajaxError('操作异常，请刷新后重试');
        }
        if(!$request->has('name') || !$request->has('cid')){
            return parent::ajaxError('姓名和分组不能为空');
        }
        $Resource = Resources::where('id',$request->input('id'))->first();
        if(!$Resource){
            return parent::ajaxError('文件信息不存在');
        }

        # 检查这个文件的信息是否是本人所有
        $isSelf = ResourceClassify::where('id',$Resource->cid)->where('gid',parent::$user['gid'])->first();
        if(!$isSelf){
            return parent::ajaxError('文件信息不存在');
        }
        $OldName = $Resource->name; # 原名，作日志记录用
        $OldClassify = $Resource->Classify->name;
        $Resource->name = $request->input('name');
        $Resource->cid = $request->input('cid');
        if(!$Resource->save()){
            return parent::ajaxError('服务器异常，请稍后重试');
        }else{
            parent::GroupLogger('update',$Resource->id,"原信息:{分类:".$OldClassify.",文件名:".$OldName."},现信息:{分类:".$Resource->Classify->name.",文件名:".$Resource->name."}","RESOURCE");
            return parent::ajaxSuccess('保存成功');
        }
    }
    /**
     * 删除文件
     *
     * 删除数据库信息的同时，进行文件的删除
     *
     * @param type var Description
     **/
    public function del(Request $request)
    {
        if(!$request->has('id')){
            return parent::ajaxError('操作异常，请稍后重试');
        }
        # 获取该文件的信息
        $Resource = Resources::where('id',$request->input('id'))->first();
        if(!$Resource){
            return parent::ajaxError('文件不存在');
        }

        # 检查这个文件的信息是否是本人所有
        $isSelf = ResourceClassify::where('id',$Resource->cid)->where('gid',parent::$user['gid'])->first();
        if(!$isSelf){
            return parent::ajaxError('当前文件无法删除，因为该文件不属于该账户');
        }

        # 删除文件
        $path = config('app.upload_path').$Resource->path."/".$Resource->filename;
        Storage::delete( $path );
        $rs = $Resource->delete();
        if(!$rs){
            return parent::ajaxError( '服务器异常，请稍后重试' );
        }
        parent::GroupLogger('delete',$Resource->id,"文件名:".$Resource->name,"RESOURCE");
        return parent::ajaxSuccess('删除成功');
    }
    /**
     * 分类添加
     *
     * @param type var Description
     **/
    public function classifyAdd(Request $request)
    {
        $data = [];
        return view('web.v1.admin.resource.classify.add',$data);
    }
    /**
     * 分类添加[Action]
     *
     * 添加文件管理的分类
     *
     * @param type var Description
     **/
    public function doClassifyAdd(Request $request)
    {
        if(!$request->has('name')){
            return parent::ajaxError('请输入分类名称');
        }
        $sort = $request->has('sort') ? $request->input('sort') : 1;
        $sort = $sort == 0 ? 1 : $sort;
        $ResourceClassify = new ResourceClassify;
        $ResourceClassify->name = $request->input('name');
        $ResourceClassify->sort = $sort;
        $ResourceClassify->gid = self::$user['gid'];
        if(!$ResourceClassify->save()){
            return parent::ajaxError('服务器异常，请稍后重试');
        }else{
            parent::GroupLogger('insert',$ResourceClassify->id,"分类名:".$ResourceClassify->name,"RESOURCE");
            return parent::ajaxSuccess('创建成功');
        }
    }
    /**
     * 分类修改
     *
     * @param type var Description
     **/
    public function classifyEdit(Request $request)
    {
        if(!$request->has('id')){
            return parent::_error('操作异常，请刷新重试','modal');
        }
        $Classify = ResourceClassify::where('id',$request->input('id'))->where('gid',self::$user['gid'])->first();
        if(!$Classify){
            return parent::_error('该分类不存在','modal');
        }
        $data = [
            'classify' => $Classify
        ];
        return view('web.v1.admin.resource.classify.edit',$data);
    }
    /**
     * 分类修改[Action]
     *
     * 修改文件管理的分类
     *
     * @param type var Description
     **/
    public function doClassifyEdit(Request $request)
    {
        if(!$request->has('id')){
            return parent::ajaxError('系统异常，请稍后重试');
        }
        if(!$request->has('name')){
            return parent::ajaxError('名称必填');
        }
        $ResourceClassify = ResourceClassify::where('id',$request->input('id'))->where('gid',self::$user['gid'])->first();
        if(!$ResourceClassify){
            return parent::ajaxError('该分类不存在');
        }
        if($ResourceClassify->sort != 0){
            $sort = $request->has('sort') ? $request->input('sort') : 1;
            $sort = $sort == 0 ? 1 : $sort;
        }else{
            $sort = 0;
        }
        $OldName = $ResourceClassify->name;
        $OldSort = $ResourceClassify->sort;
        $ResourceClassify->name = $request->input('name');
        $ResourceClassify->sort = $sort;
        if(!$ResourceClassify->save()){
            return parent::ajaxError('服务器异常，请稍后重试');
        }else{
            parent::GroupLogger('update',$ResourceClassify->id,"原信息:{名称:".$OldName.",排序:".$OldSort."},现信息:{名称:".$ResourceClassify->name.",排序:".$ResourceClassify->sort."}","RESOURCE");
            return parent::ajaxSuccess('保存成功');
        }
    }
    /**
     * 分类删除[Action]
     *
     * !!!默认分类不允许删除!!! sort为0的分类表示默认分类，执行空分类的删除，如果分类下有文件，将文件移动至默认分类
     *
     * @param type var Description
     **/
    public function doClassifyDel(Request $request)
    {
        if(!$request->has('cid')){
            return parent::ajaxError('操作异常，请稍后重试');
        }
        $Classify = ResourceClassify::where('id',$request->input('cid'))->where('gid',self::$user['gid'])->first();
        if(!$Classify){
            return parent::ajaxError('该分类不存在');
        }
        if($Classify->sort == '0'){
            return parent::ajaxError('默认分类不允许删除');
        }
        # 获取该分类下所有的文件
        $Resources = Resources::where('cid',$request->input('cid'))->get();
        if($Resources){
            # 获取默认分类的ID
            $defaultClassify = ResourceClassify::where('gid',self::$user['gid'])->where('sort',0)->first();
            # 将该分类下的文件，转移到默认分类下
            Resources::where('cid',$request->input('cid'))->update(['cid'=>$defaultClassify->id]);
        }
        # 删除该分类信息
        if(!$Classify->delete()){
            # 进行回滚
            if($Resources){
                foreach($Resources as $Resource){
                    $Resource->cid = $Classify->id;
                    $Resource->save();
                }
            }
            return parent::ajaxError('服务器异常，请稍后重试');
        }else{
            parent::GroupLogger('delete',$Classify->id,"分类名:".$Classify->name,"RESOURCE");
            return parent::ajaxSuccess('删除成功');
        }
    }
    /**
     * 文件选择模态框
     *
     * @param type var Description
     **/
    public function chooseModal(Request $request)
    {
        $id = "alert";
        $method = null;
        if(!$request->has('method')){
            $method = "all";
        }else{
            $method = $request->input('method');
        }
        if($request->has('id')){
            $id = $request->input('id');
        }
        # 获取我的6个分类
        $ResourceClassifys = ResourceClassify::where('gid',self::$user['gid'])->orderBy('sort','asc')->get();
        # 根据method来区分需要图片选择器还是文件选择器
        switch ($method) {
            case 'img':
                $data = [
                    'id' => $id,
                    'load_url' => route('/admin/resource/img/get'),
                    'classifys' => $ResourceClassifys
                ];
                break;
            
            default:
                $data = [
                    'id' => $id,
                    'load_url' => route('/admin/resource/get'),
                    'classifys' => $ResourceClassifys
                ];
                break;
        }
        return view('web.v1.admin.resource.dialog',$data);
    }
    /**
     * 获取图片列表
     *
     * Undocumented function long description
     *
     * @param type var Description
     **/
    public function getImages(Request $request)
    {
        $page = $request->has('page') ? $request->input('page') : 1;
        $num = 8;
        $first = ($page-1)*$num;
        $imgs = [
            'png',
            'jpg',
            'gif',
            'bmp',
            'jpeg'
        ];
        # 获取当前用户的所有分组列表
        $ResourceClassifys = ResourceClassify::where('gid',parent::$user['gid'])->orderBy('sort','asc')->get();
        if($ResourceClassifys){
            foreach($ResourceClassifys as $classify)
                $ids[] = $classify->id;
        }
        # 获取图片[如果有分类ID，则取分类下的图片，若无，则取默认分类下的图片。]
        if($request->has('cid')){
            $cid = $request->input('cid');
            if($cid == 0){
                $Resources = Resources::whereIn('type',$imgs)
                            ->whereIn('cid',$ids)
                            ->offset($first)->limit($num)
                            ->orderBy('updated_at','desc')
                            ->get();
            }else{
                if(!in_array($cid,$ids)){
                    return parent::ajaxError('当前分类不存在');
                }
                $Resources = Resources::where('cid',$cid)->whereIn('type',$imgs)
                            ->offset($first)->limit($num)
                            ->orderBy('updated_at','desc')
                            ->get();
            }
        }else{
            $Resources = Resources::whereIn('type',$imgs)
                        ->whereIn('cid',$ids)
                        ->offset($first)->limit($num)
                        ->orderBy('updated_at','desc')
                        ->get();
        }
        $json = [];
        if($Resources){
            foreach($Resources as $Resource){
                $json[] = [
                    'id' => $Resource->id,
                    'url' => parent::ResourcePath($Resource->path,$Resource->filename),
                    'name' => $Resource->name
                ];
            }
        }
        return parent::ajaxSuccess($json);
    }
}
