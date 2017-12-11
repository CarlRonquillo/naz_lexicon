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
  <link rel="stylesheet" href="<?php echo base_url("resources/css/tags.css"); ?>">
  
</head>
<body>

	<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="">
        <img alt="Brand" class="img-responsive" src="<?php echo base_url("resources/images/Nazarene Logo-White.png"); ?>">
        <?php echo anchor("home/index","NAZARENE LEXICON", ["class" => 'navbar-brand']); ?>
      </a>
    </div>
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav navbar-right">
        <li class="active"><?php echo anchor("home/index","Home"); ?></li>
        <li><a href="#">Dictionary<span class="sr-only">(current)</span></a></li>

        <?php if(!empty($this->session->userdata('Username')))
          { ?>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
              <?php echo $this->session->userdata('FirstName').' '.$this->session->userdata('LastName'); ?>
              <span class="caret"></span>
            </a>
              <ul class="dropdown-menu" role="menu">
                <li><?php echo anchor("#","Accounts") ?></li>
                <li><?php echo anchor("home/Terms?Language=".(null !== $this->session->userdata('language_set') ? $this->session->userdata('language_set') : 1),"Terms") ?></li>
                <li><?php echo anchor("home/SignUp","Sign Up") ?></li>
                <li class="divider"></li>
                <li><?php echo anchor("home/logout","Logout") ?></li>
              </ul>
          </li>
        <?php } else { ?>
          <li><?php echo anchor("home/login","Login"); ?></li>
        <?php } ?>
      </ul>
    </div>
  </div>
</nav>