import sqlite3

conn = sqlite3.connect('data.db')
c = conn.cursor()

c.execute('delete from sessions;')
c.execute('delete from sqlite_sequence where name=\'sessions\';')
c.execute('delete from view_log;')
c.execute('delete from sqlite_sequence where name=\'view_log\';')
c.execute('delete from auth_log;')
c.execute('delete from sqlite_sequence where name=\'auth_log\';')

conn.commit()