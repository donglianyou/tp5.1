<?php
/**
 * 应用公共文件
 */
use think\Db;

// 根据用户的主键id，查询用户名称
if (!function_exists('getUserName')) {
    function getUserName($id)
    {
        return Db::table('zh_user')->where('id', $id)->value('name');
    }
}
// 根据用户的主键id，查询用户名称
if (!function_exists('getCateName')) {
    function getCateName($CateId)
    {
        return Db::table('zh_article_category')->where('id', $CateId)->value('name');
    }
}

//过滤文章摘要
if (!function_exists('getArtContent')) {
    function getArtContent($content)
    {
        return mb_substr(strip_tags($content), 0, 50).'...';
    }
}