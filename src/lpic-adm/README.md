LPIC-ADM website
================

This directory contain all specific files added to the web site base.
This one is my [MTFW framwork](https://github.com/Meier-Link/mtfw).

Modifications
-------------

Following modifications directory by directory ...

### secure

We'll use the SQLite driver to directly access the lpic database with PHP (PHP want the PDO sqlite driver to work properly).

### htdocs/model

* Question
* Answer
* Lang
* Lvl
Lang and Lvl will be added only for convenience.

### htdocs/controller

* Admin : adding a page to allow bot start/restart directly from Webserver (Will want some tests)
* QnA : Allowed users will edit/add questions and anwsers from this section. It will contain a page to see available q/a and a second one to edit/add q/a.

The htdocs tree will be reproduced here with only changed/added files

