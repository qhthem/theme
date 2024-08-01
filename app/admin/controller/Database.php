<?php
// +----------------------------------------------------------------------
// | QHPHP [ 代码创造未来，思维改变世界。 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 https://www.astrocms.cn/ All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: ZHAOSONG <1716892803@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;
use app\admin\logic\Database as Databaselogic;

class Database extends Base
{
    /**
     * 复制数据库
     *
     * @author zhaosong
     * @return void
     */
    public function copy()
    {
        if (isPost()) {
            json(Databaselogic::copylogic(Request()::post()));
        }
    }

    /**
     * 备份数据库
     *
     * @author zhaosong
     * @return void
     */
    public function backup()
    {
        if (isPost()) {
            json(Databaselogic::backuplogic(Request()::post()));
        }
    }

    /**
     * 获取整个数据库表
     *
     * @author zhaosong
     * @return void
     */
    public function table_whole()
    {
        if (isPost()) {
            json(Databaselogic::table_whole());
        }
    }

    /**
     * 复制数据库表
     *
     * @author zhaosong
     * @return void
     */
    public function table_copy()
    {
        if (isPost()) {
            json(Databaselogic::table_copy(Request()::post()));
        }
    }

    /**
     * 优化数据库表
     *
     * @author zhaosong
     * @return void
     */
    public function table_optimize()
    {
        if (isPost()) {
            json(Databaselogic::table_optimize(Request()::post()));
        }
    }

    /**
     * 修复数据库表
     *
     * @author zhaosong
     * @return void
     */
    public function table_repair()
    {
        if (isPost()) {
            json(Databaselogic::table_repair(Request()::post()));
        }
    }

    /**
     * 获取数据库表进度
     *
     * @author zhaosong
     * @return void
     */
    public function table_progress()
    {
        if (isPost()) {
            json(Databaselogic::table_progress());
        }
    }

    /**
     * 恢复删除的数据库表
     *
     * @author zhaosong
     * @return void
     */
    public function revert_delete()
    {
        if (isPost()) {
            json(Databaselogic::revert_delete(Request()::post()));
        }
    }

    /**
     * 下载恢复文件
     *
     * @author zhaosong
     * @return void
     */
    public function revert_downoad()
    {
        if (isPost()) {
            Databaselogic::revert_downoad(Request()::post());
        }
    }

    /**
     * 导入数据库
     *
     * @author zhaosong
     * @return void
     */
    public function import_database()
    {
        if (isPost()) {
            json(Databaselogic::import_database(Request()::post('filename')));
        }
    }
}