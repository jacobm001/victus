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
            var t = $("#recipe_section_template");
            var r = null;
            for(var i = 0; i < data.length; i++) {
                var c;
                if( r == null || r != data[i].name.charAt(0) ) {
                    r = data[i].name.charAt(0);
                    c = t.clone();
                    c.attr("id", "recipe_section_" + r);
                    c.find(".section-header").text(r);
                }

                c.find('.list-unstyled').append(
                    $('<li>').append(
                        $('<a>').append(data[i].name)
                    ).attr("onclick", "v.disp_recipe(" + i + ")")
                );

                if( i == data.length-1 || data[i+1].name.charAt(0) != r ) {
                    c.removeClass("hidden");
                    $("#content").append(c);
                }
            }
        });
    };
}

var v = new victus();
v.get_recipes();