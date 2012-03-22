
window.addEvent('domready', function() {

	document.getElements('div.tips-delete').addEvent('click', function(event){
		if (confirm(Joomla.JText._('COM_TRACKS_TIPSFROMPRO_DELETE_CONFIRM'))) {
			var target = this.getProperty('rel');
			window.location = target;
		}
	});
	
});