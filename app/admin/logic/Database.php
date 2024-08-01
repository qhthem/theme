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
namespace app\admin\logic;
use ZipArchive;
use app\admin\model\Handle;
class Database
{
    /**
     * 复制数据库表结构和数据
     *
     * @author zhaosong
     * @param array $params 分页参数
     * @return array 包含状态码、数据和总记录数的数组
     */
    public static function copylogic($params = [])
    {
        $limit = !empty($params['limit']) ? $params['limit'] : 10;
        $page = !empty($params['page']) ? $params['page'] : 1;

        // 计算查询的起始位置
        $offset = ($page - 1) * $limit;
        $res = cache_get_or_set('Database_key', function() {
            return db('admin')->query("SHOW TABLE STATUS");
        }, 7200);
        
        $tableStatusPaginated = array_slice($res, $offset, $limit);
        $totalRecords = count($res);

        $data['status'] = 200;
        $data['data'] = $tableStatusPaginated;
        $data['total'] = $totalRecords;
        return $data;
    }

    /**
     * 备份数据库
     *
     * @author zhaosong
     * @param array $params 分页参数
     * @return array 包含状态码、数据和总记录数的数组
     */
    public static function backuplogic($params = [])
    {
        $limit = !empty($params['limit']) ? $params['limit'] : 10;
        $page = !empty($params['page']) ? $params['page'] : 1;

        // 计算查询的起始位置
        $offset = ($page - 1) * $limit;

        $res = [];
        $list = glob(C('db_backups') . '*');

        foreach ($list as $name) {
            if (preg_match('/^backup_\d{14}\.(zip|zip\.gz)$/', basename($name))) {
                $info['filesize'] = Handle::formatBytes(filesize($name));
                $info['filename'] = basename($name);
                $info['backtime'] = date("Y-m-d H:i:s", filectime($name));
                $info['random'] = $name[0];
                $info['part'] = $name[7];
                $info['time'] = strtotime($info['backtime']);
                $res[] = $info;
            }
        }

        $tableStatusPaginated = array_slice($res, $offset, $limit);
        $totalRecords = count($res);

        $data['status'] = 200;
        $data['data'] = $tableStatusPaginated;
        $data['total'] = $totalRecords;

        return $data;
    }
    /**
     * 优化数据库表
     *
     * @author zhaosong
     * @param array $params 包含表名的参数数组
     * @return array 包含状态码和消息的数组
     */
    public static function table_optimize($params = [])
    {
        if (empty($params['table'])) {
            return ['status' => 00, 'msg' => '参数错误'];
        }

        foreach ($params['table'] as $tables) {
            db('admin')->query('OPTIMIZE TABLE `' . $tables . '`');
        }

        return ['status' => 200, 'msg' => '优化成功'];
    }

    /**
     * 修复数据库表
     *
     * @author zhaosong
     * @param array $params 包含表名的参数数组
     * @return array 包含状态码和消息的数组
     */
    public static function table_repair($params = [])
    {
        if (empty($params['table'])) {
            return ['status' => 00, 'msg' => '参数错误'];
        }

        foreach ($params['table'] as $tables) {
            db('admin')->query('REPAIR TABLE `' . $tables . '`');
        }

        return ['status' => 200, 'msg' => '修复成功'];
    }

    /**
     * 复制数据库表结构和数据
     *
     * @author zhaosong
     * @param array $params 包含表名的参数数组
     * @return array 包含状态码和消息的数组
     */
    public static function table_copy($params = [])
    {
        if (empty($params['table'])) {
            return ['status' => 00, 'msg' => '参数错误'];
        }

        return self::backup_file($params['table']);
    }

    /**
     * 获取整个数据库表
     *
     * @author zhaosong
     * @param array $params 分页参数
     * @return array 包含状态码和消息的数组
     */
    public static function table_whole($params = [])
    {
        $res = db('admin')->query("SHOW TABLE STATUS");
        $tables = [];
        foreach ($res as $item) {
            $tables[] = $item['Name'];
        }
        
        return self::backup_file($tables);
    }
    
    /**
     * 获取数据库表进度
     *
     * @author zhaosong
     * @return array 包含状态码和进度的数组
     */
    public static function table_progress()
    {
        $progress = get_cookie('backup_progress');
        $progress = empty($progress) ? 10 : $progress;
        return ['status' => 200, 'Progress' => (int) $progress];
    }
    
    /**
     * 备份数据库表到文件
     *
     * @author zhaosong
     * @param array $tables 表名数组
     * @return array 包含状态码和消息的数组
     */    
    protected static function backup_file($tables)
    {
        $backup_file = "backup_" . date("YmdHis") . ".sql";
        $handle = fopen($backup_file, 'w+');
        
        $processed_tables = 0;
        $total_tables = count($tables);
        foreach ($tables as $table_name) {
            // 获取表结构
            $sql = "SHOW CREATE TABLE $table_name";
            $result = db('admin')->query($sql);
            // var_dump($result[0]["Table"]);
            $table_structure = $result[0]['Create Table'];
        
            // 获取表数据
            $sql = "SELECT * FROM $table_name";
            $result = db('admin')->query($sql);
            $table_data = $result;
            
            // 备份表
            fwrite($handle, "-- 备份表结构\n");
            fwrite($handle, $table_structure . ";\n\n");
            fwrite($handle, "-- 备份表数据\n");
        
            $total_rows = count($table_data);
            $processed_rows = 0;
            
            foreach ($table_data as $row) {
                $values = implode("', '", $row);
                fwrite($handle, "INSERT INTO $table_name VALUES ('$values');\n");
        
                $processed_rows++;
                $percentage = (($processed_tables + ($processed_rows / $total_rows)) / $total_tables) * 100;
                
                cookie('backup_progress',(int) $percentage); // 存储备份进度到缓存
            }
        
            $processed_tables++;
        }
        
        fclose($handle);
        
        self::zip_file($backup_file);
        del_cookie('backup_progress');
        return ['status' => 200, 'msg' => '打包完成！'];
    }
    
