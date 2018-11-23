<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Good extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        // 定义表的名称
        $table = $this->table('zh_goods');
        // 给当前表添加字段
        // addColumn()方法第一个参数是字段名称
        // 第二个参数是字段的数据类型
        // 第三个参数为数组，设置默认值，备注，字段长度
        $table->addColumn('title','string',['limit'=>80,'default'=>'笔记本','comment'=>'商品名'])
        ->addColumn('class','string',['limit'=>20,'default'=>'电脑','comment'=>'商品分类'])->addColumn('username','string',['limit'=>10,'default'=>'张三','comment'=>'用户名'])->addColumn('content','text',['limit'=>80,'comment'=>'商品内容'])->create();
    }
}
