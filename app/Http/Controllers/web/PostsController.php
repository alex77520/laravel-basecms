<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\web\Controller;

use App\Models\Posts;
use App\Models\PostContent;
use App\Models\PostClassify;
use App\Models\PostRelation;
use App\Models\Resources;
use App\Models\Groups;
use App\Http\Requests\Article\ClassifyAddPost;

class PostsController extends Controller
{
    /**
     * 新闻列表[超管]
     *
     * Undocumented function long description
     *
     * @param type var Description
     **/
    public function index(Request $request)
    {
        $where = []; # 查询的条件
        $key = null; # 存储搜索KEY的条件，因为要匹配3个不同字段，所以要用or
        $post_ids = []; # 存储属于这个分类下的所有文章ID
        $search = []; # 存储搜索的条件，用于展示
        # 是否显示
        if($request->has('show')){
            $search['show'] = $request->get('show');
            switch ($request->get('show')) {
                case '1':
                    $where['show'] = true;
                    break;
                case '2':
                    $where['show'] = false;
                    break;
            }
        }
        # 是否屏蔽
        if($request->has('status')){
            $search['status'] = $request->get('status');
            switch ($request->get('status')) {
                case '1':
                    $where['status'] = true;
                    break;
                case '2':
                    $where['status'] = false;
                    break;
            }
        }
        # 类型:普通，图文，markdown
        if($request->has('type')){
            $search['type'] = $request->get('type');
            switch ($request->get('type')) {
                # 普通
                case '1':
                    $where['single'] = true;
                break;
                # 图文
                case '2':
                    $where['single'] = false;
                break;
                # Markdown
                case '3':
                    $where['single'] = true;
                    $where['markdown'] = true;
                break;
            }
        }
        # 搜索KEY
        if($request->has('key')){
            $key = $request->get('key');
            $search['key'] = $key;
        }
        # 机构
        if($request->has('group')){
            $group = $request->get('group');
            $search['gid'] = $group;
            $where['gid'] = $group;
        }
        # 判断分类，因为分类很复杂，需要先验证分类是不是这个人可以查询，然后从两者关系表取出所有的IDS，再用whereIn来查询
        if($request->has('classify')){
            $search['classify'] = $request->get('classify');
            $cid = $request->get('classify');
            if($cid !== '0'){
                # 证明存在的，那就获取他的所有文章ID
                $PostRelations = PostRelation::withTrashed()->where('pc_id',$cid)->get();
                if($PostRelations != null){
                    foreach($PostRelations as $relation){
                        $post_ids[] = $relation->post_id;
                    }
                }
            }
        }
        if(count($post_ids) > 0){
            if($key == null){
                $Posts = Posts::withTrashed()->where($where)
                        ->whereIn('post_id',$post_ids)
                        ->orderBy('created_at','desc')
                        ->paginate(20);
            }else{
                $Posts = Posts::withTrashed()->where($where)
                        ->whereIn('post_id',$post_ids)
                        ->where(function($query) use ($key){
                            $query->orWhere('title','like','%'.$key.'%')->orWhere('source','like','%'.$key.'%')->orWhere('tags','like','%'.$key.'%');
                        })
                        ->orderBy('created_at','desc')
                        ->paginate(20);
            }
        }else{
            if($key == null){
                $Posts = Posts::withTrashed()->where($where)
                        ->orderBy('created_at','desc')
                        ->paginate(20);
            }else{
                $Posts = Posts::withTrashed()->where($where)
                        ->where(function($query) use ($key){
                            $query->orWhere('title','like','%'.$key.'%')->orWhere('source','like','%'.$key.'%')->orWhere('tags','like','%'.$key.'%');
                        })
                        ->orderBy('created_at','desc')
                        ->paginate(20);
            }
        }
        $Classifys = PostClassify::where('father' , 0)->get();
        $Groups = Groups::all();
        $data = [
            'posts' => $Posts,
            'classifys' => $Classifys,
            'groups' => $Groups,
            'search' => $search
        ];
        return view('web.v1.admin.article.admin.index',$data);
    }