    /**
     * 导入数据库
     *
     * @author zhaosong
     * @param string $backup_file 备份文件名
     * @return array 包含状态码和消息的数组
     */    
    public static function import_database($backup_file)
    {
        if(empty($backup_file)){
            return ['status' => 00, 'msg' => '参数错误'];
        }
        
        // 解压备份文件（如果是压缩的）
        $unzip_result = self::unzip_file(C('db_backups') . $backup_file);
        if ($unzip_result['status'] != 200) {
            return $unzip_result;
        }
        $sql_file = $unzip_result['data']['file_path'];
    
        // 读取备份文件中的SQL语句
        $sql_content = file_get_contents($sql_file);
        $sql_statements = explode(";\n", $sql_content);
        
        // 删除现有的表
        foreach ($sql_statements as $sql) {
            if (preg_match('/CREATE TABLE `(\w+)`/', $sql, $matches)) {
                $table_name = $matches[1];
                $drop_table_sql = "DROP TABLE IF EXISTS `$table_name`";
                $result = db('admin')->query($drop_table_sql);
                if ($result === false) {
                    return ['status' => 500, 'msg' => '删除表失败，SQL执行错误'];
                }
            }
        }
    
        // 执行SQL语句，恢复数据库
        $processed_statements = 0;
        $total_statements = count($sql_statements);
        foreach ($sql_statements as $sql) {
            if (trim($sql) == '') {
                continue;
            }
            
            // 执行 SQL 语句
            $result = db('admin')->query($sql);
            if ($result === false) {
                return ['status' => 500, 'msg' => '恢复失败，SQL执行错误'];
            }
            $processed_statements++;
            $percentage = ($processed_statements / $total_statements) * 100;
            cookie('backup_progress',(int) $percentage); // 存储导入进度到缓存
        }
        del_cookie('backup_progress');
        // 删除解压后的文件
        unlink($sql_file);
        return ['status' => 200, 'msg' => '数据库导入成功！'];
    }
    
    /**
     * 压缩备份文件
     *
     * @author zhaosong
     * @param string $backup_file 备份文件路径
     * @return bool 压缩成功返回true，否则返回false
     */    
    protected static function zip_file($backup_file)
    {
        // 创建ZIP文件并将备份文件添加到ZIP文件中
        $zip_file = "backup_" . date("YmdHis") . ".zip";
        $zip = new ZipArchive();
        $zip->open($zip_file, ZipArchive::CREATE);
        $zip->addFile($backup_file, basename($backup_file));
        $zip->close();
        
        // 将ZIP文件移动到指定文件夹
        $target_folder = C('db_backups');
        if (!file_exists($target_folder)) {
            mkdir($target_folder, 0777, true);
        }
        rename($zip_file, $target_folder . basename($zip_file));
        
        // 删除临时备份文件
        unlink($backup_file);
        
        return true;
    }
    
    /**
     * 解压文件
     *
     * @author zhaosong
     * @param string $zip_file 压缩文件路径
     * @return array 包含状态码和消息的数组
     */    
    protected static function unzip_file($zip_file)
    {
        $zip = new ZipArchive;
        $res = $zip->open($zip_file);
        if ($res === TRUE) {
            $extract_path = dirname($zip_file);
            $zip->extractTo($extract_path);
            $zip->close();
    
            // 获取解压后的SQL文件路径
            $sql_file = glob($extract_path . "/*.sql");
            if (empty($sql_file)) {
                return ['status' => 500, 'msg' => '解压失败，未找到SQL文件'];
            }
            return ['status' => 200, 'data' => ['file_path' => $sql_file[0]]];
        } else {
            return ['status' => 500, 'msg' => '解压失败，无法打开压缩文件'];
        }
    }
    
    /**
     * 恢复删除的备份文件
     *
     * @author zhaosong
     * @param array $params 包含文件名的参数数组
     * @return array 包含状态码和消息的数组
     */    
	public static function revert_delete(array $params = []) {
		if(empty($params['filename'])) return ['status' => 0, 'msg' => '参数错误！'];
        $path  = C('db_backups').$params['filename'];
		array_map('unlink', glob($path));
		if(count(glob($path))){
			return ['status'=>0, 'msg'=>'备份文件删除失败，请检查权限！'];
		} else {
			return ['status'=>200, 'msg'=>'备份文件删除成功！'];
		}
	}
	
    /**
     * 下载备份文件
     *
     * @author zhaosong
     * @param array $params 包含文件名的参数数组
     * @return array 包含状态码和消息的数组
     */	
	public static function revert_downoad(array $params = [])
	{
	    if(empty($params['filename'])) return ['status' => 0, 'msg' => '参数错误！'];
	    $filename  = C('db_backups').$params['filename'];
	    
	    if(!is_file($filename)){
	        return ['status'=>0, 'msg'=>'文件不存在!'];
	    }
	    
	    download($filename);
	}
}