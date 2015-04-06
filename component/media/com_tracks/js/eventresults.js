/**
 * @package     Tracks
 * @subpackage  scripts
 * @copyright   Tracks (C) 2008-2015 Julien Vonthron. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */
(function($){

	var showInput = function() {
		var value = $(this).text();
		var input = $('<input type="text" name="update" value="' + value + '"/>')
			.on('change blur', updateValue);
		$(this).replaceWith(input);
		input.focus();
	};

	var updateValue = function() {

		var element = $(this);
		var cell = $(this).parents('td');
		var type = $(this).parents('td').attr('object');
		var cb = $(this).parents('tr').find('[name="cid[]"]').val();

		$.ajax({
			url: 'index.php?option=com_tracks&task=eventresult.update&format=json',
			data: {'cb' : cb, 'property' : type ,'value' : $(this).val()},
			type : 'POST',
			dataType: 'json',
			beforeSend: function (xhr) {
				cell.addClass('ajaxing');
			},
			error: function (xhr) {
				alert('Updating value operation failed');
			}
		}).done(function(data) {
			cell.removeClass('ajaxing');

			if (data && (typeof(data.success) !== 'undefined')) {
				var span = $('<div>' + data.success + '</div>')
					.click(showInput);
				element.replaceWith(span);
			}
			else {
				alert('Updating value operation failed: ' + data.error);
			}
		});
	};

	$(function(){
		$('.ajaxupdate div').click(showInput);
	});

})(jQuery);
