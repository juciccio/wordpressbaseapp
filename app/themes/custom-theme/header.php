<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php bloginfo('description'); ?>" />
    <meta name="keywords" content="" />

    <title><?php wp_title('|',1,'right'); ?> <?php bloginfo('name'); ?></title>

    <?php
      wp_head();
    ?>
  </head>
  <body <?php body_class(); ?>>