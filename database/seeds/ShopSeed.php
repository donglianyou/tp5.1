<?php

use think\migration\Seeder;

class ShopSeed extends Seeder
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        // 使用db()方法定义往哪个表里填充数据，然后使用insert()方法来设置插入的数据
        db('zh_shop')->insert(['name'=>'苹果笔记本','class'=>'笔记本','username'=>'张先生']);
        db('zh_shop')->insert(['name'=>'鸿基','class'=>'电脑','username'=>'刘晓峰']);
    }
}