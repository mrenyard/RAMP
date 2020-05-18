<?php
/**
 * Svelte - Rapid web application development enviroment for building
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
namespace svelte;

/**
 * Application Settings class, responsible for holding configuration details for the local aspects
 * of an individual Svelte Project.
 *
 * Usually generated from Svelte's local initialization file, called svelte.ini.
 * Which SHOULD usually be located alongside the public website root folder. i.e.:
 * ```
 * -- ~/                    (secure ftp folder)
 *  |
 *  +-- public_html         (website root folder)
 *  | |-- index.html        (static homepage)?
 *  | |-- robots.txt
 *  | |-- humans.txt
 *  | |-- controller.php    (svelte controller file)
 *  |
 *  +-- svelte.ini          (local initialization file)
 * ```
 */
class SETTING
{
  /**
   * Domain under which this application is run.
   */
  public static $SVELTE_DOMAIN;

  /**
   * Directory from which to locate application specific code
   * related only to this particular project.
   */
  public static $SVELTE_LOCAL_DIR;

  /**
   * Concrete BusinessModelManager used to handle data layer
   * (fully qualified namespace and class name).
   * - e.g. {@link svelte\model\business\SQLBusinessModelManager}
   */
  public static $SVELTE_BUSINESS_MODEL_MANAGER;

  /**
   * Fully qualified namespace for business model classes.
   * - e.g. svelte\model\business
   */
  public static $SVELTE_BUSINESS_MODEL_NAMESPACE;

  /**
   * Authenticatible Unit of specific business model Record.
   */
  public static $SVELTE_AUTHENTICATABLE_UNIT;

  /**
   * Database connection string.
   * replace [host] and [dbname] where appropriate
   * - MS SQL Server and Sybase with PDO_DBLIB
   *  - dbconnection = mssql:host=[host];dbname=[dbname]
   *  - dbconnection = sybase:host=[host];dbname=[dbname]
   * - MySQL with PDO_MYSQL
   *  - dbconnection = mysql:host=[host];dbname=[dbname]
   * - SQLite Database
   *  - dbconnection = sqlite:my/database/path/database.db
   */
  public static $DATABASE_CONNECTION;

  /**
   * Database user name.
   */
  public static $DATABASE_USER;

  /**
   * Database password.
   */
  public static $DATABASE_PASSWORD;

  /**
   * Maximum rows to display on a single page from a single query.
   */
  public static $DATABASE_MAX_RESULTS;

  /**
   * SALT value used for password encryption.
   */
  public static $SECURITY_PASSWORD_SALT;

  /**
   * Name to be used when sending e-mail.
   */
  public static $EMAIL_FROM_NAME;

  /**
   * Sender's e-mail address.
   */
  public static $EMAIL_FROM_ADDRESS;

  /**
   * 'Reply to' e-mail address.
   */
  public static $EMAIL_REPLY_ADDRESS;

  /**
   * Boolean flag for testing mode.
   */
  public static $TEST_ON;

  /**
   * Boolean reset session used in testing.
   */
  public static $TEST_RESET_SESSION;
}
