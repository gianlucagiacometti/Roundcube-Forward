/*

 +-----------------------------------------------------------------------+
 | PostfixAdmin Forward Plugin for RoundCube                             |
 | Version: 1.4.0                                                        |
 | Author: Gianluca Giacometti <php@gianlucagiacometti.it>               |
 | Contributors:                                                         |
 |               Sebastien Blaisot (https://github.com/sblaisot)         |
 |               Jan B. Fiedler (https://github.com/zuloo)               |
 |               Sebastian L. (https://github.com/brknkfr)               |
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

	rcmail.register_command('plugin.forward', function() {
		rcmail.goto_url('plugin.forward')
	}, true);

	rcmail.register_command('plugin.forward-save', function() {
		$("#forwardkeepcopies").prop("disabled", false);
		rcmail.gui_objects.forwardform.submit();
	}, true);

	// Get existing forwards
	var initial_forwards = $("#forwardforwards").val();

	// Disable submit button
	$(".button").prop("disabled",true);

	$("#forwardforwards").on("keyup", function(){
		if(initial_forwards != "" || $("#forwardforwards").val() != ""){
	        	$(".button").prop("disabled",false);
			$("#forwardkeepcopies").prop("disabled", false);
    		}
		if($("#forwardforwards").val() == ""){
			$("#forwardkeepcopies").prop("checked", true);
			$("#forwardkeepcopies").prop("disabled", true);
		}
	});
	$("#forwardkeepcopies").change( function(){
		if($("#forwardforwards").val() != ""){
	        	$(".button").prop("disabled",false);
			$("#forwardkeepcopies").prop("disabled", false);
    		}
	});

});

