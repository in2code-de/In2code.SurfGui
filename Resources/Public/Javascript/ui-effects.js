/****************************************************
 *                                                  *
 *  (c) 2013 in2code GmbH  www.in2code.de           *
 *                                                  *
 ****************************************************/

;
(function ($) {

	// chosen initialisieren und konfigurieren
	$('.In2code-SurfGui-Git-Source-Select').chosen({
		no_results_text:'Oops, da gibts nix!',
		search_contains: true
	});
	$('.In2code-SurfGui-Project-Select').chosen({
		no_results_text:'Oops, da gibts nix!',
		search_contains: true
	});

}(jQuery));
