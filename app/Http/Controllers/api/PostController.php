<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\api\Controller;

use App\Models\Posts;
use App\Models\PostContent;
use App\Models\PostRelation;
use App\Models\Resources;

class PostController extends Controller
{
    /**
     * 获取文章列表
     *
     * @param type var Description
     **/
    public function post_list()
    {
        $where = [
            'show' => true,
            'status' => true
        ];
        $Posts = Posts::where($where)
                        ->orderBy('top_time','desc')
                        ->orderBy('updated_at','desc')
                        ->paginate(10);
        $count = Posts::where($where)->count();
        $respData = [
            'count' => $count,
            'list'  => self::formatPost($Posts)
        ];
        return parent::respSuccess($respData);
    }
    /**
     * 根据分类ID获取文章
     *
     * Undocumented function long description
     *
     * @param type var Description
     **/
    public function post_classify_list(Request $request)
    {
        if(!$request->has('cid')){
            return parent::respError('403','[cid]缺失');
        }
        $where = [
            'pc_id' => $request->input('cid'),
            'status' => true,
            'show' => true
        ];
        $PostCount = PostRelation::where($where)->count();
        # 节约查询资源，没有数据就直接返回了
        if($PostCount <= 0){
            return parent::respSuccess([
                'count' => $PostCount,
                'list'  => []
            ]);
        }

        $PostRelations = PostRelation::where($where)
                        ->orderBy('top_time','desc')
                        ->orderBy('created_at','desc')
                        ->paginate(10);
        $ids = [];
        foreach($PostRelations as $PostRelation){
            $ids[] = $PostRelation->post_id;
        }
        $Posts = Posts::whereIn('post_id',$ids)->get();
        $respData = [
            'count' => $PostCount,
            'list'  => self::formatPost($Posts)
        ];
        return parent::respSuccess($respData);
    }
    /**
     * 根据ID获取内容详情
     *
     * @param type var Description
     **/
    public function post_content(Request $request)
    {
        if(!$request->has('id')){
            return parent::respError('405','[id]缺失');
        }
        $where = [
            'post_id' => $request->input('id'),
            'show' => true,
            'status' => true
        ];
        $Post = Posts::where($where)->first();
        if(!$Post){
            return parent::respError('404');
        }
        return parent::respSuccess(self::formatPost($Post,false));
    }
    /**
     * 根据提供的数据，将文章数据进行统一格式化，支持：[文章(Object)数组] [单篇文章] 
     *
     * @param type var Description
     * @param type $isList 是否是列表，默认列表
     **/
    private function formatPost($Posts,$isList = true)
    {
        $trueList = [];
        if(is_object($Posts)) {
            if($isList === true)
            {
                foreach($Posts as $Post)
                {
                    $trueCover = [];
                    if($Post->cover != null){
                        $Resources = Resources::whereIn('id',$Post->cover)->get();
                        if($Resources != null){
                            foreach($Resources as $Resource){
                                $trueCover[] = parent::ResourcePath($Resource->path,$Resource->filename);
                            }
                        }
                    }
                    $trueList[] = [
                        'id'     => $Post->post_id,
                        'title'  => $Post->title,
                        'source' => $Post->source,
                        'single' => $Post->single,
                        'markdown' => $Post->markdown,
                        'cover'    => $trueCover,
                        'hit'      => $Post->hit,
                        'tags'     => $Post->tags != null ? explode(',',$Post->tags) : [],
                        'likes'    => $Post->likes,
                        'comments' => $Post->comments,
                        'stars'    => $Post->stars,
                        'user'     => [
                            'id'     => $Post->Group->gid,
                            'avatar' => $Post->Group->avatar == null ? parent::ResourcePath() : $Post->Group->avatar,
                            'name'   => $Post->Group->name
                        ],
                        'time'     => parent::formatDate($Post->created_at->format('Y-m-d H:i:s'))
                    ];
                }
            }else{
                $trueCover = [];
                if($Posts->cover != null){
                    $Resources = Resources::whereIn('id',$Posts->cover)->get();
                    if($Resources != null){
                        foreach($Resources as $Resource){
                            $trueCover[] = parent::ResourcePath($Resource->path,$Resource->filename);
                        }
                    }
                }
                $trueList = [
                    'id'     => $Posts->post_id,
                    'title'  => $Posts->title,
                    'source' => $Posts->source,
                    'single' => $Posts->single,
                    'markdown' => $Posts->markdown,
                    'cover'    => $trueCover,
                    'hit'      => $Posts->hit,
                    'tags'     => $Posts->tags != null ? explode(',',$Posts->tags) : [],
                    'likes'    => $Posts->likes,
                    'comments' => $Posts->comments,
                    'stars'    => $Posts->stars,
                    'user'     => [
                        'id'     => $Posts->Group->gid,
                        'avatar' => $Posts->Group->avatar == null ? parent::ResourcePath() : $Posts->Group->avatar,
                        'name'   => $Posts->Group->name
                    ],
                    'time'     => parent::formatDate($Posts->created_at->format('Y-m-d H:i:s'))
                ];
                if($Posts->single == '1') {
                    # Markdown/普通文档
                    $trueList['content'] = $Posts->Content->content;
                }else {
                    $Contents = PostContent::where('post_id',$Posts->post_id)
                                ->orderBy('sort','asc')
                                ->orderBy('created_at','desc')
                                ->get();
                    if($Contents != null) {
                        foreach ($Contents as $Content) {
                            $image = "";
                            $Resource = Resources::where('id',$Content->picture)->first();
                            if($Resource != null){
                                $image = parent::ResourcePath($Resource->path,$Resource->filename);
                            }
                            $trueList['content'][] = [
                                'sort'    => $Content->sort,
                                'content' => $Content->content,
                                'image'   => $image
                            ];
                        }
                    }
                }
            }
        } 
        return $trueList;
    }
}
