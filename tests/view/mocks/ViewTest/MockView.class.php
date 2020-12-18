<?php
/**
 * Testing - Svelte - Rapid web application development enviroment for building
 *  flexible, customisable web systems.
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
 * @version 0.0.9;
 */
namespace tests\svelte\view\mocks\ViewTest;

use svelte\view\View;

/**
 * Mock Concreate implementation of \svelte\view\View for testing login against.
 */
class MockView extends View
{
  /**
   */
  public function render()
  {
?>
        <form action="<?=$_SERVER['REQUEST_URI']; ?>" method="post">
<?php if ($this->allowRegistration) { ?>
          <div class="input text field" title="User Name">
            <label for="person:new:uname">User Name</label>
            <input id="person:new:uname" name="person:new:uname" type="text" value="<?=$this->uname; ?>" placeholder="HINT" />
          </div>
          <div class="input text field" title="givenName">
            <label for="person:new:given-name">givenName</label>
            <input id="person:new:given-name" name="person:new:given-name" type="text" value="<?=$this->givenName; ?>" placeholder="HINT" />
          </div>
          <div class="input text field" title="familyName">
            <label for="person:new:family-name">familyName</label>
            <input id="person:new:family-name" name="person:new:family-name" type="text" value="<?=$this->familyName; ?>" placeholder="HINT" />
          </div>
          <div class="input text field" title="e-mail address to associate with your account">
            <label for="user:new:email">e-mail address</label>
            <input id="user:new:email" name="user:new:email" type="text" value="<?=$this->email; ?>" placeholder="HINT" />
          </div>
<?php } ?>
          <div class="input text field" title="confirm previously supplied e-mail address">
            <label for="login-email">Confirm e-mail</label>
            <input id="login-email" name="login-email" type="text" />
          </div>
<?php if ($this->forcePasswordField) { ?>
          <div class="input text field" title="Password associated with your account (e-mailed to you following your first quote)">
            <label for="login-password">Password</label>
            <input id="login-password" name="login-password" type="password" />
          </div>
<?php } ?>
          <div class="input submit" title="submit">
            <input type="submit" value="Submit" />
          </div>
        </form>
<?php
  }
}
