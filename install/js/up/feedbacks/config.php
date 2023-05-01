<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

return [
	'css' => 'dist/feedbacks.bundle.css',
	'js' => 'dist/feedbacks.bundle.js',
	'rel' => [
		'main.core',
	],
	'skip_core' => false,
];