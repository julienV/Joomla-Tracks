window.addEvent('domready', function(){
	
	$('glproject').addEvent('change', function(){
		this.form.task.value = 'updateproject';
		this.form.submit();
	});
	
});