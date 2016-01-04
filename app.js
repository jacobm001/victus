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
                    $("#content").append(c);
                }
            }
        });
    };
}

var v = new victus();
v.get_recipes();