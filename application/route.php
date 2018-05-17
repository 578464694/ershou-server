<?php
use think\Route;

Route::post('api/:version/upload','api/:version.UploadImage/upload');

// token
Route::post('api/:version/token/verify','api/:version.Token/verifyToken');
Route::post('api/:version/token/user','api/:version.Token/getToken');

// goods
Route::post('api/:version/goods','api/:version.Goods/addGoods');
Route::post('api/:version/goods/price','api/:version.Goods/modifyGoodsPrice');
Route::post('api/:version/goods/saled','api/:version.Goods/signGoodsSaled');
Route::get('api/:version/goods/paginate','api/:version.Goods/getGoods');    //分页获取商品
Route::get('api/:version/goods/:id','api/:version.Goods/getGoodsDetail','',['id'=>'\d+']);
Route::get('api/:version/goods/test','api/:version.Goods/test');
Route::get('api/:version/goods/collection','api/:version.Goods/getCollection');
//user
Route::post('api/:version/user','api/:version.User/setUser');

// comment
Route::post('api/:version/comment','api/:version.Comment/addComment');
Route::get('api/:version/comment/paginate','api/:version.Comment/getCommentsByPaginate');

// collection
Route::post('api/:version/collection','api/:version.Collection/changeCollection');//改变收藏状态