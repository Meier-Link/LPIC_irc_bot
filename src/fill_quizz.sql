-- --------------------------------------------------------------
-- fill_quizz.sql
-- Used to fill 'question' and 'answer' tables
-- IMPORTANT:
--  - Do not forget to escape simple quotes in strings ;
--  - for the right answers, list letters without spaces (example: 'ab', or 'acd')
--  - Each time I'll execute the script, I'll comment insert clauses runed
-- Levels:
-- 1 = LPI-101
-- 2 = LPI-102
-- 3 = LPI-201
-- 4 = LPI-202
-- 5 = LPI-301
-- 6 = LPI-302
-- 7 = LPI-303
-- Languages:
-- 1 = 'fr'
-- 2 = 'en'
-- And very important : sorry for my bad english :o)
-- --------------------------------------------------------------

--INSERT INTO question (q_txt, q_lvl, q_lang) VALUES 
--("Comment est représenté le périphérique IDE esclave du deuxième contrôleur ?", 1, 1);
--INSERT INTO answer (a_q_id, a_is_right, a_txt) VALUES (1, 0, "hda"), (1, 0, "sdc"), (1, 0, "hdc1"), (1, 1, "hdd");

--INSERT INTO question (q_txt, q_lvl, q_lang) VALUES 
--("Which of the following permissions have the SUID value set ?", 1, 2);
--INSERT INTO answer (a_q_id, a_is_right, a_txt) VALUES (2, 1, "4755"), (2, 0, "2755"), (2, 0, "1755");

--INSERT INTO question (q_txt, q_lvl, q_lang) VALUES 
--("When typing at the shell, what character can be used to split a command across multiple lines ?", 1, 2);
--INSERT INTO answer (a_q_id, a_is_right, a_txt) VALUES (3, 0, "&"), (3, 1, "\"), (3, 0, "/");

--INSERT INTO question (q_txt, q_lvl, q_lang) VALUES 
--("What option on the tail command will cause it to keep displaying new lines as they are written to the file (e.g. when viewing current log files) ?", 1, 2);
--INSERT INTO answer (a_q_id, a_is_right, a_txt) VALUES (4, 0, "-o"), (4, 0, "-r"), (4, 0, "-i"), (4, 0, "-l"), (4, 1, "-f");

--INSERT INTO question (q_txt, q_lvl, q_lang) VALUES 
--("Which of the following will run command2, only if command1 is successful ?", 1, 2);
--INSERT INTO answer (a_q_id, a_is_right, a_txt) VALUES 
--(5, 0, "command1 > command2"), (5, 0, "command1 & command2"), (5, 1, "command1 && command2"), 
--(5, 0, "command1 | command2"), (5, 0, "command1 || command2");

--INSERT INTO question (q_txt, q_lvl, q_lang) VALUES 
--("What umask value would ensure that, by default, new files and directories created would not give any permissions except to the owner ?", 1, 2);
--INSERT INTO answer (a_q_id, a_is_right, a_txt) VALUES (6, 0, "0066"), (6, 0, "0007"), (6, 1, "0077");

--INSERT INTO question (q_txt, q_lvl, q_lang) VALUES 
--("Which of the following is often used for the configuration of X11 ?", 1, 2);
--INSERT INTO answer (a_q_id, a_is_right, a_txt) VALUES 
--(7, 1, "/etc/X11/xorg.conf"), (7, 0, "/etc/X11/xorgConfig"), (7, 0, "/etc/X11/xconfiguration"), 
--(7, 0, "/etc/Xconfig"), (7, 0, "/etc/X.conf");

--INSERT INTO question (q_txt, q_lvl, q_lang) VALUES 
--("What is the octal value for the chmod command to set a file so that it can be read and written to by the owner, and read by the group and all other users? It should not be executable.", 1, 2);
--INSERT INTO answer (a_q_id, a_is_right, a_txt) VALUES (8, 1, "644"), (8, 0, "755"), (8, 0, "664"), (8, 0, "640");
