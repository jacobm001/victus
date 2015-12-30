function victus() {
    this.recipes;

    this.set_active_menu = function(option) {
        var str = "#menu_" + option;
        $(".nav .active").toggleClass("active");
        $(str).toggleClass("active");
    };

    this.set_active_view = function(option) {
        $(".view-visible").toggleClass("view-visible");
        $("#" + option).toggleClass("view-visible");
    };

    this.disp_recipe = function(id) {
        var list    = $("#list");
        var display = $("#disp_recipe");
        var ingr    = display.find("#disp_recipe_ingredients");

        display.find("#disp_recipe_name").text(recipes[id].name);
        display.find("#disp_recipe_directions").text(recipes[id].directions);
        display.find("#disp_recipe_yields").text(recipes[id].yields);

        ingr.empty("");
        for(var i = 0; i < recipes[id].ingredients.length; ++i) {
            var str = "<li class=\"col-xs-12 col-sm-6 col-md-4 col-lg-3\">";
            str += recipes[id].ingredients[i];
            str += "</li>";
            ingr.append(str);
        }

        $(".view-visible").toggleClass("view-visible");
        display.toggleClass("view-visible");

    };

    this.disp_create = function() {
        this.set_active_view("create");
        this.set_active_menu("create");
    };

    this.disp_list = function() {
        this.set_active_view("list");
        this.set_active_menu("home");
    };

    this.get_recipes = function() {
        $.getJSON("api/", function(data) {
            recipes = data;
            var t = $("#recipe_card_template");
            for(var i = 0; i < data.length; i++) {
                var c = t.clone();
                c.attr("id", "recipe_card_" + i);
                c.find(".card_title").text(data[i].name);
                c.find(".card_desc").text(data[i].notes);
                c.attr("onclick", "v.disp_recipe(" + i + ")");
                c.removeClass("hidden");
                c.appendTo("#list");
            }
        });
    };
};

var v = new victus();
v.get_recipes();