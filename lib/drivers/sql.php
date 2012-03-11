<?php

/*

 +-----------------------------------------------------------------------+
 | PostfixAdmin Forward Plugin for RoundCube                             |
 | Version: 0.7.1                                                        |
 | Author: Gianluca Giacometti <php@gianlucagiacometti.it>               |
 | Copyright (C) 2012 Gianluca Giacometti                                |
 | License: GNU General Public License                                   |
 +-----------------------------------------------------------------------+

 code structure based on:

 +-----------------------------------------------------------------------+
 | lib/drivers/sql.php                                                   |
 | Copyright (C) 2009 Boris HUISGEN <bhuisgen@hbis.fr>                   |
 | Licensed under the GNU GPL                                            |
 +-----------------------------------------------------------------------+

*/

/*
 * Read driver function.
 * @params: array $data the array of data to get and set.
 * @return: integer the status code.
 */
function mail_forward_read(array &$data) {

	$rcmail = rcmail::get_instance();

	if ($dsn = $rcmail->config->get('forward_sql_dsn')) {
		if (is_array($dsn) && empty($dsn['new_link'])) {
			$dsn['new_link'] = true;
			}
		else if (!is_array($dsn) && !preg_match('/\?new_link=true/', $dsn)) {
			$dsn .= '?new_link=true';
			}
		$db = new rcube_mdb2($dsn, '', FALSE);
		$db->set_debug((bool)$rcmail->config->get('sql_debug'));
		$db->db_connect('w');
		}
	else {
		$db = $rcmail->get_dbh();
		}

	if ($err = $db->is_error()) {
		return PLUGIN_ERROR_CONNECT;
		}

	$search = array('%address');
	$replace = array($db->quote($data['address']));
	$query = str_replace($search, $replace, $rcmail->config->get('forward_sql_read'));

	$sql_result = $db->query($query);
	if ($err = $db->is_error()) {
		return PLUGIN_ERROR_PROCESS;
		}

	$sql_arr = $db->fetch_assoc($sql_result);
	if (isset($sql_arr['goto'])) {
		$data['goto'] = $sql_arr['goto'];
		}
	else {
		$data['goto'] = array();
		}

	return PLUGIN_SUCCESS;

	}

/*
 * Write driver function.
 * @params: array $data the array of data to get and set.
 * @return: integer the status code.
 */
function mail_forward_write(array &$data) {

	$rcmail = rcmail::get_instance();

	if ($dsn = $rcmail->config->get('forward_sql_dsn')) {
		if (is_array($dsn) && empty($dsn['new_link'])) {
			$dsn['new_link'] = true;
			}
		else if (!is_array($dsn) && !preg_match('/\?new_link=true/', $dsn)) {
			$dsn .= '?new_link=true';
			}
		$db = new rcube_mdb2($dsn, '', FALSE);
		$db->set_debug((bool)$rcmail->config->get('sql_debug'));
		$db->db_connect('w');
		}
	else {
		$db = $rcmail->get_dbh();
		}

	if ($err = $db->is_error()) {
		return PLUGIN_ERROR_CONNECT;
		}

	$search = array(
			'%address',
			'%goto'
			);
	$replace = array(
			$db->quote($data['address']),
			 $db->quote($data['goto'])
			);
	$query = str_replace($search, $replace, $rcmail->config->get('forward_sql_write'));

	$sql_result = $db->query($query);
	if ($err = $db->is_error()) {
		return PLUGIN_ERROR_PROCESS;
		}

	return PLUGIN_SUCCESS;

	}

?>
