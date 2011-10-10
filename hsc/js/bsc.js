/*
 * SimpleModal Basic Modal Dialog
 * http://www.ericmmartin.com/projects/simplemodal/
 * http://code.google.com/p/simplemodal/
 *
 * Copyright (c) 2010 Eric Martin - http://ericmmartin.com
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Revision: $Id: basic.js 254 2010-07-23 05:14:44Z emartin24 $
 */

jQuery(function ($) {
	// Load dialog on page load
	//$('#basic-modal-content').modal();

	// Load dialog on click
	$('a.basic').click(function (e) {
		$('#admin').modal();

		return false;
	});
 
        $('a.asigna').click(function (e) {
          var carrera = $(this).attr("id");
          $('<iframe name="" class="" src="asignar_jdc.php?hidden_car=' + carrera + '&asigna=1" scrolling="no"></iframe>').modal();
          return false;
        });

        $('a.cambia').click(function (e) {
          var carrera = $(this).attr("id");
          $('<iframe name="" class="" src="asignar_jdc.php?hidden_car=' + carrera + '&cambia=1" scrolling="no"></iframe>').modal();
          return false;
        });
 
        $('a.eliminar').click(function (e) {
          var carrera = $(this).attr("id");
          $('<iframe name="" class="" src="eliminar_jdc.php?hidden_jdc=' + carrera + '" scrolling="no"></iframe>').modal();
          return false;
        });

        $('a.eliminar').click(function (e) {
          var carrera = $(this).attr("id");
          $('<iframe name="" class="" src="eliminar_jdc.php?hidden_jdc=' + carrera + '" scrolling="no"></iframe>').modal();
          return false;
        });
});
