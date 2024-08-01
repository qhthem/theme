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
namespace app\admin\model;

/**
 * 数据库操作字段类
 *
 * @author zhaosong
 */
class Sql {
    
   /**
     * 创建表
     *
     * @param string $tableName 表名
     * @param array $fields 表字段数组
     */
    public static function createTable($tableName) {
		$sql = "CREATE TABLE `".C('db_prefix').$tableName."` (
		  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
		  `userid` mediumint(8) unsigned NOT NULL DEFAULT '0',
		  `username` varchar(30) NOT NULL DEFAULT '',
		  `nickname` varchar(30) NOT NULL DEFAULT '',
		  `title` varchar(180) NOT NULL DEFAULT '',
		  `color` char(9) NOT NULL DEFAULT '',
		  `inputtime` int(10) unsigned NOT NULL DEFAULT '0',
		  `updatetime` int(10) unsigned NOT NULL DEFAULT '0',
		  `keywords` varchar(100) NOT NULL DEFAULT '',
		  `description` varchar(255) NOT NULL DEFAULT '',
		  `click` mediumint(8) unsigned NOT NULL DEFAULT '0',
		  `content` text NOT NULL,
		  `copyfrom` varchar(50) NOT NULL DEFAULT '',
		  `thumb` varchar(150) NOT NULL DEFAULT '',
		  `url` varchar(100) NOT NULL DEFAULT '',
		  `flag` varchar(12) NOT NULL DEFAULT '' COMMENT '1置顶,2头条,3特荐,4推荐,5热点,6幻灯,7跳转',
		  `status` tinyint(1) NOT NULL DEFAULT '1',
		  `issystem` tinyint(1) unsigned NOT NULL DEFAULT '1',
		  `listorder` tinyint(3) unsigned NOT NULL DEFAULT '1',
		  `groupids_view` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '阅读权限',
		  `readpoint` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '阅读收费',
		  `paytype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '收费类型',
		  `is_push` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否百度推送',
		  PRIMARY KEY (`id`),
		  KEY `status` (`status`,`listorder`),
		  KEY `catid` (`catid`,`status`),
		  KEY `userid` (`userid`,`status`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
			
		return db('model')->query($sql);
    }

    /**
     * 添加整型字段
     *
     * @param string $fieldName 字段名
     * @param int $length 字段长度，默认为 11
     * @return string 字段定义
     */
    public static function addIntField($tableName,$fieldName, $length = 11) {
        return db('model')->query("ALTER TABLE `".C('db_prefix').$tableName."` ADD COLUMN `$fieldName` INT($length)");
    }

    /**
     * 添加大整型字段
     *
     * @param string $fieldName 字段名
     * @param int $length 字段长度，默认为 20
     * @return string 字段定义
     */
    public static function addBigIntField($fieldName, $length = 20) {
        return "$fieldName BIGINT($length)";
    }

    /**
     * 添加小整型字段
     *
     * @param string $fieldName 字段名
     * @param int $length 字段长度，默认为 4
     * @return string 字段定义
     */
    public static function addTinyIntField($fieldName, $length = 4) {
        return "$fieldName TINYINT($length)";
    }

    /**
     * 添加中整型字段
     *
     * @param string $fieldName 字段名
     * @param int $length 字段长度，默认为 6
     * @return string 字段定义
     */
    public static function addSmallIntField($fieldName, $length = 6) {
        return "$fieldName SMALLINT($length)";
    }

    /**
     * 添加中整型字段
     *
     * @param string $fieldName 字段名
     * @param int $length 字段长度，默认为 9
     * @return string 字段定义
     */
    public static function addMediumIntField($fieldName, $length = 9) {
        return "$fieldName MEDIUMINT($length)";
    }

    /**
     * 添加浮点型字段
     *
     * @param string $fieldName 字段名
     * @param int $length 总长度，默认为 10
     * @param int $decimals 小数位数，默认为 2
     * @return string 字段定义
     */
    public static function addFloatField($fieldName, $length = 10, $decimals = 2) {
        return "$fieldName FLOAT($length, $decimals)";
    }

    /**
     * 添加双精度浮点型字段
     *
     * @param string $fieldName 字段名
     * @param int $length 总长度，默认为 10
     * @param int $decimals 小数位数，默认为 2
     * @return string 字段定义
     */
    public static function addDoubleField($tableName,$fieldName, $length = 10, $decimals = 2) {
        return db('field')->query("ALTER TABLE `".C('db_prefix').$tableName."` ADD COLUMN `$fieldName` DOUBLE($length, $decimals)");
    }

    /**
     * 添加定点数字字段
     *
     * @param string $fieldName 字段名
     * @param int $length 总长度，默认为 10
     * @param int $decimals 小数位数，默认为 2
     * @return string 字段定义
     */
    public static function addDecimalField($fieldName, $length = 10, $decimals = 2) {
        return "$fieldName DECIMAL($length, $decimals)";
    }

    /**
     * 添加可变字符串字段
     *
     * @param string $fieldName 字段名
     * @param int $length 字段长度，默认为 255
     * @return string 字段定义
     */
    public static function addVarCharField($tableName,$fieldName, $length = 255) {
        return db('field')->query("ALTER TABLE `".C('db_prefix').$tableName."` ADD COLUMN `$fieldName` VARCHAR($length)");
    }

    /**
     * 添加固定字符串字段
     *
     * @param string $fieldName 字段名
     * @param int $length 字段长度
     * @return string 字段定义
     */
    public static function addCharField($fieldName, $length) {
        return "$fieldName CHAR($length)";
    }

    /**
     * 添加文本字段
     *
     * @param string $fieldName 字段名
     * @return string 字段定义
     */
    public static function addTextField($tableName,$fieldName) {
        return "$fieldName TEXT";
    }

    /**
     * 添加中等文本字段
     *
     * @param string $fieldName 字段名
     * @return string 字段定义
     */
    public static function addMediumTextField($tableName,$fieldName) {
        return db('field')->query("ALTER TABLE `".C('db_prefix').$tableName."` ADD COLUMN `$fieldName` MEDIUMTEXT");
    }

    /**
     * 添加长文本字段
     *
     * @param string $fieldName 字段名
     * @return string 字段定义
     */
    public static function addLongTextField($fieldName) {
        return "$fieldName LONGTEXT";
    }

    /**
     * 添加日期字段
     *
     * @param string $fieldName 字段名
     * @return string 字段定义
     */
    public static function addDateField($fieldName) {
        return "$fieldName DATE";
    }

    /**
     * 添加日期时间字段
     *
     * @param string $fieldName 字段名
     * @return string 字段定义
     */
    public static function addDateTimeField($fieldName) {
        return "$fieldName DATETIME";
    }

    /**
     * 添加时间戳字段
     *
     * @param string $fieldName 字段名
     * @return string 字段定义
     */
    public static function addTimestampField($fieldName) {
        return "$fieldName TIMESTAMP";
    }

    /**
     * 添加时间字段
     *
     * @param string $fieldName 字段名
     * @return string 字段定义
     */
    public static function addTimeField($fieldName) {
        return "$fieldName TIME";
    }
    /**
     * 添加年份字段
     * @param string $fieldName 字段名
     * @return string 字段定义
     */
    public static function addYearField($fieldName) {
        return "$fieldName YEAR";
    }

    /**
     * 添加枚举字段
     * @param string $fieldName 字段名
     * @param array $values 枚举值数组
     * @return string 字段定义
     */
    public static function addEnumField($fieldName, $values) {
        $values = implode(",", $values);
        return "$fieldName ENUM($values)";
    }

    /**
     * 添加集合字段
     * @param string $fieldName 字段名
     * @param array $values 集合值数组
     * @return string 字段定义
     */
    public static function addSetField($fieldName, $values) {
        $values = implode(",", $values);
        return "$fieldName SET($values)";
    }

    /**
     * 添加布尔字段
     * @param string $fieldName 字段名
     * @return string 字段定义
     */
    public static function addBooleanField($tableName,$fieldName) {
        return db('field')->query("ALTER TABLE `".C('db_prefix').$tableName."` ADD COLUMN `$fieldName` BOOLEAN");
    }

    /**
     * 添加二进制字段
     * @param string $fieldName 字段名
     * @return string 字段定义
     */
    public static function addBinaryField($fieldName) {
        return "$fieldName BINARY";
    }

    /**
     * 添加可变长度二进制字段
     * @param string $fieldName 字段名
     * @param int $length 长度，默认为255
     * @return string 字段定义
     */
    public static function addVarBinaryField($fieldName, $length = 255) {
        return "$fieldName VARBINARY($length)";
    }

    /**
     * 添加小型二进制字段
     * @param string $fieldName 字段名
     * @return string 字段定义
     */
    public static function addTinyBlobField($fieldName) {
        return "$fieldName TINYBLOB";
    }

    /**
     * 添加二进制字段
     * @param string $fieldName 字段名
     * @return string 字段定义
     */
    public static function addBlobField($fieldName) {
        return "$fieldName BLOB";
    }

    /**
     * 添加中型二进制字段
     * @param string $fieldName 字段名
     * @return string 字段定义
     */
    public static function addMediumBlobField($fieldName) {
        return "$fieldName MEDIUMBLOB";
    }

    /**
     * 添加大型二进制字段
     * @param string $fieldName 字段名
     * @return string 字段定义
     */
    public static function addLongBlobField($fieldName) {
        return "$fieldName LONGBLOB";
    }
    
    /**
     * 删除数据库表
     *
     * @param string $fieldName 表名
     * @return mixed 查询结果
     * @author zhaosong
     */
    public static function deltable($fieldName)
    {
        $fieldName = C('db_prefix') . $fieldName;
        $sql = "DROP TABLE $fieldName";
        return db('model')->query($sql);
    }
    
    /**
     * 检查表中是否存在指定字段
     *
     * @param string $tableName 表名
     * @param string $fieldName 字段名
     * @return bool 如果字段存在，返回 true，否则返回 false
     * @author zhaosong
     */
    public static function checkField($tableName, $fieldName)
    {
        $sql = "SELECT COUNT(*) > 0
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_NAME = '$tableName' AND COLUMN_NAME = '$fieldName'";
    
        $result = db('model')->query($sql);
        if ($result[0]) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * 删除数据表中的指定字段
     *
     * @param string $tableName 表名
     * @param string $columnName 字段名
     * @return mixed 删除成功返回 true，失败返回 false
     * @author zhaosong
     */
    public static function delColumn($tableName, $columnName)
    {
        $tableName = C('db_prefix') . $tableName;
        $sql = "ALTER TABLE `$tableName` DROP COLUMN `$columnName`";
        return db('model')->query($sql);
    }
    
}
