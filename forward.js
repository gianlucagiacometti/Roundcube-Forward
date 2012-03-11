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

if (window.rcmail) {

	rcmail.addEventListener('init', function(evt) {

		var tab = $('<span>').attr('id', 'settingstabpluginforward').addClass('tablink');

		var button = $('<a>').attr('href', rcmail.env.comm_path + '&_action=plugin.forward').html(rcmail.gettext('forward', 'forward')).appendTo(tab);

		button.bind('click', function(e) {
			return rcmail.command('plugin.forward', this);
			});

		rcmail.add_element(tab, 'tabs');

		rcmail.register_command('plugin.forward', function() {
			rcmail.goto_url('plugin.forward')
			}, true);

		rcmail.register_command('plugin.forward-save', function() {
			rcmail.gui_objects.forwardform.submit();
			}, true);

		})

	}
