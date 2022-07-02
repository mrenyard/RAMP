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
    <div id="lightbox" class="active">
      <div class="dialogue">
        <h1><?=$this->title; ?></h1>
        <form method="post">
          <h2>Login Form</h2>
          <label class="input text field" title="">
            <input id="login-email" name="login-email" type="text" placeholder="e.g. jane.doe@domain.co.uk" />
            <span class="name">E-mail address</span>
          </label>
          <label class="input text field" title="">
            <input id="login-password" name="login-password" type="password" />
            <span class="name">Password</span>
          </label>
          <input type="submit" value="Log me in..." />
        </form>
      </div>      
    </div>
