BEGIN TRANSACTION;
CREATE TABLE users (
	user_id number,
	username text,
	name text,
	primary key(user_id)
);
CREATE TABLE sessions (
	session_id number,
	session_key text,
	session_user number,
	session_start date,
	session_expires date,
	primary key(session_id),
	foreign key(session_user) references users(user_id)
);
CREATE TABLE recipes(
	recipe_id integer primary key autoincrement,
	user_id number,
	recipe_name text,
	recipe_notes text,
	recipe_yields text,
	recipe_directions text,
	foreign key(user_id) references users(user_id)
);
CREATE TABLE recipe_tags (
	tag_id integer primary key autoincrement,
	recipe_id integer,
	tag_name text,
	foreign key(recipe_id) references recipes(recipe_id)
);
CREATE TABLE recipe_ingredients (
	ingredient_id integer primary key autoincrement,
	recipe_id integer,
	ingredient_name text,
	foreign key(recipe_id) references recipes(recipe_id)
);
CREATE INDEX recipe_tags_find_id on recipe_tags(recipe_id);
CREATE INDEX recipe_ingredients_find_id on recipe_ingredients(recipe_id);
CREATE VIEW one_line_recipes as
select
	recipes.recipe_id,
	recipes.recipe_name,
	recipes.recipe_notes,
	recipes.recipe_yields,
	recipes.recipe_directions,
	(
		select 
			group_concat(recipe_ingredients.ingredient_name, '||')
		from
			recipe_ingredients
		where
			recipe_ingredients.recipe_id = recipes.recipe_id
	) as recipe_ingredients,
	(
		select 
			group_concat(recipe_tags.tag_name, '||')
		from
			recipe_tags
		where
			recipe_tags.recipe_id = recipes.recipe_id
	) as recipe_tags
from
	recipes
	left join recipe_ingredients
		on recipes.recipe_id = recipe_ingredients.recipe_id
	left join recipe_tags
		on recipes.recipe_id = recipe_tags.recipe_id
group by
	recipes.recipe_id
order by
	recipes.recipe_name;
COMMIT;
