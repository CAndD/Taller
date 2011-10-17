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
          $('<iframe name="" class="" src="asignar_jdc.php?hidden_car=' + carrera + '&asigna=1" scrolling="no" frameborder="0"></iframe>').modal();
          return false;
        });

        $('a.cambia').click(function (e) {
          var carrera = $(this).attr("id");
          $('<iframe name="" class="" src="asignar_jdc.php?hidden_car=' + carrera + '&cambia=1" scrolling="no" frameborder="0"></iframe>').modal();
          return false;
        });
 
        $('a.eliminar').click(function (e) {
          var carrera = $(this).attr("id");
          $('<iframe name="" class="" src="eliminar_jdc.php?hidden_jdc=' + carrera + '" scrolling="no" frameborder="0"></iframe>').modal();
          return false;
        });

        $('a.eliminar').click(function (e) {
          var carrera = $(this).attr("id");
          $('<iframe name="" class="" src="eliminar_jdc.php?hidden_jdc=' + carrera + '" scrolling="no" frameborder="0"></iframe>').modal();
          return false;
        });

        $('a.relacionar').click(function (e) {
          var codigoRamo = $(this).attr("id");
          $('<iframe name="" class="ifr" src="ramosRelacionar.php?codigoRamo=' + codigoRamo + '" scrolling="no" frameborder="0"></iframe>').modal();
          return false;
        });

        $('a.verMalla').click(function (e) {
          var codigoCarrera = $(this).attr("id");
          $('<iframe name="" class="ifr" src="Malla.php?codigoCarrera=' + codigoCarrera + '" scrolling="no" frameborder="0"></iframe>').modal();
          return false;
        });

        $('a.seccionesCreadas').click(function (e) {
          var codigoRamo = $(this).attr("id");
          $('<iframe name="" class="ifr" src="secundario/seccion.php?otros=no&codigoRamo=' + codigoRamo + '" scrolling="no" frameborder="0"></iframe>').modal();
          return false;
        });

        $('a.seccionesCreadasOtros').click(function (e) {
          var codigoRamo = $(this).attr("id");
          $('<iframe name="" class="ifr" src="secundario/seccion.php?otros=si&codigoRamo=' + codigoRamo + '" scrolling="no" frameborder="0"></iframe>').modal();
          return false;
        });

        $('a.responderSolicitud').click(function (e) {
          var idSolicitud = $(this).attr("id");
          $('<iframe name="" class="ifr" src="secundario/solicitud.php?idSolicitud=' + idSolicitud + '" scrolling="no" frameborder="0"></iframe>').modal();
          return false;
        });
});
