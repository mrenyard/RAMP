<?php
/**
 * MEDIA.server - 
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
 * @link https://csswizardry.com/2023/09/the-ultimate-lqip-lcp-technique/
 * 
 * @author Matt Renyard (renyard.m@gmail.com)
 * @package MEDIA
 * @version 0.0.9;
 */
?>
<ol>
<?php
set_time_limit(120);
$files = scandir('../');
foreach ($files as $file) {
  if ($file == '.' || $file == '../' || $file == 'low' || $file == 'index.php' || is_dir($file)) { continue; }
  $source = str_replace('low', '', __DIR__) . $file;
  $destination = './' . $file;
  $srcimage = imagecreatefrompng($source);
  list($width, $height) = getimagesize($source);
  $sourceWeight = filesize($source);
  // https://csswizardry.com/2023/09/the-ultimate-lqip-lcp-technique/
  $target = (($width * $height) * 0.055);
  $palette = 2;
  $img; $weight;
  $img = imagecreatetruecolor($width, $height);
  imagecopy($img, $srcimage, 0, 0, 0, 0, $width, $height);
  imagetruecolortopalette($img, FALSE, 8);
  imageresolution($img, 6);
  imagepng($img, $destination);
?>
  <li><?=$file; ?></li>
<?php
  imagedestroy($img);
} ?>
</ol>
