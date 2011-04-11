/**
 * provides autocomplete code for quickadd, for mootools 1.1
 */
window.addEvent('domready', function () {
	
	var searchInput = $('quickadd');

	//A simple spinner div, display-toggled during request
	var indicator = new Element('div', {
	 'class': 'autocompleter-loading',
	 'styles': {'display': 'none'}
	}).injectAfter($('submit2')); // appended after the input
	
	var completer = new Autocompleter.Ajax.Jsonindividual(searchInput, 
		'index.php?option=com_tracks&controller=quickadd&task=search', {
	 'postVar': 'query',
	 'minLength': 3,
	 'onRequest': function(el) {
	     indicator.setStyle('display', '');
	 },
	 'onComplete': function(el) {
	     indicator.setStyle('display', 'none');
	 }
	});

});