<?php
/**
 * RAMP - Rapid web application development environment for building flexible, customisable web systems.
 *
 * This program is free software; you can $redistribute it and/or modify it under the terms of the
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
 */
namespace ramp;
$red = (isset($_POST['red'])) ? $_POST['red'] : 255;
$green = (isset($_POST['green'])) ? $_POST['green'] : 255;
$blue = (isset($_POST['blue'])) ? $_POST['blue'] : 255;
require_once('../../../load.ini.php');
if (isset($_GET['scratch'])) { $GLOBALS["cssScratch"] = $_GET['scratch']; unset($_GET['scratch']); }
?>
<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8">
    <title>Style Guide and CSS Design Patterns of a RAMP Application - <?=\ramp\SETTING::$RAMP_DOMAIN; ?></title>
    <meta name="viewport" content="width=device-width,initial-scale=1,interactive-widget=resizes-content">
<?php include("../../../head.php"); ?>
  </head>
  <body id="<?=str_replace('.', '-', \ramp\SETTING::$RAMP_DOMAIN) ?>" class="content-page">
    <main id="main">
      <header><a href="#main" title="Full set of icons avalible with RAMP">#</a>
        <h1>Full Icon Set</h1>
        <!-- <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Iusto iure id fuga dolore ea ullam, iste cumque aliquam maxime impedit soluta. Omnis, dolor veritatis. Optio veritatis veniam ullam libero tenetur.</p> -->
      </header>
      <section class="box gallery icons">
        <header>
          <h2>Dymanic ICON Set (icon-*)</h2>  
        </header>
<?php
$dir =  '../img/dynamic/';
chdir($dir);
$matches = glob('icon-*.svg.php');
if(is_array($matches) && !empty($matches)){
  foreach($matches as $match){
    $file = \str_replace('.svg.php', '', $match);
    $fileName = \str_replace('icon-', '', $file);
    // $path = explode('/',strtolower(trim($_SERVER["PATH_INFO"],'/')));
    // $file = explode('-', $path[0]);
    // $type = array_shift($file);
    // $size = str_replace('x','', array_shift($file));
    // $name = implode('-', $file);
    // $fillColor = explode(',', $path[1]);
    // $fillColorRed = $fillColor[0];
    // $fillColorGreen = $fillColor[1];
    // $fillColorBlue = $fillColor[2];
    // $scale = isset($path[2]) ? $path[2] : 1;
    // $rescaleSize = ($size * $scale);
?>
      <figure class="icon" id="<?=$file; ?>">
        <a href="#<?=$file; ?>"><img src="../img/svg.php/<?=$file; ?>/<?=$red; ?>,<?=$green; ?>,<?=$blue; ?>/2"></a>
        <figcaption><?=$fileName; ?></figcaption>
      </figure>
<?php }} ?>
        <footer><form method="post">
          <input type="number" name="red" min="0" max="255">&nbsp;<input type="number" name="green" min="0" max="255">&nbsp;<input type="number" name="blue" min="0" max="255">
          <input type="submit" value="Set preffered RGB icon color">
        </form></footer>
      </section>
    </main>
  </body>
</html>
