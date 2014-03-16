<!--
    html.php
    
    Copyright 2014 Jeremie aka Meier Link <jeremie.balagna@autistici.org>
    
    This program is free software licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 France License.
    To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/fr/
    or send a letter to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
    
    
-->

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
  <title><?php echo Conf::get('SITE_NAME'); ?> :: <?php echo $controller->title; ?></title>
  <link type="image/x-icon" href="/includes/img/og-logo.ico" rel="shortcut icon">
  <meta name="og:title" content="MyTinyFrameWork : <?php echo $controller->title; ?>">
  <meta name="description" content="<?php echo $controller->desc(); ?>">
  <meta name="og:description" content="<?php echo $controller->desc(); ?>">
  <meta name="image" content="/includes/img/logo.png">
  <meta name="og:image" content="/includes/img/logo.png">
  <meta http-equiv="content-type" content="text/html;charset=utf-8" />
  <?php
  if (!empty($controller->html_head))
    foreach($controller->html_head as $chead)
      echo $chead;
  
  foreach($controller->getJsFiles() as $js)
  {
    ?><script src="<?php echo $js; ?>" type="text/javascript" ></script><?php
  }
  
  foreach($controller->getCssFiles() as $css)
  {
    ?><link href="/<?php echo $css; ?>" rel="stylesheet" type="text/css" /><?php
  }
  ?>
</head>

<body>
  <div id="header">
    <h1><?php echo Conf::get('SITE_NAME'); ?></h1>
    <div style="float:left; text-align:right; width:99%; margin-top:-84px">
      <?php if (isset($_SESSION['user']) && !is_null($_SESSION['user'])) { ?>
      Bienvenue, <?php echo $_SESSION['user']->u_name(); ?>.
      <?php } ?>
      <!--
      <a href="/main/hl/fr"><img alt="fr" title="Français" src="/includes/img/hl/fr.png"/></a>&nbsp;
      <a href="/main/hl/en"><img alt="en" title="English" src="/includes/img/hl/en.png"/></a>
      -->
    </div>
    <div id="menu">
      <a class="menu" style="text-decoration:none" href="/index">Accueil</a>
      <?php if (isset($_SESSION['user']) && !is_null($_SESSION['user'])) { ?>
      <?php if ($controller->isAdmin()) { ?>
      <a class="menu" style="text-decoration:none" href="/admin/home">Query Page</a>
      <a class="menu" style="text-decoration:none" href="/admin/users">Manage users</a>
      <a class="menu" style="text-decoration:none" href="/admin/bot">Bot status</a>
      <?php } ?>
      <a class="menu" style="text-decoration:none" href="/main/logout">Logout</a>
      <?php } else { ?>
      <a class="menu" style="text-decoration:none" href="/main/login">Login</a>
      <?php } ?>
    </div>
  </div>

  <div id="main">
    <div id="logs">
      <?php
        if (Log::isLogs())
          echo Log::toHTML();
      ?>
    </div>
    <?php
    echo $controller->ariane() . "<br/><hr/>";
    if (!file_exists($controller->relPath()))
      include("view/main/notfound.php");
    else
      include($controller->relPath()); 
    ?>
  </div>
  
  <div id="footer">
    <hr>
    <div class="middle" style="">
      Site en cours de développement
    </div>
    <hr/>
    <div class="left">
      <div class="license-logo">
        <a href="/about/licence" rel="license">
          <img src="http://i.creativecommons.org/l/by-sa/2.0/fr/80x15.png" style="border-width: 0pt;" alt="Creative Commons License">
        </a>
      </div>
      <div class="license-txt">
        <i><?php echo Conf::get('SITE_NAME'); ?></i> by <?php echo Conf::get('SITE_OWNER'); ?><br/>
        <a rel="cc:morePermissions" href="/about/licence" xmlns:cc="http://creativecommons.org/ns#">Plus d'informations</a>
      </div>
    </div>
    <div class="right">
      <a style="text-decoration:none" href="/about/home" class="link">À propos</a>
    </div>
  </div>
</body>
