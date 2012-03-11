<?php

/*

 +-----------------------------------------------------------------------+
 | PostfixAdmin Forward Plugin for RoundCube                             |
 | Version: 0.7.2                                                        |
 | Author: Gianluca Giacometti <php@gianlucagiacometti.it>               |
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

class rcube_forward {

	public $username = '';
	public $forward_keepcopy = TRUE;
	public $forward_forwards = '';

	public function __construct() {
		$this->init();
		}

	private function init() {
		$this->username = rcmail::get_instance()->user->get_username();
		}

	// Gets the username.
	public function get_username() {
		return $this->username;
		}

	// Gets the forward message.
	public function get_forward_forwards() {
		return $this->forward_forwards;
		}

	// Checks if user address must be keep in alias when the forward is enabled.
	public function is_forward_keepcopies() {
		return $this->forward_keepcopies;
		}

	// Sets the forward addresses.
	public function set_forward_forwards($forwards) {
		$this->forward_forwards = $forwards;
		}

	// Sets the keep copies.
	public function set_forward_keepcopies($flag) {
		$this->forward_keepcopies = $flag;
		}

	}
