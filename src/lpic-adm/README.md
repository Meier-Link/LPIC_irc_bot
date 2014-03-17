LPIC-Adm website
================

This directory contains all specific files added to the web site base.
This one is my [MTFW framwork](https://github.com/Meier-Link/mtfw).

Modifications
-------------

Following modifications directory by directory ...

### secure

We'll use the SQLite driver to directly access the lpic database with PHP (PHP wants the PDO sqlite driver to work properly).

### htdocs/model

* Question
* Answer
* Lang
* Level

Lang and Level will be added only for convenience.

### htdocs/controller

* Admin: adding a page to allow bot start/restart directly from Webserver (Will want some tests)
* Manage: Allowed users will edit/add questions and anwsers from this section.

### htdocs/view
* admin: bot.php (start/restart bot), home.php
* manage: home.php (display available questions), edit.php (to edit/add question)
* main: only content customization 

The htdocs tree will be reproduced here with only changed/added files

