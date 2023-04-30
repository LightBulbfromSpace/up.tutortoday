<?php
/**
 * @var array $arResult
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<div class="container-subjects box hidden-custom" id="subject-form">
    <div class="container-subjects">
        <div class="control">
            <div class="select-custom">
                <select name="subjectSelect">
                    <?php foreach ($arResult['subjects'] as $subject): ?>
                        <option value="<?=$subject['ID']?>"><?=$subject['NAME']?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
            <div class="container-row-custom is-aligned-center max-width-90">
                <div class="box-dark-element-custom">
                <input type="number" class="input-custom" placeholder="Price" name="add-subject-price-<?=$subject['SUBJECT']['ID']?>">
                <div class="price">rub/hour</div>
                </div>
            </div>
    </div>
    <button type="button" class="button-plus-minus button-large-custom max-width-90" onclick="closeSubjectForm()">-</button>
</div>