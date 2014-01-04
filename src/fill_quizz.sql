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

--INSERT INTO question (q_txt, q_lvl, q_lang) VALUES
--("La commande ________ permet de lister les périphériques USB présents sur la machine.", 1, 1);
--INSERT INTO answer (a_q_id, a_is_right, a_txt) VALUES (9, 0, "lspci"), (9, 1, "lsusb"), (9, 0, "ls /dev");

--INSERT INTO question (q_txt, q_lvl, q_lang) VALUES
--("Comment peut-on afficher le contenu d'un fichier en sens inverse ?", 1, 1);
--INSERT INTO answer (a_q_id, a_is_right, a_txt) VALUES (10, 0, "cat -r"), (10, 0, "tail"), (10, 1, "tac"), (10, 0, "sort -r");

--INSERT INTO question (q_txt, q_lvl, q_lang) VALUES
--("Le choix du périphérique de démarrage de la machine se fait", 1, 1);
--INSERT INTO answer (a_q_id, a_is_right, a_txt) VALUES
--(11, 1, "Dans le BIOS"), (11, 0, "Avec une option du chargeur de démarrage (boot loader)"), (11, 0, "Automatiquement");

--INSERT INTO question (q_txt, q_lvl, q_lang) VALUES
--("Quels droits sont positionnés par la commande chmod 1777 un_repertoire sur un répertoire (plusieurs réponses) ?", 1, 1);
--INSERT INTO answer (a_q_id, a_is_right, a_txt) VALUES
--(12, 1, "Elle permet à tous les utilisateurs d'accéder au répertoire."),
--(12, 0, "Elle interdit l'accès aux autres utilisateurs que le groupe."),
--(12, 1, "Elle permet à tous de créer des fichiers dans ce répertoire, mais pas de modifier ou d'effacer ceux des autres.");

--INSERT INTO question (q_txt, q_lvl, q_lang) VALUES
--("La commande locate", 1, 1);
--INSERT INTO answer (a_q_id, a_is_right, a_txt) VALUES
--(13, 0, "fonctionne comme find mais avec des options différentes."),
--(13, 1, "est plus efficace que find car elle utilise un index."),
--(13, 0, "permet de changer la langue par défaut de Linux.");

--INSERT INTO question (q_txt, q_lvl, q_lang) VALUES
--("Les droits d'un fichier sont « rw-r--r-- ». La valeur de umask est:", 1, 1);
--INSERT INTO answer (a_q_id, a_is_right, a_txt) VALUES (14, 0, "666"), (14, 1, "022"), (14, 0, "123"), (14, 1, "133");

--INSERT INTO question (q_txt, q_lvl, q_lang) VALUES
--("Quelle différence y a-t-il entre les systèmes de fichiers ext2 et ext3 ?", 1, 1);
--INSERT INTO answer (a_q_id, a_is_right, a_txt) VALUES
--(15, 0, "ext3 est un nouveau système, incompatible avec ext2."),
--(15, 1, "ext3 est une simple extension de ext2 comprenant la journalisation."),
--(15, 1, "on peut passer de ext2 à ext3 et inversement très facilement.");

--INSERT INTO question (q_txt, q_lvl, q_lang) VALUES
--("Quelle commande utiliser pour afficher les messages du noyau lors du démarrage du système ?", 1, 1);
--INSERT INTO answer (a_q_id, a_is_right, a_txt) VALUES (16, 0, "mess"), (16, 1, "dmesg"), (16, 0, "bootmsg"), (16, 0, "lsmsg");

--INSERT INTO question (q_txt, q_lvl, q_lang) VALUES
--("Quelle est la commande qui permet d'afficher toute les variables ?", 1, 1);
--INSERT INTO answer (a_q_id, a_is_right, a_txt) VALUES (17, 1, "set"), (17, 0, "varlist"), (17, 0, "vshow var"), (17, 0, "ps");

--INSERT INTO question (q_txt, q_lvl, q_lang) VALUES
--("Quelle commande permet d’effacer tous les fichiers et les sous-répertoires de l'arborescence /home/nicolas/truc/ ?", 1, 1);
--INSERT INTO answer (a_q_id, a_is_right, a_txt) VALUES
--(18, 0, "rm -r /home/nicolas/"), (18, 0, "rmdir /home/nicolas/truc"), (18, 1, "rm -r /home/nicolas/truc/*");

--INSERT INTO question (q_txt, q_lvl, q_lang) VALUES
--("La commande pstree permet d’afficher", 1, 1);
--INSERT INTO answer (a_q_id, a_is_right, a_txt) VALUES
--(19, 1, "l'arborescence des processus par « famille »."),
--(19, 0, "uniquement les processus d’un utilisateur."),
--(19, 0, "l'occupation du processeur par les processus.");

--INSERT INTO question (q_txt, q_lvl, q_lang) VALUES
--("La commande killall permet de", 1, 1);
--INSERT INTO answer (a_q_id, a_is_right, a_txt) VALUES
--(20, 0, "tuer tous les processus d'un utilisateur."),
--(20, 1, "tuer un processus sans connaître son PID."),
--(20, 0, "envoyer tous les signaux standard à un processus.");

--INSERT INTO question (q_txt, q_lvl, q_lang) VALUES
--("La commande paste permet de", 1, 1);
--INSERT INTO answer (a_q_id, a_is_right, a_txt) VALUES
--(21, 0, "coller un fichier à la suite d'un autre."),
--(21, 1, "joindre deux fichiers côte à côte simplement sans critères."),
--(21, 0, "laisser un processus en mémoire.");
