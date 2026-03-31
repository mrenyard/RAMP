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
namespace ramp;

$page = view\WebRoot::getInstance();
$data = ($page->type == view\PageType::DATA);
$index = (!$data && $page->type == view\PageType::INDEX);
?>
    <main id="main"><?php if ($data) { ?><form action="#main" method="post"><a href="#main" title="Here for Page Main content: <?=$this->title; ?>">#</a>
      <h1><?=$this->heading; ?></h1>
<?=$this->children; ?>
    </form></main>
<?php } else { ?><a href="#main" title="Here for Page Main content: <?=$this->title; ?>">#</a>
      <header>
        <h1><?=$this->heading; ?></h1>
        <p><?=$this->summary; ?></p>
<?=$this->extendedSummary; ?>
      </header>
<?=$this->extendedContent; ?>
<?php if (!$index) { $this->children; ?>
      <footer>
<?=$page->footnotes; ?>
         <a href="#top" title="Jump back to Top-of-page (in-page Navigation)">top</a>
      </footer>  
<?php } ?>
    </main>
<?php if ($index) { $this->children; } } ?>
