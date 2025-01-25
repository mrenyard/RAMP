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
$red = (isset($_POST['red'])) ? $_POST['red'] : 0;
$green = (isset($_POST['green'])) ? $_POST['green'] : 0;
$blue = (isset($_POST['blue'])) ? $_POST['blue'] : 0;
?>
<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8">
    <title>Style Guide and CSS Design Patterns of a RAMP Application</title>
    <meta name="viewport" content="width=device-width,initial-scale=1,interactive-widget=resizes-content">
    <link rel="stylesheet" href="../import/icons.css.php">
    <link rel="stylesheet" href="../import/base.css">
    <link rel="stylesheet" href="../import/layout.css">
    <link rel="stylesheet" href="../import/modual-stickyfoot-footnotes.css">
    <link rel="stylesheet" href="../import/modual-page-nav-search.css">
    <style>
.gallery {
  display: grid; gap: 2rem;
  grid-columns: main-left / span 2;
  grid-template-columns: repeat(auto-fit, 12rem);
  max-width: calc(100% - 15rem);
  > header, > footer { grid-column: 1 / span all; grid-row: auto;}
  > figure {
    margin: 0; padding: 0 0 1rem;
    min-width: 12rem; height: 100%;
    align-items: center;
    display: flex; flex-direction: column;
    background-color: rgb(0 0 0 / .25);
    border-radius: 1rem;
    > img {
      width: 2rem; max-width: 100%;
      height: 9rem; margin-bottom: -2em;
      background-color: transparent;
    }
  }
}
    </style>
  </head>
  <body>
    <main id="main"><form action="#main" method="post" class="gallery">
      <header><a href="#main" title="Full set of icons available with RAMP">#</a>
        <h1>Full Dymanic ICON Set (icon-*)</h1>
      </header>
<?php
$dir =  '../img/dynamic/';
chdir($dir);
$matches = glob('icon-*.svg.php');
if(is_array($matches) && !empty($matches)){
  foreach($matches as $match){
    $file = \str_replace('.svg.php', '', $match);
    $fileName = \str_replace('icon-', '', $file);
?>
      <figure class="icon" id="<?=$file; ?>">
        <img src="../img/svg.php/<?=$file; ?>/<?=$red; ?>,<?=$green; ?>,<?=$blue; ?>/2">
        <figcaption><a href="../img/svg.php/<?=$file; ?>/<?=$red; ?>,<?=$green; ?>,<?=$blue; ?>/5"><?=$fileName; ?></a></figcaption>
      </figure>
<?php }} ?>
    </section>
    <footer>
        <menu>
          <li><input type="number" name="red" min="0" max="255" size="4">&nbsp;<input type="number" name="green" min="0" max="255" size="4">&nbsp;<input type="number" name="blue" min="0" max="255" size="4"></li>
          <li><input type="submit" value="Set preffered RGB icon color"></li>
        </menu>
      </footer>
    </form></main>
  </body>
</html>
