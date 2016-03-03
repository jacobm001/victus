function editor() {
	// recipe_values
	this.name;
	this.yields;
	this.notes;
	this.ingredients = [];
	this.directions;
	this.tags = [];

	// data to submit
	this.cleaned_values;

	this.get_values = function() {
		this.name       = $("#create_recipe_name").val();
		this.yeilds     = $("#create_recipe_yields").val();
		this.notes      = $("#create_recipe_notes").val();
		this.directions = $("#create_recipe_directions").val();
	};

	this.clean_values = function() {
		var clean_tags = [];
		var clean_ingrdients = [];

		for(var i = 0; i < this.tags.length; i++)
			clean_tags.push(encodeURIComponent(this.tag[i]));

		for(var i = 0; i < this.ingredients.length; i++)
			clean_ingredients.push(encodeURIComponent(this.ingredients[i]));

		this.cleaned_values = {
			'name':        encodeURIComponent(this.name),
			'yields':      encodeURIComponent(this.yields),
			'notes':       encodeURIComponent(this.notes),
			'directions':  encodeURIComponent(this.directions),
			'ingredients': clean_ingredients,
			'tags':        clean_tags
		};

		this.cleaned_values = JSON.stringify(this.cleaned_values);
	};

	this.send_recipe = function() {
		// post the recipe to api
	};
};
