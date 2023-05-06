<?php
/**
 * @var array $arResult
 */

global $USER, $APPLICATION;

use Up\Tutortoday\Controller\AdminController;

\Bitrix\Main\UI\Extension::load('main.core');

$isAdmin = (new AdminController((int)$USER->GetID()))->isAdmin();

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/local/templates/tutortoday/template_styles.css">
    <title><?php $APPLICATION->ShowTitle(); ?></title>
    <?php $APPLICATION->ShowHead(); ?>

</head>
<body>
<?php $APPLICATION->ShowPanel(); ?>
<header class="header">
    <div class="container header__container">
        <a class="header__mainLink header__links-item link" href="/overview/" id="main-logo">TutorToday</a>
        <div class="header__links" id="navbarNav">
            <?php if ($isAdmin): ?>
                <a class="header__links-item link" id="adminPanel" href="/admin/">Admin Panel</a>
            <?php endif; ?>
            <a class="header__links-item link" id="myProfileButton" href="<?=$arResult['linkToProfile']?>">My Profile</a>
            <a class="header__links-item link" href="/about/">About</a>
            <a class="header__links-item link" href="#">Contacts</a>
            <a class="header__links-item link" id="registrationButton" href="/registration/">Registration</a>
        </div>
    </div>
</header>