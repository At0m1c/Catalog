<?php

use Amel\Main\Basket;
use Bitrix\Main\Context;
use Bitrix\Sale\Basket as SaleBasket;
use Bitrix\Sale\Fuser;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Main\Data\Cache;
use Bitrix\Main\Application;

class HeaderCart extends CBitrixComponent
{
    public function executeComponent()
    {
        $cache = Cache::createInstance();
        $taggedCache = Application::getInstance()->getTaggedCache();

        $cachePath = 'basket_count';

        if ($cache->initCache(CACHE_TIME, Fuser::getId(), $cachePath)) {
            $vars = $cache->getVars();
        } elseif ($cache->startDataCache()) {
            $taggedCache->startTagCache($cachePath);
            $vars = $this->getCount();
            $taggedCache->registerTag('basket_count_' . Fuser::getId());
            $taggedCache->endTagCache();
            $cache->endDataCache($vars);
        }

        $this->arResult['COUNT'] = $vars;

        $this->includeComponentTemplate();
    }

    private function getCount(): int
    {
        $basket = SaleBasket::loadItemsForFUser(
            Fuser::getId(),
            Context::getCurrent()->getSite()
        );

        return $basket->count();
    }
}