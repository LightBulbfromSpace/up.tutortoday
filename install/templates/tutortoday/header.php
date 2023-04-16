<?php
/**
 * @var CMain $APPLICATION
 */

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
?>

<!doctype html>
<html lang="<?=LANGUAGE_ID?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php $APPLICATION->ShowTitle(); ?></title>
    <?php $APPLICATION->ShowHead(); ?>
</head>
<body>
<?php $APPLICATION->ShowPanel(); ?>

<div id="overlay"></div>
<section class="section">
    <div class="container">
        <nav class="navbar-custom" role="navigation" aria-label="main navigation">
            <div class="navbar-brand-custom">
                <a class="navbar-item has-text-weight-semibold is-size-5" href="/">
                    📝 <?=SITE_NAME?>
                </a>
            </div>

            <div class="navbar-item">
                <div class="buttons">
                    <a class="button is-link" href="/tasks/create/">BLABLA</a>
                </div>
            </div>
        </nav>
    </div>
</section>
