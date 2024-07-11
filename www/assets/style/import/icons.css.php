<?php
/**
 * Content-Type text/css; charset=utf-8
 * /img/svg.php/[type]-x[size]-[name]/r,g,b
 * 
 * TODO:mrenyard: This should eventualy be a static file generated during theming of style sheets were part of that process would include selecting relevant icons from those avalible. 
 */
header('Content-Type: text/css; charset=utf-8');
?>
:root {
  --icon-x12-external: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 12 12" width="12" height="12"><?php include('../img/dynamic/icon-x12-external.svg.php'); ?></svg>');
  --icon-x16-menu: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><?php include('../img/dynamic/icon-x16-menu.svg.php'); ?></svg>');
  --icon-x16-info: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><?php include('../img/dynamic/icon-x16-info.svg.php'); ?></svg>');
  --icon-x16-search-glass: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><?php include('../img/dynamic/icon-x16-search-glass.svg.php'); ?></svg>');
  --icon-x16-star: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><?php include('../img/dynamic/icon-x16-star.svg.php'); ?></svg>');
  --icon-x16-heart: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><?php include('../img/dynamic/icon-x16-heart.svg.php'); ?></svg>');
}
