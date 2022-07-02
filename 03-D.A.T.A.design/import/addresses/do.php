<?php
/**
 * RAMP - Rapid web application development enviroment for building
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
 * @package RAMP.Addresses
 * @version 0.0.9;
 */

$COUNTRY_CODE = (isset($_SERVER["PATH_INFO"])) ? trim($_SERVER["PATH_INFO"], '/') : 'GB';
$TODAY = date("Y-m-d");
$IMPORT_FOLDER = $COUNTRY_CODE . '/';

echo "Downloading latest version [.....]\n";
echo "Building SQL file [";
$writeSQL = fopen($IMPORT_FOLDER . $TODAY . '.sql', 'w');
$headerSQL = <<<EOF
USE `ramp_db`;

-- -----------------------------------------------------
-- Table `ramp_db`.`GB_Addresses`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ramp_db`.`GB_Addresses` ;

CREATE TABLE IF NOT EXISTS `ramp_db`.`GB_Addresses` (
  `postalCode` VARCHAR(8) NOT NULL,
  `PostTown` VARCHAR(30) NOT NULL,
  `dependentLocality` VARCHAR(35) NULL DEFAULT NULL,
  `doubleDependentLocality` VARCHAR(35) NULL DEFAULT NULL,
  `thoroughfare` VARCHAR(80) NULL DEFAULT NULL,
  `dependentThoroughfare` VARCHAR(80) NULL DEFAULT NULL,
  `buildingNumber` SMALLINT NULL DEFAULT NULL,
  `buildingName` VARCHAR(50) NULL DEFAULT NULL,
  `subBuildingName` VARCHAR(30) NULL DEFAULT NULL,
  `PoBoxNumber` SMALLINT NULL DEFAULT NULL,
  `departmentName` VARCHAR(60) NULL DEFAULT NULL,
  `organisationName` VARCHAR(60) NULL DEFAULT NULL,
  `id` VARCHAR(15) NOT NULL,
  `postcodeType` VARCHAR(1) NOT NULL,
  `SmallUserOrganisationFLAG` TINYINT NOT NULL DEFAULT 0,
  `deliveryPointSuffix` VARCHAR(2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

INSERT INTO GB_Addresses (`postalCode`,`PostTown`,`dependentLocality`,`doubleDependentLocality`,`thoroughfare`,`dependentThoroughfare`,`buildingNumber`,`buildingName`,`subBuildingName`,`PoBoxNumber`,`departmentName`,`organisationName`,`id`,`postcodeType`,`SmallUserOrganisationFLAG`,`deliveryPointSuffix`)
  VALUES
EOF;
fwrite($writeSQL, $headerSQL);
$readCSV = fopen($IMPORT_FOLDER . $TODAY . '.csv', 'r');
$c=0; $lock=FALSE;
while (! feof($readCSV)) {
  $rowSQL = ($c != 0)? "," : "";  
  $a = explode(',', fgets($readCSV));
  if (count($a) < 2) { break; }
  $i=0; $c++;
  $rowSQL .= "\n('" . $a[$i] . "'";
  while ($i < 15) {
    $i++;
    if ($i == 6 || $i == 9) { // INT
      $rowSQL .=  ($a[$i] == '' || $a[$i] == ' ')? ",NULL" : "," . $a[$i];
      continue;
    }
    if ($i == 14) { // BOOLEAN INT
      $rowSQL .= ($a[$i] == 'Y')? ",1" : ",0";
      continue;
    }
    $rowSQL .= ($a[$i] == '' || $a[$i] == ' ') ? ",NULL" : 
      ",'" . str_replace("'", "''", $a[$i]) . "'";
  }
  fwrite($writeSQL, $rowSQL. ")");
  if ($c % 1000 == 0) { echo '.'; }
}
fclose($readCSV);
fwrite($writeSQL, ";\n");
fclose($writeSQL);
echo "]\n";
echo "Updating database...\n Please wait..\n";
echo shell_exec('sudo mysql < ' . $IMPORT_FOLDER . $TODAY . '.sql 2>&1');
echo "...Done\n";