<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Nazarene Lexicon™</title>
  <link rel="stylesheet" href="<?php echo base_url("resources/css/bootstrap.css"); ?>"/>
  <link rel="stylesheet" href="<?php echo base_url("resources/css/jquery-ui.css"); ?>">
  
</head>
<body>

	<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="<?php echo base_url("home/index"); ?>">
        <img alt="Brand" class="img-responsive" src="<?php echo base_url("resources/images/Nazarene Logo-White.png"); ?>">
        <?php echo anchor("home/index","NAZARENE LEXICON", ["class" => 'navbar-brand']); ?>
      </a>
    </div>
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#">Home</a></li>
        <li class="active"><a href="#">Dictionary<span class="sr-only">(current)</span></a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Admin<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><?php echo anchor("#","Accounts") ?></li>
            <li><?php echo anchor("home/Terms","Terms") ?></li>
            <li><?php echo anchor("#","Sign Up") ?></li>
            <li class="divider"></li>
            <li><?php echo anchor("#","Logout") ?></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>