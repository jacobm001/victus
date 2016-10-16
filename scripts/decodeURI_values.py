import sqlite3
from urllib.parse import unquote
from urllib.parse import quote

con = sqlite3.connect('../storage/data.db')
cur = con.cursor()

cur.execute('select * from recipes')

for row in cur.fetchall():
	if row[2] is not None:
		name = unquote(unquote(row[2]))
	else:
		name =  None

	if row[3] is not None:
		note = unquote(unquote(row[3]))
	else:
		note = None
	
	if row[4] is not None:
		yiel = unquote(unquote(row[4]))
	else:
		yiel = None

	if row[5] is not None:
		directions = unquote(unquote(row[5]))
	else:
		directions = ""
	
	query = 'update recipes set recipe_name=(?), recipe_notes=(?), recipe_yields=(?), recipe_directions=(?) where recipe_id=?'

	cur.execute(query, (
		name
		, note
		, yiel
		, directions
		, row[0])
	)

	con.commit()

cur.execute('select * from recipe_ingredients')

for row in cur.fetchall():
	if row[2] is not None:
		name = unquote(unquote(row[2]))
	else:
		name =  None

	
	query = 'update recipe_ingredients set ingredient_name=(?) where recipe_id=?'

	cur.execute(query, (
		name
		, row[0])
	)

	con.commit()

cur.execute('select * from recipe_tags')
for row in cur.fetchall():
	if row[2] is not None:
		name = unquote(unquote(row[2]))
	else:
		name =  None

	
	query = 'update recipe_tags set tag_name=(?) where recipe_id=?'

	cur.execute(query, (
		name
		, row[0])
	)

	con.commit()

con.close()