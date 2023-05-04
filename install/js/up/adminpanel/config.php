<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

return [
	'css' => 'dist/adminPanel.bundle.css',
	'js' => 'dist/adminPanel.bundle.js',
	'rel' => [
		'main.core',
	],
	'skip_core' => false,
];