    /**
     * 文章详细信息
     *
     * @param type var Description
     **/
    public function intro(Request $request)
    {
        if(!$request->has('post_id')){
            return parent::_error('操作异常，请刷新重试','modal');
        }
        $where = [
            'post_id' => $request->input('post_id'),
        ];
        $Post = Posts::withTrashed()->where($where)->first();
        if(!$Post){
            return parent::_error('文章不存在','modal');
        }
        if($Post->cover != null){
            $pictures = explode(",",$Post->cover);
            $imgs = [];
            foreach($pictures as $key => $picture){
                $file = Resources::where('id' , $picture)->first();
                if($file != null) {
                    if(in_array($file->type,['png','jpg','jpeg','gif','bmp'])){
                        $imgs[] = parent::ResourcePath($file->path,$file->filename);
                    }
                }
            }
            $Post->images = $imgs;
        }
        if($Post->single == '0'){
            $trueContents = [];
            if($Post->Contents->count() > 0){
                foreach($Post->Contents as $content){
                    if($content->picture != null){
                        $file = Resources::where('id' , $content->picture)->first();
                        if($file != null) {
                            if(in_array($file->type,['png','jpg','jpeg','gif','bmp'])){
                                $tmp['image'] = parent::ResourcePath($file->path,$file->filename);
                            }
                        }
                    }
                    $tmp['content'] = $content->content;
                    $trueContents[] = $tmp;
                }
            }
            $Post->contents = $trueContents;
            $data = [
                'post' => $Post
            ];
            return view('web.v1.admin.article.intro-tm',$data);
        }else{
            $data = [
                'post' => $Post
            ];
            return view('web.v1.admin.article.intro',$data);
        }
    }

