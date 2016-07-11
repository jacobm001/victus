function editor() {
	// recipe_values
	this.name;
	this.yields;
	this.notes;
	this.ingredients = [];
	this.directions;
	this.tags = [];
	this.ingr_autonum = 0;
	this.tag_autonum = 0;

	// data to submit
	this.cleaned_values;

	this.init = function() {
	};

	this.build_ingr_item = function(val) {
		var li  = $("<li></li>");
		var del = $("<span></span>");
		var txt = $("<span></span>");
		var id  = "ingr_" + this.ingr_autonum++;

		del.addClass("glyphicon glyphicon-remove");
		del.attr("style", "min-width:16px;");
		del.attr("onclick", "v.editor.rm_ingredient(" + id + ")");

		txt.text(val);

		li.attr("id", id);
		li.append(del);
		li.append(txt);

		return li;
	};

	this.add_ingredient = function() {
		var textbox = $("#create_recipe_ingredients");
		
		if(textbox.val() == "")
			return;

		var ul      = $("#create_recipe_entered_ingredients");
		var li      = this.build_ingr_item(textbox.val());
		
		ul.append(li);

		textbox.val("");
		textbox.focus();
	};

	this.rm_ingredient = function(id) {
		$(id).remove();
	};

	this.build_tag_item = function(val) {
		var li  = $("<li></li>");
		var del = $("<span></span>");
		var txt = $("<span></span>");
		var id  = "tag_" + this.tag_autonum++;

		del.addClass("glyphicon glyphicon-remove");
		del.attr("style", "min-width:16px;");
		del.attr("onclick", "v.editor.rm_tag(" + id + ")");

		txt.text(val);
		
		li.attr("id", id);
		li.append(del);
		li.append(txt);

		return li;
	};

	this.add_tag = function() {
		var textbox = $("#create_recipe_tags");

		if(textbox.val() == "")
			return;
		
		var ul      = $("#create_recipe_entered_tags");
		var li      = this.build_tag_item(textbox.val());

		ul.append(li);

		textbox.val("");
		textbox.focus();
	};

	this.rm_tag = function(id) {
		$(id).remove();
	};

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
