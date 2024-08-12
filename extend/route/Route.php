<?php
// +----------------------------------------------------------------------
// | 路由设置
// +----------------------------------------------------------------------

// 路由配置说明 如果要配置栏目路由 

// 请把下面代码反注释掉 

// 在array_merge 函数中添加$category函数 比如array_merge($category,$user,$search)

// $category = db('category')->field('catid,pclink,catdir')->order('listorder DESC')->select();

// $category = array_map(function ($item) {
//     return [
//         '^'.str_replace('/', '\/', $item['catdir']).'$' => 'index/index/lists/catid/'.$item['catid'].'',
//         '^'.str_replace('/', '\/', $item['catdir']).'\/list_(\d+)$' => 'index/index/lists/catid/'.$item['catid'].'/page/$1',
//         '^'.str_replace('/', '\/', $item['catdir']).'\/(\d+)$' => 'index/index/show/catid/'.$item['catid'].'/id/$1'
//     ];
// }, $category);

$user = ['^'.str_replace('/', '\/', 'user').'\/(\d+)$' => 'member/user/index/userid/$1'];
// $search = ['^'.str_replace('/', '\/', 'search').'\/(\w+)\/(\w+)$' => 'index/search/index/modelid/$1/keyword/$2'];
$search = ['^'.str_replace('/', '\/', 'search').'$' => 'index/search/index'];
return array_merge($user,$search);