    /**
     * 批量删除文章
     *
     * @param type var Description
     **/
    public function doDelPosts(Request $request)
    {
        if(!$request->has('ids')){
            return parent::ajaxError('操作异常，请刷新重试');
        }
        $ids = explode(",",$request->input('ids'));
        $deleteRows = Posts::withTrashed()->whereIn('post_id',$ids)->forceDelete();
        return parent::ajaxSuccess($deleteRows);
    }
    /**
     * 单条删除文章
     *
     * @param type var Description
     **/
    public function doDelPost(Request $request)
    {
        if(!$request->has('post_id')){
            return parent::ajaxError('操作异常，请刷新重试');
        }
        $deleteRows = Posts::withTrashed()->where('post_id',$request->input('post_id'))->forceDelete();
        return parent::ajaxSuccess($deleteRows);
    }
    /**
     * 批量通过审核
     *
     * @param type var Description
     **/
    public function doUnbanPosts(Request $request)
    {
        if(!$request->has('ids')){
            return parent::ajaxError('操作异常，请刷新重试');
        }
        $ids = explode(",",$request->input('ids'));
        $enableRows = Posts::withTrashed()->whereIn('post_id',$ids)->update(['status'=>true]);
        $updateRows = PostRelation::withTrashed()->whereIn('post_id',$ids)->update(['status'=>true]);
        return parent::ajaxSuccess($enableRows);
    }
    /**
     * 批量拒绝审核
     *
     * @param type var Description
     **/
    public function doBanPosts(Request $request)
    {
        if(!$request->has('ids')){
            return parent::ajaxError('操作异常，请刷新重试');
        }
        $ids = explode(",",$request->input('ids'));
        $enableRows = Posts::withTrashed()->whereIn('post_id',$ids)->update(['status'=>false]);
        $updateRows = PostRelation::withTrashed()->whereIn('post_id',$ids)->update(['status'=>false]);
        return parent::ajaxSuccess($enableRows);
    }
    /**
     * 通过审核
     *
     * @param type var Description
     **/
    public function doUnbanPost(Request $request)
    {
        if(!$request->has('post_id')){
            return parent::ajaxError('操作异常，请刷新重试');
        }
        $where = [
            'post_id' => $request->input('post_id')
        ];
        $Post = Posts::withTrashed()->where($where)->first();
        if(!$Post){
            return parent::ajaxError('文章不存在');
        }
        $UnbanRows = Posts::withTrashed()->where('post_id',$Post->post_id)->update(['status'=>true]);
        $updateRows = PostRelation::withTrashed()->where('post_id',$Post->post_id)->update(['status'=>true]);
        return parent::ajaxSuccess($UnbanRows);
    }
    /**
     * 拒绝审核
     *
     * @param type var Description
     **/
    public function doBanPost(Request $request)
    {
        if(!$request->has('post_id')){
            return parent::ajaxError('操作异常，请刷新重试');
        }
        $where = [
            'post_id' => $request->input('post_id')
        ];
        $Post = Posts::withTrashed()->where($where)->first();
        if(!$Post){
            return parent::ajaxError('文章不存在');
        }
        $UnbanRows = Posts::withTrashed()->where('post_id',$Post->post_id)->update(['status'=>false]);
        $updateRows = PostRelation::withTrashed()->where('post_id',$Post->post_id)->update(['status'=>false]);
        return parent::ajaxSuccess($UnbanRows);
    }
    /**
     * 批量显示文章
     *
     * @param type var Description
     **/
    public function doEnablePosts(Request $request)
    {
        if(!$request->has('ids')){
            return parent::ajaxError('操作异常，请刷新重试');
        }
        $ids = explode(",",$request->input('ids'));
        $enableRows = Posts::withTrashed()->whereIn('post_id',$ids)->update(['show'=>true]);
        $updateRows = PostRelation::withTrashed()->whereIn('post_id',$ids)->update(['show'=>true]);
        return parent::ajaxSuccess($enableRows);
    }
    /**
     * 批量隐藏文章
     *
     * @param type var Description
     **/
    public function doDisablePosts(Request $request)
    {
        if(!$request->has('ids')){
            return parent::ajaxError('操作异常，请刷新重试');
        }
        $ids = explode(",",$request->input('ids'));
        $enableRows = Posts::withTrashed()->whereIn('post_id',$ids)->update(['show'=>false]);
        $updateRows = PostRelation::withTrashed()->whereIn('post_id',$ids)->update(['show'=>false]);
        return parent::ajaxSuccess($enableRows);
    }
    /**
     * 显示/隐藏文章
     *
     * @param type var Description
     **/
    public function doEnablePost(Request $request)
    {
        if(!$request->has('post_id')){
            return parent::ajaxError('操作异常，请刷新重试');
        }
        $where = [
            'post_id' => $request->input('post_id')
        ];
        $Post = Posts::withTrashed()->where($where)->first();
        if(!$Post){
            return parent::ajaxError('文章不存在');
        }
        $enableRows = Posts::withTrashed()->where('post_id',$Post->post_id)->update(['show'=>true]);
        $updateRows = PostRelation::withTrashed()->where('post_id',$Post->post_id)->update(['show'=>true]);
        return parent::ajaxSuccess($enableRows);
    }
    /**
     * 显示/隐藏文章
     *
     * @param type var Description
     **/
    public function doDisablePost(Request $request)
    {
        if(!$request->has('post_id')){
            return parent::ajaxError('操作异常，请刷新重试');
        }
        $where = [
            'post_id' => $request->input('post_id')
        ];
        $Post = Posts::withTrashed()->where($where)->first();
        if(!$Post){
            return parent::ajaxError('文章不存在');
        }
        $enableRows = Posts::withTrashed()->where('post_id',$Post->post_id)->update(['show'=>false]);
        $updateRows = PostRelation::withTrashed()->where('post_id',$Post->post_id)->update(['show'=>false]);
        return parent::ajaxSuccess($enableRows);
    }
    /**
     * 批量恢复
     *
     * @param type var Description
     **/
    public function doRestores(Request $request)
    {
        if(!$request->has('ids')){
            return parent::ajaxError('操作异常，请刷新重试');
        }
        $ids = explode(",",$request->input('ids'));
        $restoreRows = Posts::withTrashed()->whereIn('post_id',$ids)->restore();
        $updateRows = PostRelation::withTrashed()->whereIn('post_id',$ids)->restore();
        return parent::ajaxSuccess($restoreRows);
    }  
    /**
     * 恢复
     *
     * @param type var Description
     **/
    public function doRestore(Request $request)
    {
        if(!$request->has('post_id')){
            return parent::ajaxError('操作异常，请刷新重试');
        }
        $where = [
            'post_id' => $request->input('post_id')
        ];
        $Post = Posts::withTrashed()->where($where)->first();
        if(!$Post){
            return parent::ajaxError('文章不存在');
        }
        $restoreRows = Posts::withTrashed()->where('post_id',$Post->post_id)->restore();
        $updateRows = PostRelation::withTrashed()->where('post_id',$Post->post_id)->restore();
        return parent::ajaxSuccess($restoreRows);
    }    
    /**
     * 文章分类列表
     *
     * @param type var Description
     **/
    public function classify(Request $request)
    {
        if($request->has('id')){
            $father = $request->get('id');
        }else{
            $father = 0;
        }
        $Classifys = PostClassify::where('father' , 0)->get();
        $ClassifyInfo = PostClassify::where('pc_id' , $father)->first();
        if(!$ClassifyInfo && $father != 0){
            return parent::_error('当前分类不存在');
        }
        $data = [
            'classifys' => $Classifys, # 所有的一级分类
            'classifyInfo' => $ClassifyInfo, # 某一级分类下的所有二级分类
        ];
        return view('web.v1.admin.article.classify.index' , $data);
    }
    /**
     * 分类添加
     *
     * @param type var Description
     **/
    public function classifyAdd(Request $request)
    {
        $data = [];
        if($request->has('id')){
            $id = $request->get('id');
            $Classify = PostClassify::where('pc_id' , $id)->first();
            if(!$Classify){
                return parent::_error('一级分类已不存在' , 'modal');
            }
            $data = [
                'classify' => $Classify
            ];
        }
        return view('web.v1.admin.article.classify.add' , $data);
    }
    /**
     * 添加分类[Action]
     *
     * 执行分类的添加
     *
     * @param type var Description
     **/
    public function doClassifyAdd(ClassifyAddPost $request)
    {
        $Classify = new PostClassify;
        if(!$request->has('id')){
            $Classify->father = 0;
        }else{
            $Classify->father = $request->input('id');
        }
        $Classify->name = $request->input('name');
        if($request->has('key')) {
            $Classify->key = $request->input('key');
        }
        $Classify->show = $request->input('show') == '1' ? true : false;;
        if($request->has('intro')){
            $Classify->intro = $request->input('intro');
        }
        $rs = $Classify->save();
        if(!$rs){
            return parent::ajaxError( '服务器异常，请稍后再试' );
        }else{
            # 子类修改后，父类updated_at应该修改
            if($Classify->father != '0'){
                $Father = PostClassify::where('pc_id',$Classify->father)->first();
                $Father->updated_at = date('Y-m-d H:i:s',time());
                $Father->save();
            }
            return parent::ajaxSuccess( '添加成功' );
        }
    }

