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
use App\Http\Requests\Article\SingleEditPost;
use App\Http\Requests\Article\ImgTextEditPost;
use App\Http\Requests\Article\ImgTextItemEditPost;

class ArticleController extends Controller
{
    /**
     * 新闻列表[服务号]
     *
     * 限制用户查看，仅展示用户当前的服务号下的新闻
     *
     * @param type var Description
     **/
    public function index(Request $request)
    {
        $where = ['gid' => self::$user['gid']]; # 查询的条件
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
        # 判断分类，因为分类很复杂，需要先验证分类是不是这个人可以查询，然后从两者关系表取出所有的IDS，再用whereIn来查询
        if($request->has('classify')){
            $search['classify'] = $request->get('classify');
            $cid = $request->get('classify');
            if($cid !== '0'){
                # 证明存在的，那就获取他的所有文章ID
                $PostRelations = PostRelation::where('pc_id',$cid)->get();
                if($PostRelations != null){
                    foreach($PostRelations as $relation){
                        $post_ids[] = $relation->post_id;
                    }
                }
            }
        }
        if(count($post_ids) > 0){
            if($key == null){
                $Posts = Posts::where($where)
                        ->whereIn('post_id',$post_ids)
                        ->orderBy('created_at','desc')
                        ->paginate(20);
            }else{
                $Posts = Posts::where($where)
                        ->whereIn('post_id',$post_ids)
                        ->where(function($query) use ($key){
                            $query->orWhere('title','like','%'.$key.'%')->orWhere('source','like','%'.$key.'%')->orWhere('tags','like','%'.$key.'%');
                        })
                        ->orderBy('created_at','desc')
                        ->paginate(20);
            }
        }else{
            if($key == null){
                $Posts = Posts::where($where)
                        ->orderBy('created_at','desc')
                        ->paginate(20);
            }else{
                $Posts = Posts::where($where)
                        ->where(function($query) use ($key){
                            $query->orWhere('title','like','%'.$key.'%')->orWhere('source','like','%'.$key.'%')->orWhere('tags','like','%'.$key.'%');
                        })
                        ->orderBy('created_at','desc')
                        ->paginate(20);
            }
        }
        $Classifys = PostClassify::where('father' , 0)->get();
        $data = [
            'posts' => $Posts,
            'classifys' => $Classifys,
            'search' => $search
        ];
        return view('web.v1.admin.article.index',$data);
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
            'gid' => self::$user['gid'],
            'post_id' => $request->input('post_id'),
        ];
        $Post = Posts::where($where)->first();
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
     * 选择发布类型
     *
     * @param type var Description
     **/
    public function choose(Request $request)
    {
        return view('web.v1.admin.article.choose');
    }
    /**
     * 普通模式发布
     *
     * @param type var Description
     **/
    public function single(Request $request)
    {
        $Classifys = PostClassify::where('father' , 0)->get();
        $data = [
            'classifys' => $Classifys, # 所有的一级分类
        ];
        return view('web.v1.admin.article.create.single',$data);
    }
    /**
     * 发布普通模式[Action]
     *
     * 根据提交的信息，进行分类表、主体表、内容表的更新。
     *
     * @param type var Description
     **/
    public function doSingle(SingleEditPost $request)
    {
        # 进行主体的存储
        $Post = new Posts;
        $Post->title = $request->input('title'); # 标题
        $Post->source = $request->input('source'); # 来源
        $Post->single = true; # 设置为单图文
        $Post->show = $request->input('show') == '1' ? true : false; # 是否显示
        # 拆、拼装标签
        if($request->has('tags')){
            $tagsArray = explode(',',$request->input('tags'));
            if(is_array($tagsArray)){
                $Post->tags = implode(',',$tagsArray);
            }
        }
        # 拆、拼装封面图
        if($request->has('imgs')){
            $imgsArray = explode(',',$request->input('imgs'));
            if(is_array($imgsArray)){
                $Post->cover = implode(',',$imgsArray);
            }
        }
        # 是否是定时
        if($request->has('interval')){
            $Post->interval = strtotime($request->input('interval'));
        }else{
            $Post->interval = 0;
        }
        $Post->gid = self::$user['gid'];
        $Post->status = self::canRelease();
        $postRs = $Post->save();
        if(!$postRs){
            return parent::ajaxError('服务器异常，请稍后重试');
        }
        # 绑定分类关系
        $classifys = $request->input('classify');  # 该值是数组
        $bindRows = 0; # 绑定成功的数量
        if(count($classifys) > 4){
            return parent::ajaxError('最多选择4个分类发布');
        }
        foreach($classifys as $classify){
            $ClassifyRelation = new PostRelation;
            $ClassifyRelation->post_id = $Post->post_id;
            $ClassifyRelation->pc_id = $classify;
            if($ClassifyRelation->save()){
                $bindRows++; # 过滤掉非法的ID，并将有效的绑定进行计数
            }
        }
        if($bindRows <= 0){
            # 分类关系未建立成功，回滚
            $Post->forceDelete();
            return parent::ajaxError('选择的分类无效，请重新选择');
        }
        # 添加内容
        $PostContent = new PostContent;
        $PostContent->content = $request->input('content');
        $PostContent->show = true;
        $PostContent->post_id = $Post->post_id;
        if(!$PostContent->save()){
            # 回滚
            PostRelation::where('post_id',$Post->post_id)->forceDelete();
            $Post->forceDelete();
            return parent::ajaxError('服务器异常，请稍后重试');
        }
        parent::GroupLogger('insert',$Post->post_id,"[普通]标题:".$Post->title,"POSTS");
        return parent::ajaxSuccess('保存成功');
    }
    /**
     * 普通模式修改
     *
     * @param type var Description
     **/
    public function editSingle(Request $request)
    {
        $Classifys = PostClassify::where('father' , 0)->get();
        if(!$request->has('post_id')){
            return parent::_error('操作异常，请稍后重试');
        }
        $where = [
            'gid' => self::$user['gid'],
            'post_id' => $request->input('post_id'),
            'single' => true
        ];
        $Post = Posts::where($where)->first();
        if(!$Post){
            return parent::_error('该文章不存在');
        }
        if($Post->cover != null){
            $pictures = explode(",",$Post->cover);
            $imgs = [];
            foreach($pictures as $key => $picture){
                $file = Resources::where('id' , $picture)->first();
                if(in_array($file->type,['png','jpg','jpeg','gif','bmp'])){
                    $imgs[] = parent::ResourcePath($file->path,$file->filename);
                }
            }
            $Post->images = $imgs;
        }
        $ClassifyIds = [];
        foreach($Post->ClassifyRelation as $ClassifyRelation){
            $ClassifyIds[] = $ClassifyRelation->pc_id;
        }
        $data = [
            'post' => $Post,
            'classifys' => $Classifys,
            'classifyIds' => $ClassifyIds
        ];
        return view('web.v1.admin.article.edit.single', $data);
    }
    /**
     * 修改普通模式[Action]
     *
     * 无回滚
     *
     * @param type var Description
     **/
    public function doEditSingle(SingleEditPost $request)
    {
        if(!$request->has('post_id')){
            return parent::_error('操作异常，请稍后重试');
        }
        $where = [
            'gid' => self::$user['gid'],
            'post_id' => $request->input('post_id'),
            'single' => true
        ];
        $Post = Posts::where($where)->first();
        if(!$Post){
            return parent::_error('该文章不存在');
        }
        # 进行主体的存储
        $Post->title = $request->input('title'); # 标题
        $Post->source = $request->input('source'); # 来源
        $Post->single = true; # 设置为单图文
        $Post->show = $request->input('show') == '1' ? true : false; # 是否显示
        # 拆、拼装标签
        if($request->has('tags')){
            $tagsArray = explode(',',$request->input('tags'));
            if(is_array($tagsArray)){
                $Post->tags = implode(',',$tagsArray);
            }
        }
        # 拆、拼装封面图
        if($request->has('imgs')){
            $imgsArray = explode(',',$request->input('imgs'));
            if(is_array($imgsArray)){
                $Post->cover = implode(',',$imgsArray);
            }
        }
        # 是否是定时
        if($request->has('interval')){
            $Post->interval = strtotime($request->input('interval'));
        }else{
            $Post->interval = 0;
        }
        $Post->status = self::canRelease();
        $postRs = $Post->save();
        if(!$postRs){
            return parent::ajaxError('服务器异常，请稍后重试');
        }
        # 绑定分类关系
        $classifys = $request->input('classify');  # 该值是数组
        $bindRows = 0; # 绑定成功的数量
        if(count($classifys) > 4){
            return parent::ajaxError('最多选择4个分类发布');
        }
        # 清除分类
        PostRelation::where('post_id',$Post->post_id)->forceDelete();
        # 循环添加
        foreach($classifys as $classify){
            $ClassifyRelation = new PostRelation;
            $ClassifyRelation->post_id = $Post->post_id;
            $ClassifyRelation->pc_id = $classify;
            if($ClassifyRelation->save()){
                $bindRows++; # 过滤掉非法的ID，并将有效的绑定进行计数
            }
        }
        if($bindRows <= 0){
            return parent::ajaxError('选择的分类无效，请重新选择');
        }
        # 添加内容
        $PostContent = PostContent::where('post_id',$Post->post_id)->first();
        $PostContent->content = $request->input('content');
        $PostContent->show = true;
        if(!$PostContent->save()){
            return parent::ajaxError('服务器异常，请稍后重试');
        }
        parent::GroupLogger('update',$Post->post_id,"[普通]标题:".$Post->title,"POSTS");
        return parent::ajaxSuccess('保存成功');
    }

