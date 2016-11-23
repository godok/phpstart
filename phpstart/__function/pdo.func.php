<?php
/**
 *  PDO函数
 *
 * @copyright			(C) 2016-2020 PHPSTART
 * @license				http://phpstart.xyz/license/
 * @lastmodify			2016-10-1
 * SCRIPT_PATH : php脚本的路径
 * SCRIPT_NAME : php脚本文件
 */
defined('IS_RUN') or exit('/**error:404**/');


function pdo($con = null) {
    if(empty($con)){
        $con = defined('DEFAULT_DB') ? DEFAULT_DB : 'master';
    }
	static $db;
	if(isset($db[$con])) {
	   return $db[$con];
	}
	ps::sys_class('db',0);
	$db[$con] = new DB($con);
	return $db[$con];
}


function pdo_query($sql, $params = array()) {
	return pdo()->query($sql, $params);
}


function pdo_fetchcolumn($sql, $params = array(), $column = 0) {
	return pdo()->fetchcolumn($sql, $params, $column);
}

function pdo_fetch($sql, $params = array()) {
	return pdo()->fetch($sql, $params);
}

function pdo_fetchall($sql, $params = array(), $keyfield = '') {
	return pdo()->fetchall($sql, $params, $keyfield);
}


function pdo_get($tablename, $condition = array(), $fields = array()) {
	return pdo()->get($tablename, $condition, $fields);
}

function pdo_getall($tablename, $condition = array(), $fields = array(), $keyfield = '') {
	return pdo()->getall($tablename, $condition, $fields, $keyfield);
}

function pdo_getslice($tablename, $condition = array(), $limit = array(), &$total = null, $fields = array(), $keyfield = '') {
	return pdo()->getslice($tablename, $condition, $limit, $total, $fields, $keyfield);
}


function pdo_update($table, $data = array(), $params = array(), $glue = 'AND') {
	return pdo()->update($table, $data, $params, $glue);
}


function pdo_insert($table, $data = array(), $replace = FALSE) {
	return pdo()->insert($table, $data, $replace);
}


function pdo_delete($table, $params = array(), $glue = 'AND') {
	return pdo()->delete($table, $params, $glue);
}


function pdo_insertid() {
	return pdo()->insertid();
}


function pdo_begin() {
	pdo()->begin();
}


function pdo_commit() {
	pdo()->commit();
}


function pdo_rollback() {
	pdo()->rollBack();
}


function pdo_debug($output = true, $append = array()) {
	return pdo()->debug($output, $append);
}

function pdo_run($sql) {
	return pdo()->run($sql);
}


function pdo_fieldexists($tablename, $fieldname = '') {
	return pdo()->fieldexists($tablename, $fieldname);
}


function pdo_indexexists($tablename, $indexname = '') {
	return pdo()->indexexists($tablename, $indexname);
}


function pdo_fetchallfields($tablename){
	$fields = pdo_fetchall("DESCRIBE {$tablename}", array(), 'Field');
	$fields = array_keys($fields);
	return $fields;
}


function pdo_tableexists($tablename){
	return pdo()->tableexists($tablename);
}
