/**
 * provides autocomplete code for quickadd, for mootools 1.1
 */

Autocompleter.Request.JSONIndividual = new Class({

	Extends: Autocompleter.Request.JSON,

	queryResponse: function(response) {
		this.parent();
		if (!response || !response.totalCount) return;
		this.update(response.rows);
	},

	update: function(tokens) {
		this.choices.empty();
		this.cached = tokens;
		var type = tokens && $type(tokens);
		if (!type || (type == 'array' && !tokens.length) || (type == 'hash' && !tokens.getLength())) {			
			(this.options.emptyChoices || this.hideChoices).call(this);
		} else {
			if (this.options.maxChoices < tokens.length && !this.options.overflow) tokens.length = this.options.maxChoices;
			tokens.each(this.options.injectChoice || function(token){
				var choice = new Element('li', {'individ': token.id, 'html': this.markQueryValue(token.name)});
				choice.inputValue = token.name;
				this.addChoiceEvents(choice).inject(this.choices);
			}, this);
			this.showChoices();
		}
	}, 	

	choiceSelect: function(choice) {
		if (choice) this.choiceOver(choice);
		$('individualid').value = choice.getProperty('individ');
		this.setSelection(true);
		this.queryValue = false;
		this.hideChoices();
	}

});

window.addEvent('domready', function () {
	
	new Autocompleter.Request.JSONIndividual('quickadd', 
			'index.php?option=com_tracks&controller=quickadd&task=search', {
        'postVar': 'query'
    });

});