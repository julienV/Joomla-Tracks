/**
 * @package     Tracks
 * @subpackage  scripts
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */
(function($){

	$(function(){
		$('.result input').change(function(){
			var cbinputs = $(this).parents('table').find('input[name="cid[]"]');
			cbinputs.attr('checked', 'checked');
			$('input[name="boxchecked"]').val(cbinputs.length);
			$('.save-results').removeClass('btn-default').addClass('btn-warning');
		});
	});

})(jQuery);
