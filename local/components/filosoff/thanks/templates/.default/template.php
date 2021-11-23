<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
\Bitrix\Main\UI\Extension::load('filosoff.thanks');
$this->SetViewTarget('inside_pagetitle');
$APPLICATION->IncludeComponent('bitrix:main.ui.filter', '.default', $arResult['FILTER']);
?>
<div id="conroller-button" class="pt-5"></div>
<?php
$this->EndViewTarget();
$APPLICATION->IncludeComponent('bitrix:main.ui.grid', '.default', $arResult['GRID']);
?>
<div id="container"></div>
