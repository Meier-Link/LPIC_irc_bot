# !!! Experimental !!!
#
# author: WarLocG <warlocg.wlg@gmail.com>

# dump user table
sqlite3 lpic_quizz.db --line ".dump user" > dump_user.sql
grep INSERT dump_user.sql > dump_user0.sql
# drop table user
sqlite3 lpic_quizz.db --line "DROP TABLE IF EXISTS user;"
# recreate new table user
sqlite3 lpic_quizz.db --line "CREATE TABLE IF NOT EXISTS user (u_id INTEGER PRIMARY KEY AUTOINCREMENT , u_pseudo  VARCHAR(255) UNIQUE NOT NULL, u_start DATETIME NOT NULL, u_score INTEGER);"
# insert user from dump
sqlite3 lpic_quizz.db --line ".read dump_user0.sql"
# using patch
sqlite3 lpic_quizz.db --line ".read patch-20140111-20140113.sql"
# drop dumps
rm dump_user.sql dump_user0.sql