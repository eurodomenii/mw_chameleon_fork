<?php
$wgDefaultSkin = "chameleon";

# Enabled skins.
wfLoadExtension( 'Bootstrap' );
wfLoadSkin( 'chameleon' );
wfLoadSkin( 'chameleon1' );
wfLoadSkin( 'chameleon2' );


$egChameleonLayoutFile = __DIR__ . '/skins/chameleon/layouts/standard.xml';
$egChameleon1LayoutFile = __DIR__ . '/skins/chameleon/layouts/standard.xml';
$egChameleon2LayoutFile = __DIR__ . '/skins/chameleon/layouts/standard.xml';

  #echo "logged in";
  $egChameleon1ExternalStyleVariables = [
    '$cmln-toc-title-font-size' => '1.25rem',
    '$h1-font-size' => '2.1rem',
    '$h2-font-size' => '2.0rem',
    '$h3-font-size' => '2.0rem',
    '$h4-font-size' => '2.0rem',
    '$enable-responsive-font-sizes' => 'false',
    '$enable-rfs' => 'false',
    '$font-family-base' => 'sans-serif',
    '$font-family-sans-serif' => 'sans-serif',
    '$font-family-monospace' => '',
  ];

  $egChameleon1ExternalStyleModules = [
  __DIR__ . '/resources/scss/eurodomenii.scss',
  __DIR__ . '/resources/scss/eurodomenii_admin.scss',
  ];
  
  $egChameleon2ExternalStyleVariables = [
    '$cmln-toc-title-font-size' => '1.25rem',
    '$h1-font-size' => '1.15rem',
    '$h2-font-size' => '1.15rem',
    '$h3-font-size' => '1.15rem',
    '$h4-font-size' => '1.15rem',
    '$enable-responsive-font-sizes' => 'false',
    '$enable-rfs' => 'false',
    '$font-family-base' => 'sans-serif',
    '$font-family-sans-serif' => 'sans-serif',
    '$font-family-monospace' => '',
];

$egChameleon2ExternalStyleModules = [
  __DIR__ . '/resources/scss/eurodomenii.scss',
  __DIR__ . '/resources/scss/eurodomenii_eagle.scss',
];
  #echo 'anon';
  $egChameleonExternalStyleVariables = [
          '$h1-font-size' => '1.4rem',
          '$h2-font-size' => '1.3rem',
          '$h3-font-size' => '1.3rem',
          '$font-size-base' => '1.15rem',
	  '$font-size-sm' => '1.15rem',
	  '$small-font-size' => '1rem',
          '$enable-responsive-font-sizes' => 'false',
          '$enable-rfs' => 'false',
          '$font-family-base' => 'sans-serif',
          '$font-family-sans-serif' => 'sans-serif',
          '$font-family-monospace' => '',
  ];

  $egChameleonExternalStyleModules = [
        __DIR__ . '/resources/scss/eurodomenii.scss',
        __DIR__ . '/resources/scss/eurodomenii_anon.scss',
  ];

