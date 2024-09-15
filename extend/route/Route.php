<?php
// +----------------------------------------------------------------------
// | 路由设置
// +----------------------------------------------------------------------
$Route = [
    '^'.str_replace('/', '\/', 'user').'\/(\d+)$' => 'member/user/index/userid/$1',
    '^'.str_replace('/', '\/', 'msg').'\/(\d+)$' => 'member/messages/show/to/$1',
    '^'.str_replace('/', '\/', 'search').'$' => 'index/search/index'
];

if (isDbConnected()){
    $category = db('category')->field('catid,pclink,catdir')->order('listorder DESC')->select();
    
    $category = array_map(function ($item) {
        return [
            '^'.str_replace('/', '\/', $item['catdir']).'$' => 'index/index/lists/catid/'.$item['catid'].'',
            '^'.str_replace('/', '\/', $item['catdir']).'\/list_(\d+)$' => 'index/index/lists/catid/'.$item['catid'].'/page/$1',
            '^'.str_replace('/', '\/', $item['catdir']).'\/(\d+)$' => 'index/index/show/catid/'.$item['catid'].'/id/$1'
        ];
    }, $category);
    return array_merge($Route,$category);
}else {
    return $Route;
}
