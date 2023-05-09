<?php
/**
 * @var CMain $APPLICATION
 */

use Bitrix\Main\Localization\Loc;
use Up\Tutortoday\Controller\AdminController;

global $USER;

$linkToProfile = $USER->GetID() != null ? "/profile/{$USER->GetID()}/" : "/login/";

$isAdmin = (new AdminController((int)$USER->GetID()))->isAdmin();

\Bitrix\Main\UI\Extension::load('main.core');

Loc::loadMessages(__FILE__);
?>

<!doctype html>
<html lang="<?=LANGUAGE_ID?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="icon" type="image/x-icon" href="/local/templates/tutortoday/images/favicon.png">
    <title><?php $APPLICATION->ShowTitle(); ?></title>
	<?php $APPLICATION->ShowHead(); ?>
</head>
<body>
<?php $APPLICATION->ShowPanel(); ?>

<div id="overlay"></div>
<nav class="navbar navbar-expand-lg navbar-light header__other">
    <a class="navbar-brand" href="/overview/" id="main-logo">TutorToday</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
            <?php if ($isAdmin): ?>
                <li class="nav-item">
                    <a class="nav-link" id="adminPanel" href="/admin/">Admin Panel</a>
                </li>
            <?php endif; ?>
            <li class="nav-item">
                <a class="nav-link" id="myProfileButton" href="<?=$linkToProfile?>">My Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/about/">About</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Contacts</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="registrationButton" href="/registration/">Registration</a>
            </li>
        </ul>
    </div>
</nav>
<!-- Подключение Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<div class="main-container-header">