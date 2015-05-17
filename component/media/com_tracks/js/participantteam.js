/**
 * @package     Tracks
 * @subpackage  scripts
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */
(function($){
	$(function(){
		$('#jform_individual_id').chosen().change(function(){
			var individualId = $(this).val();

			if (individualId) {
				$.ajax({
					url: 'index.php?option=com_tracks&task=eventresult.participantteam&format=json&id=' + individualId,
					dataType: 'json'
				}).done(function(data) {
					if (data && data.team_id) {
						$('#jform_team_id').val(data.team_id).trigger('liszt:updated');
					}
				});
			}
			else {
				$('#jform_team_id').val('').trigger('liszt:updated');
			}
		});
	});

})(jQuery);
