<?php
/**
 * @var array $arResult
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>

<div class="container">
    <div class="field">
        <label class="label">Name</label>
        <div class="control">
            <input class="input" type="text" placeholder="Text input">
        </div>
    </div>
    <div class="field">
        <label class="label">Surname</label>
        <div class="control">
            <input class="input" type="text" placeholder="Text input">
        </div>
    </div>
    <div class="field">
        <label class="label">Middle Name</label>
        <div class="control">
            <input class="input" type="text" placeholder="Text input">
        </div>
    </div>

    <div class="field">
        <label class="label">Login</label>
        <div class="control">
            <input class="input" type="text" placeholder="Enter login">
        </div>
        <p class="help">Login can contain characters, numbers and underscore</p>
    </div>

    <div class="field">
        <label class="label">Password</label>
        <div class="control">
            <input class="input" type="password" placeholder="Enter password">
        </div>
    </div>

    <div class="field">
        <label class="label">Email</label>
        <div class="control">
            <input class="input" type="email" placeholder="Email input">
        </div>
    </div>

    <div class="field">
        <label class="label">Phone</label>
        <div class="control">
            <input class="input" type="number" placeholder="Phone input">
        </div>
    </div>

    <div class="field">
        <label class="label">City</label>
        <div class="control">
            <input class="input" type="number" placeholder="City input">
        </div>
    </div>

    <div class="field">
        <label class="label">Education format</label>
        <div class="control">
            <div class="select">
                <select>
                    <option>Online</option>
                    <option>Offline</option>
                </select>
            </div>
        </div>
    </div>

    <div class="field">
        <label class="label">Subject</label>
        <div class="control">
            <div class="select">
                <select>
                    <option selected></option>
                    <option>Mathematics</option>
                    <option>Russian language</option>
                    <option>English language</option>
                    <option>Literature</option>
                    <option>Physics</option>
                </select>
            </div>
        </div>
    </div>

    <div class="field is-grouped is-justify-content-center">
        <div class="control">
            <button class="button is-link">Register me</button>
        </div>
    </div>
</div>