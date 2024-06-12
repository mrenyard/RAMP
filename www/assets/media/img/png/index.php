<?php
/**
 * MEDIA.server - 
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the
 * GNU General Public License as published by the Free Software Foundation; either version 2 of
 * the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program; if
 * not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 * @author Matt Renyard (renyard.m@gmail.com)
 * @package MEDIA
 * @version 0.0.9;
 */

 $files = scandir('.');
 ?>
<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8">
    <title>Static Images (PNG)</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,interactive-widget=resizes-content">
<?php
foreach ($files as $file) {
  if ($file == '.' || $file == '../' || $file == 'low' || $file == 'index.php' || is_dir($file)) { continue; } ?>
    <link rel="preload" as="image" href="low/<?=$file; ?>" fetchpriority="high">
<?php } ?>
    <link rel="stylesheet" href="//style.ramp.matt-laptop.lan/import/icons.css.php" crossorigin="anonymous">
    <link rel="stylesheet" href="//style.ramp.matt-laptop.lan/import/base.css" crossorigin="anonymous">
  </head>
  <body>
    <main id="main" style="text-align: center; margin: 0 1rem;">
      <header>
        <!-- <a href="#main" title="Style Guide and CSS Design Patterns of a RAMP Application">#</a> -->
        <h1>Static Images (PNG)</h1>
        <!-- <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Iusto iure id fuga dolore ea ullam, iste cumque aliquam maxime impedit soluta. Omnis, dolor veritatis. Optio veritatis veniam ullam libero tenetur.</p> -->
      </header>
<?php
foreach ($files as $file) {
  if ($file == '.' || $file == '../' || $file == 'low' || $file == 'index.php' || is_dir($file)) { continue; }
  list($width, $height) = getimagesize($file);  
?>
      <figure>
        <img alt="Beautiful nature landscape with mountains" style="background-image: url(low/<?=$file; ?>);" src="<?=$file; ?>" width="<?=$width; ?>" height="<?=$height; ?>">
         <figcaption><?=$file; ?></figcaption>
      </figure>
<?php } ?>
    </main>
  </body>
</html>