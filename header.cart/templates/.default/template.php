<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var TYPE_NAME $arResult */
?>
<a href="/order/" class="header-shop__item">
    <svg width="24" height="24">
        <use xlink:href="#icon-basket"></use>
    </svg>
    <span class="header-shop__count"><?= $arResult['COUNT'] ?></span>
</a>