    /**
     * 普通模式发布
     *
     * @param type var Description
     **/
    public function markdown(Request $request)
    {
        $Classifys = PostClassify::where('father' , 0)->get();
        $data = [
            'classifys' => $Classifys, # 所有的一级分类
        ];
        return view('web.v1.admin.article.create.markdown',$data);
    }

    /**
     * 发布Markdown模式[Action]
     *
     * 根据提交的信息，进行分类表、主体表、内容表的更新。
     *
     * @param type var Description
     **/
    public function doMarkdown(SingleEditPost $request)
    {
        # 进行主体的存储
        $Post = new Posts;
        $Post->title = $request->input('title'); # 标题
        $Post->source = $request->input('source'); # 来源
        $Post->single = true; # 设置为单图文
        $Post->markdown = true; # 设置为单图文
        $Post->show = $request->input('show') == '1' ? true : false; # 是否显示
        # 拆、拼装标签
        if($request->has('tags')){
            $tagsArray = explode(',',$request->input('tags'));
            if(is_array($tagsArray)){
                $Post->tags = implode(',',$tagsArray);
            }
        }
        # 拆、拼装封面图
        if($request->has('imgs')){
            $imgsArray = explode(',',$request->input('imgs'));
            if(is_array($imgsArray)){
                $Post->cover = implode(',',$imgsArray);
            }
        }
        # 是否是定时
        if($request->has('interval')){
            $Post->interval = strtotime($request->input('interval'));
        }else{
            $Post->interval = 0;
        }
        $Post->gid = self::$user['gid'];
        $Post->status = self::canRelease();
        $postRs = $Post->save();
        if(!$postRs){
            return parent::ajaxError('服务器异常，请稍后重试');
        }
        # 绑定分类关系
        $classifys = $request->input('classify');  # 该值是数组
        $bindRows = 0; # 绑定成功的数量
        if(count($classifys) > 4){
            return parent::ajaxError('最多选择4个分类发布');
        }
        foreach($classifys as $classify){
            $ClassifyRelation = new PostRelation;
            $ClassifyRelation->post_id = $Post->post_id;
            $ClassifyRelation->pc_id = $classify;
            if($ClassifyRelation->save()){
                $bindRows++; # 过滤掉非法的ID，并将有效的绑定进行计数
            }
        }
        if($bindRows <= 0){
            # 分类关系未建立成功，回滚
            $Post->forceDelete();
            return parent::ajaxError('选择的分类无效，请重新选择');
        }
        # 添加内容
        $PostContent = new PostContent;
        $PostContent->content = $request->input('content');
        $PostContent->show = true;
        $PostContent->post_id = $Post->post_id;
        if(!$PostContent->save()){
            # 回滚
            PostRelation::where('post_id',$Post->post_id)->forceDelete();
            $Post->forceDelete();
            return parent::ajaxError('服务器异常，请稍后重试');
        }
        parent::GroupLogger('insert',$Post->post_id,"[Markdown]标题:".$Post->title,"POSTS");
        return parent::ajaxSuccess('保存成功');
    }
    
