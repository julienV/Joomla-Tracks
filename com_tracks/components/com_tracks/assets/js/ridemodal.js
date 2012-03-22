
window.addEvent('domready', function(){
	
	document.getElements('.removeride').addEvent('click', function(event){
		if (!confirm(Joomla.JText._('COM_TRACKS_RIDE_DELETE_CONFIRM'))) {
			event.stop();
		}
	});
	
});

function updateRide()
{
	SqueezeBox.close();
	window.location.reload();
}