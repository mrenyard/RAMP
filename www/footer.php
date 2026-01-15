<?php
/**
 * This file CAN BE EDITED to modify the 'content info' (footer) used to  defines the
 * aplication / site wide information such as main contact address, copyright information,
 * leagal information and privacy statements, and found on every page.
 *  
 * -----------------------------------------------------------------------------------------------------
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
if (\ramp\SETTING::$DEV_MODE) { ?>
<!-- footer.php -->
<?php } ?>
      <address>
        RAMP App FOSS Foundation.<br>
        c/o Justified Web Marketng Ltd.<br>
        157 Oakwood Drive,<br>
        Southampton<br>
        SO16 8EL.<br>
        United Kingdom.
      </address>
      <nav>
        <h2>Site Information &amp; Useful Links</h2>
        <ul>
          <li>legal, copyright, privacy etc.
            <ul>
              <li><a href="#">Link One</a></li>
              <li><a href="#">Link Two</a></li>
              <li><a href="#">Link Three</a></li>
              <li><a href="#">Link Four</a></li>
              <li><a href="#">Link Five</a></li>
              <li><a href="#">Link Six</a></li>
            </ul>
          </li>
          <li>Socal Media
            <ul style="display: flex;">
              <li><a href="//x.com/mrenyard">x</a></li>
              <li><a href="//www.instagram.com//mrenyard">Instagram</a></li>
              <li><a href="//github.com/mrenyard">GitHub</a></li>
            </ul>
          </li>
        </ul>
      </nav>
