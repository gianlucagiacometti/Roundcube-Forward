<?php

/*

 +-----------------------------------------------------------------------+
 | PostfixAdmin Forward Plugin for RoundCube                             |
 | Version: 1.1.0                                                        |
 | Author: Gianluca Giacometti <php@gianlucagiacometti.it>               |
 | Contributors:                                                         |
 |               Sebastien Blaisot (https://github.com/sblaisot)         |
 |               Jan B. Fiedler (https://github.com/zuloo)               |
 | Copyright (C) 2012 Gianluca Giacometti                                |
 | License: GNU General Public License                                   |
 +-----------------------------------------------------------------------+

 code structure based on:

 +-----------------------------------------------------------------------+
 | Vacation Module for RoundCube                                         |
 | Copyright (C) 2009 Boris HUISGEN <bhuisgen@hbis.fr>                   |
 | Licensed under the GNU GPL                                            |
 +-----------------------------------------------------------------------+

*/

define('PLUGIN_SUCCESS', 0);
define('PLUGIN_ERROR_CONNECT', 1);
define('PLUGIN_ERROR_PROCESS', 2);
define('EMAIL_VALIDATION_PATTERN', '^[a-zA-Z][\w\.-]*[a-zA-Z0-9]@[a-zA-Z0-9][\w\.-]*[a-zA-Z0-9]\.[a-zA-Z][a-zA-Z\.]*[a-zA-Z]$^');

class forward extends rcube_plugin {

	public $task = 'settings';
	private $rc;
	private $obj;

	public function init() {

		$rcmail = rcmail::get_instance();
		$this->rc = &$rcmail;
		$this->add_texts('localization/', true);

		$this->rc->output->add_label('forward');
		$this->register_action('plugin.forward', array($this, 'forward_init'));
		$this->register_action('plugin.forward-save', array($this, 'forward_save'));
		$this->include_script('forward.js');

		$this->load_config();
		$this->require_plugin('jqueryui');
                $this->add_hook('settings_actions', array($this, 'forward_settings'));

		require_once ($this->home . '/lib/rcube_forward.php');
		$this->obj = new rcube_forward();

		}

	public function forward_init() {
		$this->read_data();
		$this->register_handler('plugin.body', array($this, 'forward_form'));
		$this->rc->output->set_pagetitle($this->gettext('forward'));
		$this->rc->output->send('plugin');
		}

	public function forward_settings($args) {
		$args['actions'][] = array(
			'action' => 'plugin.forward',
			'class'  => 'forward',
			'label'  => 'forward',
			'domain' => 'forward',
			'title'  => 'forward',
		);
		return $args;
		}

	public function forward_save() {
		$this->write_data();
		$this->register_handler('plugin.body', array($this, 'forward_form'));
		$this->rc->output->set_pagetitle($this->gettext('forward'));
		rcmail::get_instance()->overwrite_action('plugin.forward');
		$this->rc->output->send('plugin');
		}

	public function forward_form() {

		$table = new html_table(array('cols' => 2, 'class' => 'propform'));

		$field_id = 'forwardforwards';
		$text_forwardforwards = new html_textarea(array('name' => '_forwardforwards', 'id' => $field_id, 'spellcheck' => 1, 'rows' => 6, 'cols' => 40));
		$table->add('title', html::label($field_id, rcmail::Q($this->gettext('forwardforwards'))));
		$table->add(null, $text_forwardforwards->show($this->obj->get_forward_forwards()));

		$field_id = 'forwardkeepcopies';
		$input_forwardkeepcopies = new html_checkbox(array('name' => '_forwardkeepcopies', 'id' => $field_id, 'value' => 1));
		$table->add('title', html::label($field_id, rcmail::Q($this->gettext('forwardkeepcopies'))));
		$table->add(null, $input_forwardkeepcopies->show($this->obj->is_forward_keepcopies() === true || $this->obj->is_forward_keepcopies() == "1" || $this->obj->is_forward_keepcopies() == "t" || $this->obj->is_forward_keepcopies() == "y" || $this->obj->is_forward_keepcopies() == "yes" ? 1 : 0));
 
		$out = html::div(array('class' => "box formcontainer scroller"), html::div(array('id' => "prefs-title", 'class' => 'boxtitle'), $this->gettext('forward')) . html::div(array('class' => "boxcontent formcontent"), $table->show() ) . html::p(array('class' => "formbuttons footerleft"), $this->rc->output->button(array('command' => 'plugin.forward-save', 'type' => 'input', 'class' => 'button mainaction submit btn', 'label' => 'save'))));


		$this->rc->output->add_gui_object('forwardform', 'forward-form');

		return $this->rc->output->form_tag(array('id' => 'forward-form', 'name' => 'forward-form', 'method' => 'post', 'action' => './?_task=settings&_action=plugin.forward-save'), $out);

		}

