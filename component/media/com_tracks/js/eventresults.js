/**
 * @package     Tracks
 * @subpackage  scripts
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */
(function($){

	$(function(){
		$('.result input').change(function(){
			var cbinput = $(this).parents('tr').find('input[name="cid[]"]');

			if (!cbinput.prop('checked')) {
				cbinput.click();
			}

			$('.save-results').removeClass('btn-default').addClass('btn-warning');
		});
	});

})(jQuery);
