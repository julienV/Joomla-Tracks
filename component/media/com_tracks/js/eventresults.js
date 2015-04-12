/**
 * @package     Tracks
 * @subpackage  scripts
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */
(function($){

	$(function(){
		$('.result input').change(function(){
			var row = $(this).parents('tr');

			var checked = row.find('input[name="cid[]"]').attr('checked');

			if (!checked) {
				row.find('input[name="cid[]"]').attr('checked', 'checked');
				var count = $('input[name="boxchecked"]').val();
				$('input[name="boxchecked"]').val(count + 1);
			}
			row.addClass('needs-saving');

			$('.save-results').removeClass('btn-default').addClass('btn-warning');
		});
	});

})(jQuery);
