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
}

function victus() {
	this.recipes;
	this.recipe_index;
	this.recipe_display;
	this.recipe_create;

	this.init = function() {
		self.recipe_index   = $('<div class="row" id="recipe_index">');
		self.recipe_display = $('<div class="row" id="recipe_display">');
		self.recipe_create  = $('<div class="row" id="recipe_create">');
		this.get_partials();
		this.get_recipes();
	};

	this.set_active_menu = function(option) {
		var str = "#menu_" + option;
		var menu_recipe = $("#menu_recipe");
		$(".nav .active").toggleClass("active");
		$(str).toggleClass("active");

		if( option == "recipe" && menu_recipe.hasClass("hidden") )
			menu_recipe.removeClass("hidden");
		else if( option != "recipe" && !menu_recipe.hasClass("hidden") )
			menu_recipe.addClass("hidden");
	};

	this.set_active_view = function(option) {
		$("#content > .row").detach();
		if( option === "recipe_index" )
			$("#content").append(self.recipe_index);
		else if( option === "recipe_create" )
			$("#content").append(self.recipe_create);
	};

	this.disp_recipe = function(id) {
		recipe_index.detach();
		this.set_active_menu("recipe");

		recipe_display.find("#disp_recipe_name").text(recipes[id].name);
		recipe_display.find("#disp_recipe_directions").text(recipes[id].directions);
		recipe_display.find("#disp_recipe_yields").text(recipes[id].yields);

		var ingr = recipe_display.find("#disp_recipe_ingredients");
		ingr.empty("");
		for(var i = 0; i < recipes[id].ingredients.length; ++i) {
			var str = "<li class=\"col-xs-12 col-sm-6 col-md-4 col-lg-3\">";
			str += recipes[id].ingredients[i];
			str += "</li>";
			ingr.append(str);
		}

		$("#content").append(recipe_display);
	};

	this.disp_create = function() {
		this.set_active_menu("create");
		this.set_active_view("recipe_create");
	};

	this.disp_list = function() {
		this.set_active_menu("home");
		this.set_active_view("recipe_index");
	};

	this.get_partials = function() {
		$.get("partials/display.html", function(data)
		{
			self.recipe_display.append(data);
		});

		$.get("partials/create.html", function(data) {
			self.recipe_create.append(data);
		});
	};

	this.get_recipes = function() {
		$.getJSON("api/", function(data) {
			recipes = data;
			var ts = $("#recipe_section_template_sm");
			var tm = $("#recipe_section_template_md");
			var tl = $("#recipe_section_template_lg");
			var r = null;
			for(var i = 0; i < data.length; i++) {
				var c;
				if( r == null || r != data[i].name.charAt(0) ) {
					var j = 0;
					r = data[i].name.charAt(0);

					var j = i;
					while( j != data.length -1 && r == data[j].name.charAt(0) ) {
						j++;
					}
					j = j - i;

					if( j < 15 )
						c = ts.clone();
					else if( j >= 15 && j <= 25 )
						c = tm.clone();
					else
						c = tl.clone();

					c.attr("id", "recipe_section_" + r);
					c.find(".section-header").text(r);
				}

				var li = $('<li>');
				var a  = $('<a>');

				a.append(data[i].name);
				a.attr("onclick", "v.disp_recipe(" + i + ")");
				li.append(a);

				if( j >= 15 && j < 25 )
					li.addClass("col-sm-12 col-sm-12 col-md-6 col-lg-6");
				else if( j >= 25 )
					li.addClass("col-sm-12 col-sm-6 col-md-4 col-lg-4");

				c.find('.list-unstyled').append(li);

				if( i == data.length-1 || data[i+1].name.charAt(0) != r ) {
					c.removeClass("hidden");
					recipe_index.append(c);
				}
			}

			$("#content").append(recipe_index);
		});
	};
}

var v = new victus();
v.init();

var e = new editor();
