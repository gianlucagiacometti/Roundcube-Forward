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

window.rcmail && rcmail.addEventListener('init', function(evt) {

	var input_forwards = rcube_find_object('_forwardforwards');
	    input_keepcopies = rcube_find_object('_forwardkeepcopies')
	    initial_forwards = input_forwards.value;

	// Disable forwardkeepcopies checkbox if forwardforwards textarea is empty
	if (input_keepcopies) {
		if (input_forwards && input_forwards.value == '') {
			input_keepcopies.checked = true;
			input_keepcopies.disabled = true;
		} else {
			input_keepcopies.disabled = false;
		}

	}

	rcmail.register_command('plugin.forward', function() {
		rcmail.goto_url('plugin.forward')
	}, true);

	rcmail.register_command('plugin.forward-save', function() {
		var check_forwards = rcube_find_object('_forwardforwards');

		if ((check_forwards.value == '')) {
			input_keepcopies.checked = true;
		}
		rcmail.gui_objects.forwardform.submit();
	}, true);

	// Disable submit button
	$(".button").prop("disabled",true);
	$("#forwardforwards").on("keyup", function(){
		if((input_forwards.value != "") || (initial_forwards != "")){
	        	$(".button").prop("disabled",false);
			$("#forwardkeepcopies").prop("disabled", false);
    		}
	});
	$("#forwardkeepcopies").change( function(){
		if((input_forwards.value != "")){
	        	$(".button").prop("disabled",false);
			$("#forwardkeepcopies").prop("disabled", false);
    		}
	});
});