    /**
     * Markdown模式修改
     *
     * @param type var Description
     **/
    public function editMarkdown(Request $request)
    {
        $Classifys = PostClassify::where('father' , 0)->get();
        if(!$request->has('post_id')){
            return parent::_error('操作异常，请稍后重试');
        }
        $where = [
            'gid' => self::$user['gid'],
            'post_id' => $request->input('post_id'),
            'single' => true,
            'markdown' => true
        ];
        $Post = Posts::where($where)->first();
        if(!$Post){
            return parent::_error('该文章不存在');
        }
        if($Post->cover != null){
            $pictures = explode(",",$Post->cover);
            $imgs = [];
            foreach($pictures as $key => $picture){
                $file = Resources::where('id' , $picture)->first();
                if(in_array($file->type,['png','jpg','jpeg','gif','bmp'])){
                    $imgs[] = parent::ResourcePath($file->path,$file->filename);
                }
            }
            $Post->images = $imgs;
        }
        $ClassifyIds = [];
        foreach($Post->ClassifyRelation as $ClassifyRelation){
            $ClassifyIds[] = $ClassifyRelation->pc_id;
        }
        $data = [
            'post' => $Post,
            'classifys' => $Classifys,
            'classifyIds' => $ClassifyIds
        ];
        return view('web.v1.admin.article.edit.markdown', $data);
    }

