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
namespace ramp\view;

$page = WebRoot::getInstance();
$data = ($page->type == PageType::DATA);
$index = (!$data && $page->type == PageType::INDEX);
?>
    <main id="main"><?php if ($data) { ?><form action="post"><a href="#main" title="Here for Page Main content: <?=$this->title; ?>">#</a>
      <h1><?=$this->heading; ?></h1>
<?=$this->children; ?>
    </form></main>
<?php } else { ?><a href="#main" title="Here for Page Main content: <?=$this->title; ?>">#</a>
      <header>
        <h1><?=$this->heading; ?></h1>
        <p><?=$this->summary; ?></p>
<?=$this->extendedSummary; ?>
      </header>
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Vitae obcaecati aliquam est commodi velit ab consequatur quaerat veniam consequuntur sapiente quisquam distinctio asperiores officia dolorum illo fugiat, quia, inventore placeat!</p>
      <div class="text input field">
        <label for="t1">Label</label>
        <input id="t1" name="t1" type="text" tabindex="0" placeholder="[PLACEHOLDER]" required="required" pattern="[a-zA-Z _\-:]*">
        <span class="hint">Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugit tempore cum debitis voluptatem alias quos esse nostrum illo repudiandae incidunt. Magnam dolorum iste libero esse odit sit harum corrupti dolore?</span>
      </div>            
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Vitae obcaecati aliquam est <a href="//bbc.co.uk/news" title="External link for the British Brodcasting Company">commodi velit</a> ab consequatur quaerat veniam consequuntur sapiente quisquam distinctio asperiores officia dolorum illo fugiat, quia, inventore placeat!</p>
<?=$this->extendedContent; ?>
<?php if (!$index) { $this->children; } ?>
    </main>
<?php if ($index) { $this->children; } } ?>
