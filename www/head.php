<?php
/**
 * Svelte - Rapid web application development using best practice.
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

$cssManifest = $_SERVER["DOCUMENT_ROOT"].'/assets/style/import/css.manifest';
if ((strpos($_SERVER["HTTP_HOST"], 'dev.') === 0) && file_exists($cssManifest)) {

  $STYLE_SERVER = '//'.$_SERVER["HTTP_HOST"].'/assets/style';
  $MEDIA_SERVER = '//'.$_SERVER["HTTP_HOST"].'/assets/media';
  $FUNC_SERVER = '//'.$_SERVER["HTTP_HOST"].'/assets/func';

  foreach (file($cssManifest) as $line) {
    $line = trim($line);
    if((strpos($line,';') !== 0) && ( $line !== '')) {

?>    <link rel="stylesheet" href="<?=$STYLE_SERVER; ?>/import/<?=$line; ?>.css">
<?php }} if (isset($_GET['scratch'])) { foreach (explode('|', $_GET['scratch']) as $cssScratchFile) { ?>
    <link rel="stylesheet" href="<?=$STYLE_SERVER; ?>/scratch/<?=$cssScratchFile; ?>.css">
<?php unset($_POST['scratch']); }} ?>
    <script src="<?=$FUNC_SERVER; ?>/libs/modernizr-2.0.6.js"></script>
    <!--[if lt IE 9]>
      <script src="<?=$FUNC_SERVER; ?>/libs/nwmatcher-1.2.3.js"></script>
      <script src="<?=$FUNC_SERVER; ?>/libs/selectivizr-1.0.2.js"></script>
    <![endif]-->
<?php } else {

  $STYLE_SERVER = '//style.'.$_SERVER["HTTP_HOST"];
  $MEDIA_SERVER = '//media.'.$_SERVER["HTTP_HOST"];
  $FUNC_SERVER = '//func.'.$_SERVER["HTTP_HOST"];

?>    <link rel="stylesheet" href="<?=$STYLE_SERVER; ?>/combined.20111116.css">
    <script src="<?=$FUNC_SERVER; ?>/libs/modernizr-2.0.6.min.js"></script>
    <!--[if lt IE 9]>
      <script src="<?=$FUNC_SERVER; ?>/libs/nwmatcher-1.2.3-min.js"></script>
      <script src="<?=$FUNC_SERVER; ?>/libs/selectivizr-1.0.2.min.js"></script>
    <![endif]-->
<?php } ?>
