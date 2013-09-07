window.addEvent('domready', function(){

	document.id('glproject').addEvent('change', function(){
		this.form.task.value = 'updateproject';
		this.form.submit();
	});

});
