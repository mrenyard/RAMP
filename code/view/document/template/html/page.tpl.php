<?php
/**
 * RAMP - Rapid web application development environment for building flexible, customisable web systems.
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
 * @package RAMP
 * @version 0.0.9;
 */
?>
    <dialog id="modal" open><form method="post">
      <header>
        <h2>Modal Dialog</h2>
        <button value="cancel" formmethod="dialog">X</button>
      </header>
      <footer>
        <button autofocus formmethod="dialog">OK</button>
      </footer>
    </form></dialog>
    <header role="banner">
<?php include("header.php"); ?>
    </header>
    <main id="main">
      <header><a href="#header" title="Header full description"></a>
        <h1><?=$this->heading; ?></h1>
        <p><?=$this->summary; ?></p>
      </header>
      <section id="section-one"><a href="#section-one" title="Section one description">#</a>
        <p>...</p>
      </section>
      <?=$this->children ?>
      <section id="section-two"><a href="#section-two" title="Section two description">#</a>
        <p>...</p>
      </section>
    </main>
<?php include("site-navigation.php"); ?>
    <footer role="contentinfo">
<?php include("footer.php"); ?>
    </footer>