	public function read_data() {

		$driver = $this->home . '/lib/drivers/' . $this->rc->config->get('forward_driver', 'sql').'.php';

		if (!is_readable($driver)) {
			rcube::raise_error(array('code' => 600, 'type' => 'php', 'file' => __FILE__, 'message' => "forward plugin: unable to open driver file $driver"), true, false);
			return $this->gettext('forwardinternalerror');
			}

		require_once($driver);

		if (!function_exists('mail_forward_read')) {
			rcube::raise_error(array('code' => 600, 'type' => 'php', 'file' => __FILE__, 'message' => "forward plugin: function mail_forward_read not found in driver $driver"), true, false);
			return $this->gettext('forwardinternalerror');
			}

		$data = array();
		$data['address'] = $this->obj->username;

		$ret = mail_forward_read($data);
		switch ($ret) {
			case PLUGIN_ERROR_CONNECT:
				$this->rc->output->command('display_message', $this->gettext('forwarddriverconnecterror'), 'error');
				return FALSE;
				break;
			case PLUGIN_ERROR_PROCESS:
				$this->rc->output->command('display_message', $this->gettext('forwarddriverprocesserror'), 'error');
				return FALSE;
				break;
			case PLUGIN_SUCCESS:
			default:
				break;
			}

		$forwards = explode(",", $data['goto']);
		if (in_array($this->obj->username, $forwards)) {
			$this->obj->set_forward_keepcopies(TRUE);
			}

		$forwards = array_diff($forwards, array($this->obj->username));
		if (!empty($forwards)) {
			$data['goto'] = implode("\n", $forwards);
			$this->obj->set_forward_forwards($data['goto']);
			}
		else {
			$this->obj->set_forward_forwards('');
			}

		return TRUE;

		}

	public function write_data() {

		$forwards = trim(rcube_utils::get_input_value('_forwardforwards', rcube_utils::INPUT_POST));

		if (is_string($forwards) && (strlen($forwards) > 0)) {
			$emails = preg_split("/[\s,;]+/", mb_strtolower(trim($forwards), 'UTF-8'));
			$emails = rcube_utils::get_input_value('_forwardkeepcopies', rcube_utils::INPUT_POST) ? array_diff(array_unique($emails), array($this->obj->username)) : array_unique($emails);
			}
		else if (!rcube_utils::get_input_value('_forwardkeepcopies', rcube_utils::INPUT_POST)) {
			$this->rc->output->command('display_message', $this->gettext('forwardnovalidforwards'), 'error');
			return FALSE;
			}
		if (!is_array($emails)) { $emails = array(); }
		foreach ($emails as $email) {
			if (!preg_match(EMAIL_VALIDATION_PATTERN, $email)) {
				$this->rc->output->command('display_message', $this->gettext('forwardinvalidforwards') . ': ' . $email, 'error');
				$this->obj->set_forward_forwards($forwards);
				if (rcube_utils::get_input_value('_forwardkeepcopies', rcube_utils::INPUT_POST)) {
					$this->obj->set_forward_keepcopies(TRUE);
					}
				else {
					$this->obj->set_forward_keepcopies(FALSE);
					}
				return FALSE;
				}
			}
		$forwards = empty($emails) ? "" : implode(",", $emails);

		if (rcube_utils::get_input_value('_forwardkeepcopies', rcube_utils::INPUT_POST)) {
			$forwards .= empty($emails) ? $this->obj->username : "," . $this->obj->username;
			$this->obj->set_forward_keepcopies(TRUE);
			}
		else {
			$this->obj->set_forward_keepcopies(FALSE);
			}

		$this->obj->set_forward_forwards($forwards);

		$driver = $this->home . '/lib/drivers/' . $this->rc->config->get('forward_driver', 'sql').'.php';

		if (!is_readable($driver)) {
			rcube::raise_error(array('code' => 600, 'type' => 'php', 'file' => __FILE__, 'message' => "forward plugin: unable to open driver file $driver"), true, false);
			return $this->gettext('forwardinternalerror');
			}

		require_once($driver);

		if (!function_exists('mail_forward_write')) {
			rcube::raise_error(array('code' => 600, 'type' => 'php', 'file' => __FILE__, 'message' => "forward plugin: function mail_forward_write not found in driver $driver"), true, false);
			return $this->gettext('forwardinternalerror');
			}

		$data = array();
		$data['address'] = $this->obj->username;
		$data['goto'] = $this->obj->get_forward_forwards();
		$data['modified'] = date('Y-m-d H:i:s');

		$ret = mail_forward_write ($data);
		switch ($ret) {
			case PLUGIN_ERROR_CONNECT:
					$this->rc->output->command('display_message', $this->gettext('forwarddriverconnecterror'), 'error');
					return FALSE;
					break;
			case PLUGIN_ERROR_PROCESS:
					$this->rc->output->command('display_message', $this->gettext('forwarddriverprocesserror'), 'error');
					return FALSE;
					break;
			case PLUGIN_SUCCESS:
			default:
					$this->rc->output->command('display_message', $this->gettext('forwardsuccessfullysaved'), 'confirmation');
					break;
			}

		$this->read_data();

		return TRUE;

		}

	}

?>
