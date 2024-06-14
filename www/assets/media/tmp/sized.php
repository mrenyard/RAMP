<?php
/**
 * Content-Type image/svg+xml
 */
header('Content-Type: image/svg+xml');
$imgSize = explode('x',strtolower(trim($_SERVER["PATH_INFO"],'/')));
$imgWidth = $imgSize[0];
$imgHeight = $imgSize[1];
?>
<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="<?=$imgWidth; ?>" height="<?=$imgHeight; ?>">
  <rect stroke-dasharray="none" width="<?=$imgWidth; ?>" height="<?=$imgHeight; ?>" stroke="rgb(255 255 255 / .75)" y="0" x="0" stroke-width=".125rem" fill="transparent" />
  <path d="m 0 0 l <?=$imgWidth; ?> <?=$imgHeight; ?>" stroke="rgb(255 255 255 / .75)" stroke-width=".125rem" fill="none" />
  <path d="M <?=$imgWidth; ?> 0 L 0 <?=$imgHeight; ?>" stroke="rgb(255 255 255 / .75)" stroke-width=".125rem" fill="none" />
  <g font-size="12" font="serif" fill="#FFFFFF" stroke="none" text-anchor="middle">
    <text x="<?=$imgWidth/2; ?>" y="<?=$imgHeight-($imgHeight*0.03); ?>"><?=$imgWidth; ?>x<?=$imgHeight; ?></text>
  </g>
</svg>