    /**
     * 修改Markdown模式[Action]
     *
     * 根据提交的信息，进行分类表、主体表、内容表的更新。
     *
     * @param type var Description
     **/
    public function doEditMarkdown(SingleEditPost $request)
    {
        if(!$request->has('post_id')){
            return parent::_error('操作异常，请稍后重试');
        }
        $where = [
            'gid' => self::$user['gid'],
            'post_id' => $request->input('post_id'),
            'single' => true,
            'markdown' => true
        ];
        $Post = Posts::where($where)->first();
        if(!$Post){
            return parent::_error('该文章不存在');
        }
        # 进行主体的存储
        $Post->title = $request->input('title'); # 标题
        $Post->source = $request->input('source'); # 来源
        $Post->single = true; # 设置为单图文
        $Post->markdown = true; # 设置为单图文
        $Post->show = $request->input('show') == '1' ? true : false; # 是否显示
        # 拆、拼装标签
        if($request->has('tags')){
            $tagsArray = explode(',',$request->input('tags'));
            if(is_array($tagsArray)){
                $Post->tags = implode(',',$tagsArray);
            }
        }
        # 拆、拼装封面图
        if($request->has('imgs')){
            $imgsArray = explode(',',$request->input('imgs'));
            if(is_array($imgsArray)){
                $Post->cover = implode(',',$imgsArray);
            }
        }
        # 是否是定时
        if($request->has('interval')){
            $Post->interval = strtotime($request->input('interval'));
        }else{
            $Post->interval = 0;
        }
        $Post->status = self::canRelease();
        $postRs = $Post->save();
        if(!$postRs){
            return parent::ajaxError('服务器异常，请稍后重试');
        }
        # 绑定分类关系
        $classifys = $request->input('classify');  # 该值是数组
        $bindRows = 0; # 绑定成功的数量
        if(count($classifys) > 4){
            return parent::ajaxError('最多选择4个分类发布');
        }
        # 清除分类
        PostRelation::where('post_id',$Post->post_id)->forceDelete();
        foreach($classifys as $classify){
            $ClassifyRelation = new PostRelation;
            $ClassifyRelation->post_id = $Post->post_id;
            $ClassifyRelation->pc_id = $classify;
            if($ClassifyRelation->save()){
                $bindRows++; # 过滤掉非法的ID，并将有效的绑定进行计数
            }
        }
        if($bindRows <= 0){
            return parent::ajaxError('选择的分类无效，请重新选择');
        }
        # 添加内容
        $PostContent = PostContent::where('post_id',$Post->post_id)->first();
        $PostContent->content = $request->input('content');
        $PostContent->show = true;
        if(!$PostContent->save()){
            return parent::ajaxError('服务器异常，请稍后重试');
        }
        parent::GroupLogger('update',$Post->post_id,"[Markdown]标题:".$Post->title,"POSTS");
        return parent::ajaxSuccess('保存成功');
    }
    /**
     * 图文模式发布
     *
     * @param type var Description
     **/
    public function imgText(Request $request)
    {
        $Classifys = PostClassify::where('father' , 0)->get();
        $data = [
            'classifys' => $Classifys, # 所有的一级分类
        ];
        return view('web.v1.admin.article.create.image-text',$data);
    }
    /**
     * 发布普通模式[Action]
     *
     * 根据提交的信息，进行分类表、主体表、内容表的更新。
     *
     * @param type var Description
     **/
    public function doImgText(ImgTextEditPost $request)
    {
        # 进行主体的存储
        $Post = new Posts;
        $Post->title = $request->input('title'); # 标题
        $Post->source = $request->input('source'); # 来源
        $Post->single = false; # 设置为单图文
        $Post->show = $request->input('show') == '1' ? true : false; # 是否显示
        # 拆、拼装标签
        if($request->has('tags')){
            $tagsArray = explode(',',$request->input('tags'));
            if(is_array($tagsArray)){
                $Post->tags = implode(',',$tagsArray);
            }
        }
        # 拆、拼装封面图
        if($request->has('imgs')){
            $imgsArray = explode(',',$request->input('imgs'));
            if(is_array($imgsArray)){
                $Post->cover = implode(',',$imgsArray);
            }
        }
        # 是否是定时
        if($request->has('interval')){
            $Post->interval = strtotime($request->input('interval'));
        }else{
            $Post->interval = 0;
        }
        $Post->gid = self::$user['gid'];
        $Post->status = self::canRelease();
        $postRs = $Post->save();
        if(!$postRs){
            return parent::ajaxError('服务器异常，请稍后重试');
        }
        # 绑定分类关系
        $classifys = $request->input('classify');  # 该值是数组
        $bindRows = 0; # 绑定成功的数量
        if(count($classifys) > 4){
            $Post->forceDelete();
            return parent::ajaxError('最多选择4个分类发布');
        }
        foreach($classifys as $classify){
            $ClassifyRelation = new PostRelation;
            $ClassifyRelation->post_id = $Post->post_id;
            $ClassifyRelation->pc_id = $classify;
            if($ClassifyRelation->save()){
                $bindRows++; # 过滤掉非法的ID，并将有效的绑定进行计数
            }
        }
        if($bindRows <= 0){
            # 分类关系未建立成功，回滚
            $Post->forceDelete();
            return parent::ajaxError('选择的分类无效，请重新选择');
        }
        parent::GroupLogger('insert',$Post->post_id,"[图文]标题:".$Post->title,"POSTS");
        return parent::ajaxSuccess(
            [
                'id' => $Post->post_id
            ]
        );
    }
    /**
     * 图文模式修改
     *
     * @param type var Description
     **/
    public function editImgText(Request $request)
    {
        $Classifys = PostClassify::where('father' , 0)->get();
        if(!$request->has('post_id')){
            return parent::_error('操作异常，请稍后重试');
        }
        $where = [
            'gid' => self::$user['gid'],
            'post_id' => $request->input('post_id'),
            'single' => false
        ];
        $Post = Posts::where($where)->first();
        if(!$Post){
            return parent::_error('该文章不存在');
        }
        if($Post->cover != null){
            $pictures = explode(",",$Post->cover);
            $imgs = [];
            foreach($pictures as $key => $picture){
                $file = Resources::where('id' , $picture)->first();
                if(in_array($file->type,['png','jpg','jpeg','gif','bmp'])){
                    $imgs[] = parent::ResourcePath($file->path,$file->filename);
                }
            }
            $Post->images = $imgs;
        }
        $ClassifyIds = [];
        foreach($Post->ClassifyRelation as $ClassifyRelation){
            $ClassifyIds[] = $ClassifyRelation->pc_id;
        }
        $data = [
            'post' => $Post,
            'classifys' => $Classifys,
            'classifyIds' => $ClassifyIds
        ];
        return view('web.v1.admin.article.edit.image-text', $data);
    }

