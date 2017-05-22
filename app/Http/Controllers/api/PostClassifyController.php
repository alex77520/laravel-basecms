<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\api\Controller;

use App\Models\PostClassify;

class PostClassifyController extends Controller
{
    /**
     * 获取所有分类
     *
     * $key|$cid 两者选1传递
     * @param type var Description
     **/
    public function classify_list(Request $request)
    {
        $where = [
            'father' => 0,
            'show' => true
        ];
        if($request->has('cid')) {
            $where['pc_id'] = $request->get('cid');
        }
        if($request->has('key')) {
            $where['key'] = $request->get('key');
        }
        $PostClassifys = PostClassify::where($where)->orderBy('created_at','desc')->get();
        return parent::respSuccess(self::formatClassify($PostClassifys));
    }
    /**
     * 格式化分类输出格式
     *
     * @param type var Description
     **/
    public function formatClassify($PostClassifys,$needSon = true)
    {
        $trueList = [];
        if(is_object($PostClassifys)) {
            foreach ( $PostClassifys as $PostClassify ) {
                if($PostClassify->show == '1') {
                    $forList = [
                        'cid'   => $PostClassify->pc_id,
                        'name'  => $PostClassify->name,
                        'intro' => $PostClassify->intro
                    ];
                    if($PostClassify->key != null) {
                        $forList['key'] = $PostClassify->key;
                    }
                    if($needSon === true && $PostClassify->children->count() > 0) {
                        $forList['sons'] = self::formatClassify($PostClassify->children,false);
                    }
                    $trueList[] = $forList;
                }
            }
        }
        return $trueList;
    }
}
