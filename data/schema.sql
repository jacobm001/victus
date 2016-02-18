BEGIN TRANSACTION;
CREATE TABLE users (
	user_id integer,
	username text,
	name text,
	primary key(user_id)
);
CREATE TABLE banned_ip (
	ban_id integer primary key autoincrement,
	ban_ip text,
	ban_date date,
	ban_reason text,
	ban_active_ind text
);
CREATE TABLE priviledges (
	priviledge_id integer primary key autoincrement,
	priviledge text,
	priviledge_desc text
);
CREATE TABLE user_priviledges (
	user_priviledge_id integer primary key autoincrement,
	user_id integer,
	grant_date date
	priviledge text,
	foreign key(priviledge) references priviledges(priviledge)
);
CREATE TABLE sessions (
	session_id integer primary key autoincrement,
	user_id integer,
	session_key text,
	session_start date,
	session_expires date,
	foreign key(session_user) references users(user_id)
);
CREATE TABLE view_log (
	view_id integer primary key autoincrement,
	view_date date,
	view_ip text,
	view_resource text,
	view_session_key text,
	foreign key(view_session_key) references sessions(session_key)
);
CREATE TABLE auth_log (
	auth_id integer primary key autoincrement,
	user_id integer,
	attempt_date date,
	attempt_ip text,
	status text,
	foreign key(user_id) references users(user_id)
);
CREATE TABLE recipes(
	recipe_id integer primary key autoincrement,
	user_id integer,
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
CREATE INDEX find_banned_ip4 on banned_ip(ban_ip);
CREATE INDEX find_session_key on sessions(session_key);
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