    /**
     * 修改图文模式[Action]
     *
     * 根据提交的信息，进行分类表、主体表的更新。
     *
     * @param type var Description
     **/
    public function doEditImgText(ImgTextEditPost $request)
    {
        if(!$request->has('post_id')){
            return parent::_error('操作异常，请稍后重试');
        }
        $where = [
            'gid' => self::$user['gid'],
            'post_id' => $request->input('post_id'),
            'single' => false
        ];
        $Post = Posts::where($where)->first();
        if(!$Post){
            return parent::_error('该文章不存在');
        }
        # 进行主体的存储
        $Post->title = $request->input('title'); # 标题
        $Post->source = $request->input('source'); # 来源
        $Post->single = true; # 设置为单图文
        $Post->markdown = true; # 设置为单图文
        $Post->show = $request->input('show') == '1' ? true : false; # 是否显示
        # 拆、拼装标签
        if($request->has('tags')){
            $tagsArray = explode(',',$request->input('tags'));
            if(is_array($tagsArray)){
                $Post->tags = implode(',',$tagsArray);
            }
        }
        # 拆、拼装封面图
        if($request->has('imgs')){
            $imgsArray = explode(',',$request->input('imgs'));
            if(is_array($imgsArray)){
                $Post->cover = implode(',',$imgsArray);
            }
        }
        # 是否是定时
        if($request->has('interval')){
            $Post->interval = strtotime($request->input('interval'));
        }else{
            $Post->interval = 0;
        }
        $Post->status = self::canRelease();
        $postRs = $Post->save();
        if(!$postRs){
            return parent::ajaxError('服务器异常，请稍后重试');
        }
        # 绑定分类关系
        $classifys = $request->input('classify');  # 该值是数组
        $bindRows = 0; # 绑定成功的数量
        if(count($classifys) > 4){
            return parent::ajaxError('最多选择4个分类发布');
        }
        # 清除分类
        PostRelation::where('post_id',$Post->post_id)->forceDelete();
        foreach($classifys as $classify){
            $ClassifyRelation = new PostRelation;
            $ClassifyRelation->post_id = $Post->post_id;
            $ClassifyRelation->pc_id = $classify;
            if($ClassifyRelation->save()){
                $bindRows++; # 过滤掉非法的ID，并将有效的绑定进行计数
            }
        }
        if($bindRows <= 0){
            return parent::ajaxError('选择的分类无效，请重新选择');
        }
        parent::GroupLogger('update',$Post->post_id,"[图文]标题:".$Post->title,"POSTS");
        return parent::ajaxSuccess('保存成功');
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
        $Posts = Posts::whereIn('post_id',$ids)->where('gid',self::$user['gid'])->get();
        $trueIds = [];
        if($Posts != null) {
            foreach($Posts as $Post) {
                $trueIds[] = $Post->post_id;
            }
        }
        $deleteRows = Posts::whereIn('post_id',$trueIds)->delete();
        $updateRows = PostRelation::whereIn('post_id',$trueIds)->delete();
        parent::GroupLogger('delete',implode(',',$trueIds),"ID:{".implode(',',$trueIds)."}","POSTS");
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
        $where = [
            'gid' => self::$user['gid'],
            'post_id' => $request->input('post_id'),
        ];
        $Post = Posts::where($where)->first();
        if(!$Post){
            return parent::ajaxError('文章不存在');
        }
        $deleteRows = Posts::where('post_id',$Post->post_id)->where('gid',self::$user['gid'])->delete();
        $updateRows = PostRelation::where('post_id',$Post->post_id)->delete();
        parent::GroupLogger('delete',$Post->post_id,"标题:".$Post->title,"POSTS");
        return parent::ajaxSuccess($deleteRows);
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
        $Posts = Posts::whereIn('post_id',$ids)->where('gid',self::$user['gid'])->get();
        $trueIds = [];
        if($Posts != null) {
            foreach($Posts as $Post) {
                $trueIds[] = $Post->post_id;
            }
        }
        $enableRows = Posts::whereIn('post_id',$trueIds)->update(['show'=>true]);
        $updateRows = PostRelation::whereIn('post_id',$trueIds)->update(['show'=>true]);
        parent::GroupLogger('update',implode(',',$trueIds),"[显示]ID:{".implode(',',$trueIds)."}","POSTS");
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
        $Posts = Posts::whereIn('post_id',$ids)->where('gid',self::$user['gid'])->get();
        $trueIds = [];
        if($Posts != null) {
            foreach($Posts as $Post) {
                $trueIds[] = $Post->post_id;
            }
        }
        $enableRows = Posts::whereIn('post_id',$trueIds)->update(['show'=>false]);
        $updateRows = PostRelation::whereIn('post_id',$trueIds)->update(['show'=>false]);
        parent::GroupLogger('update',implode(',',$trueIds),"[隐藏]ID:{".implode(',',$trueIds)."}","POSTS");
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
            'gid' => self::$user['gid'],
            'post_id' => $request->input('post_id'),
        ];
        $Post = Posts::where($where)->first();
        if(!$Post){
            return parent::ajaxError('文章不存在');
        }
        $deleteRows = Posts::where('post_id',$Post->post_id)->where('gid',self::$user['gid'])->update(['show'=>true]);
        $updateRows = PostRelation::where('post_id',$Post->post_id)->update(['show'=>true]);
        parent::GroupLogger('update',$Post->post_id,"[显示]标题:".$Post->title,"POSTS");
        return parent::ajaxSuccess($deleteRows);
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
            'gid' => self::$user['gid'],
            'post_id' => $request->input('post_id'),
        ];
        $Post = Posts::where($where)->first();
        if(!$Post){
            return parent::ajaxError('文章不存在');
        }
        $deleteRows = Posts::where('post_id',$Post->post_id)->where('gid',self::$user['gid'])->update(['show'=>false]);
        $updateRows = PostRelation::where('post_id',$Post->post_id)->update(['show'=>false]);
        parent::GroupLogger('update',$Post->post_id,"[隐藏]标题:".$Post->title,"POSTS");
        return parent::ajaxSuccess($deleteRows);
    }

    /**
     * 图文信息条目管理
     *
     * Undocumented function long description
     *
     * @param type var Description
     **/
    public function ImgTextItem(Request $request)
    {
        if(!$request->has('post_id')){
            return parent::_error('操作异常，请刷新后重试');
        }
        $where = [
            'gid' => self::$user['gid'],
            'post_id' => $request->input('post_id'),
            'single' => false
        ];
        $Post = Posts::where($where)->first();
        if(!$Post){
            return parent::_error('当前图文不存在');
        }
        $PostContents = PostContent::where('post_id' , $Post->post_id)->orderBy('sort','asc')->orderBy('created_at', 'desc')->get();
        if($PostContents){
            foreach($PostContents as $key => $PostContent){
                if($PostContent->picture != null){
                    $file = Resources::where('id' , $PostContent->picture)->first();
                    if($file != null){
                        $PostContents[$key]->picture = parent::ResourcePath($file->path,$file->filename);
                    }
                }
            }
        }
        $data = [
            'post' => $Post,
            'items' => $PostContents
        ];
        return view('web.v1.admin.article.image-text.item', $data);
    }

    /**
     * 获取条目列表
     *
     * @param type var Description
     **/
    public function getImgTextItems(Request $request)
    {
        if(!$request->has('post_id')){
            return parent::ajaxError('操作异常，请稍后重试');
        }
        $where = [
            'gid' => self::$user['gid'],
            'post_id' => $request->input('post_id'),
            'single' => false
        ];
        $Post = Posts::where($where)->first();
        if(!$Post){
            return parent::ajaxError('操作异常，请稍后重试');
        }
        $PostContents = PostContent::where('post_id' , $Post->post_id)->orderBy('sort','asc')->orderBy('created_at', 'desc')->get();
        if($PostContents){
            foreach($PostContents as $key => $PostContent){
                if($PostContent->picture != null){
                    $file = Resources::where('id' , $PostContent->picture)->first();
                    if($file != null){
                        $PostContents[$key]->picture = parent::ResourcePath($file->path,$file->filename);
                    }
                }
            }
        }
        return parent::ajaxSuccess($PostContents);
    }

    /**
     * 获取条目详情
     *
     * @param type var Description
     **/
    public function getImgTextItem(Request $request)
    {
        if(!$request->has('post_id') || !$request->has('pc_id')){
            return parent::ajaxError('操作异常，请稍后重试');
        }
        $pc_id = $request->input('pc_id');
        $where = [
            'gid' => self::$user['gid'],
            'post_id' => $request->input('post_id'),
            'single' => false
        ];
        $Post = Posts::where($where)->first();
        if(!$Post){
            return parent::ajaxError('操作异常，请稍后重试');
        }
        $PostContent = PostContent::where('post_id' , $Post->post_id)->where('pc_id',$pc_id)->first();
        if(!$PostContent){
            return parent::ajaxError('该条目不存在');
        }
        if($PostContent->picture != null){
            $file = Resources::where('id' , $PostContent->picture)->first();
            if($file != null){
                $PostContent->image = parent::ResourcePath($file->path,$file->filename);
            }
        }
        return parent::ajaxSuccess($PostContent);
    }
    /**
     * 条目添加
     *
     * @param type var Description
     **/
    public function doAddImgTextItem(ImgTextItemEditPost $request)
    {
        if(!$request->has('post_id')){
            return parent::ajaxError('操作异常，请稍后重试');
        }
        $where = [
            'gid' => self::$user['gid'],
            'post_id' => $request->input('post_id'),
            'single' => false
        ];
        $Post = Posts::where($where)->first();
        if(!$Post){
            return parent::ajaxError('操作异常，请稍后重试');
        }
        # 过滤掉多余的图片
        $imgs = explode(',',$request->input('image'));
        $img = $imgs[0];
        # 添加条目
        $PostItem = new PostContent;
        $PostItem->content = $request->input('content');
        $PostItem->picture = $img;
        $PostItem->sort = $request->input('sort');
        $PostItem->show = $request->input('show');
        $PostItem->post_id = $Post->post_id;
        if(!$PostItem->save()){
            return parent::ajaxError('服务器异常，请稍后重试');
        }else{
            parent::GroupLogger('insert',$Post->post_id,"标题:".$Post->title,"POSTS");
            return parent::ajaxSuccess('添加成功');
        }
    }
    /**
     * 条目删除
     *
     * @param type var Description
     **/
    public function doDelImgTextItem(Request $request)
    {
        if(!$request->has('post_id') || !$request->has('pc_id')){
            return parent::ajaxError('操作异常，请稍后重试');
        }
        $pc_id = $request->input('pc_id');
        $where = [
            'gid' => self::$user['gid'],
            'post_id' => $request->input('post_id'),
            'single' => false
        ];
        $Post = Posts::where($where)->first();
        if(!$Post){
            return parent::ajaxError('操作异常，请稍后重试');
        }
        # 删除条目
        $deleteRows = PostContent::where('pc_id',$pc_id)->where('post_id',$Post->post_id)->delete();
        if(!$deleteRows){
            return parent::ajaxError('服务器异常，请稍后重试');
        }else{
            parent::GroupLogger('delete',$Post->post_id,"标题:".$Post->title,"POSTS");
            return parent::ajaxSuccess('成功删除 '. $deleteRows . " 条数据");
        }
    }

    /**
     * 条目修改
     *
     * @param type var Description
     **/
    public function doEditImgTextItem(ImgTextItemEditPost $request)
    {
        if(!$request->has('post_id') || !$request->has('pc_id')){
            return parent::ajaxError('操作异常，请稍后重试');
        }
        $pc_id = $request->input('pc_id'); # 条目ID
        $where = [
            'gid' => self::$user['gid'],
            'post_id' => $request->input('post_id'),
            'single' => false
        ];
        $Post = Posts::where($where)->first();
        if(!$Post){
            return parent::ajaxError('操作异常，请稍后重试');
        }
        # 过滤掉多余的图片
        $imgs = explode(',',$request->input('image'));
        $img = $imgs[0];
        # 修改条目
        $PostItem = PostContent::where('pc_id',$pc_id)->where('post_id',$Post->post_id)->first();
        $PostItem->content = $request->input('content');
        $PostItem->picture = $img;
        $PostItem->sort = $request->input('sort');
        $PostItem->show = $request->input('show');
        if(!$PostItem->save()){
            return parent::ajaxError('服务器异常，请稍后重试');
        }else{
            parent::GroupLogger('update',$Post->post_id,"标题:".$Post->title,"POSTS");
            return parent::ajaxSuccess('保存成功');
        }
    }

    /**
     * 检验是否可以免审核发布文章
     *
     * @param type var Description
     **/
    public function canRelease(){
        $Group = Groups::where('gid',self::$user['gid'])->first();
        if($Group != null) {
            # 不需要审核
            if($Group->post_audit != '1'){
                return true;
            }
        }
        return false;
    }
}