    /**
     * 分类修改
     *
     * @param type var Description
     **/
    public function classifyEdit(Request $request)
    {
        $data = [];
        if(!$request->has('id')){
            return parent::_error('你可能打开了一个假页面','modal');
        }
        $id = $request->get('id');
        $Classify = PostClassify::where('pc_id' , $id)->first();
        if(!$Classify){
            return parent::_error('该分类不存在' , 'modal');
        }
        $data = [
            'classify' => $Classify
        ];
        return view('web.v1.admin.article.classify.edit' , $data);
    }

    /**
     * 分类修改
     *
     * @param type var Description
     **/
    public function doClassifyEdit(ClassifyAddPost $request)
    {
        if(!$request->has('id')){
            return parent::ajaxError('非法操作');
        }
        $id = $request->input('id');
        $Classify = PostClassify::where('pc_id' , $id)->first();
        if(!$Classify){
            return parent::ajaxError('当前分类不存在');
        }
        $Classify->name = $request->input('name');
        if($request->has('key')) {
            $Classify->key = $request->input('key');
        }
        $Classify->show = $request->input('show') == '1' ? true : false;
        if($request->has('intro')){
            $Classify->intro = $request->input('intro');
        }
        $rs = $Classify->save();
        if(!$rs){
            return parent::ajaxError( '服务器异常，请稍后再试' );
        }else{
            # 子类修改后，父类updated_at应该修改
            if($Classify->father != '0'){
                $Father = PostClassify::where('pc_id',$Classify->father)->first();
                $Father->updated_at = date('Y-m-d H:i:s',time());
                $Father->save();
            }
            return parent::ajaxSuccess( '修改成功' );
        }
    }
    /**
     * 删除分类
     *
     * @param type var Description
     **/
    public function doClassifyDel(Request $request)
    {
        # 检测分类下是否有分类
        if(!$request->has('id')){
            return parent::ajaxError('操作异常，请稍后重试');
        }
        $Classify = PostClassify::where('pc_id',$request->input('id'))->first();
        if(!$Classify){
            return parent::ajaxError('当前分类不存在');
        }
        if($Classify->father == '0'){
            # 一级分类，检测是否有子分类
            if($Classify->children->count() > 0){
                return parent::ajaxError('有二级分类存在，无法删除');
            }
        }
        # 检测分类下是否有文章
        $PostCnt = PostRelation::where('pc_id',$Classify->pc_id)->count();
        if($PostCnt > 0){
           # 如果有关系建立，不允许删除
           return parent::ajaxError('分类下有文章关联，无法删除'); 
        }
        if(!$Classify->delete()){
            return parent::ajaxError('服务器异常，请稍后重试');
        }
        return parent::ajaxSuccess('删除成功');
    }
}
