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

/**
 * Application Settings class, responsible for holding configuration details for the local aspects
 * of an individual RAMP Project.
 *
 * Usually generated from RAMP's local initialization file, called ramp.ini.
 * Which SHOULD usually be located alongside the public website root folder. i.e.:
 * ```txt
 * -- ~/                    (secure ftp folder)
 *  |
 *  +-- public_html         (website root folder)
 *  | |-- index.html        (static homepage)?
 *  | |-- robots.txt
 *  | |-- humans.txt
 *  | |-- controller.php    (ramp controller file)
 *  |
 *  +-- ramp.ini          (local initialization file)
 * ```
 */
class SETTING
{
  /**
   * Domain under which this application is run.
   * @var string
   */
  public static string $RAMP_DOMAIN;

  /**
   * Directory from which to locate application specific code
   * related only to this particular project.
   * @var string
   */
  public static string $RAMP_LOCAL_DIR;

  /**
   * Concrete BusinessModelManager used to handle data layer
   * (fully qualified namespace and class name).
   * - e.g. \ramp\model\business\SQLBusinessModelManager
   * @var string
   */
  public static string $RAMP_BUSINESS_MODEL_MANAGER;

  /**
   * Fully qualified namespace for business model classes.
   * - e.g. \ramp\model\business
   * @var string
   */
  public static string $RAMP_BUSINESS_MODEL_NAMESPACE;

  /**
   * Authenticatable Unit of specific business model Record.
   * @var string
   */
  public static string $RAMP_AUTHENTICATABLE_UNIT;

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
   * @var string
   */
  public static string $DATABASE_CONNECTION;

  /**
   * Database user name.
   * @var string
   */
  public static string $DATABASE_USER;

  /**
   * Database password.
   * @var string
   */
  public static string $DATABASE_PASSWORD;

  /**
   * Maximum rows to display on a single page from a single query.
   * @var int
   */
  public static int $DATABASE_MAX_RESULTS;

  /**
   * SALT value used for password encryption.
   * @var string
   */
  public static string $SECURITY_PASSWORD_SALT;

  /**
   * Name to be used when sending e-mail.
   * @var string
   */
  public static string $EMAIL_FROM_NAME;

  /**
   * Sender's e-mail address.
   * @var string
   */
  public static string $EMAIL_FROM_ADDRESS;

  /**
   * 'Reply to' e-mail address.
   * @var string
   */
  public static string $EMAIL_REPLY_ADDRESS;

  /**
   * Templete for the welcome email.
   * Sent at regisitration includes login details and
   * enencripted auto generated password
   * @var string
   */
  public static string $EMAIL_WELCOME_TEMPLATE;

  /**
   * Welcome email template type.
   * one of (html | text)
   * @var string
   */
  public static string $EMAIL_WELCOME_TEMPLATE_TYPE;

  /**
   * Welcome email subject line.
   * The single line of text people see when they receive your email.
   * @var string
   */
  public static string $EMAIL_WELCOME_SUBJECT_LINE;

  /**
   * Boolean flag for testing mode.
   * @var bool
   */
  public static bool $TEST_ON;

  /**
   * Boolean reset session used in testing.
   * @var bool
   */
  public static bool $TEST_RESET_SESSION;

  /**
   * Boolean operating in Develpment Mode Environment.
   * @var bool
   */
  public static bool $DEV_MODE;

  /**
   * Array of requested scratch style shreets.
   * @var array
   */
  public static array $SCRATCH__CSS;
}
