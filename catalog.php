<?php

namespace Amel\Main;

use Bitrix\Iblock\Iblock;

class Catalog
{
    /**
     * Ручная сортировка свойств товаров для фильтра
     */
    public static function getSortProperties(string $code): ?array
    {
        $result = [];

        $entity = Iblock::wakeUp(PROPERTY_SORT_IBLOCK_ID)->getEntityDataClass();

        $element = $entity::getList([
            'select' => [
                'ID',
                'CODE',
                'CODE_PROP',
            ],
            'filter' => [
                'ACTIVE' => 'Y',
                'CODE'   => $code,
            ]
        ])->fetchObject();

        if (!empty($element)) {
            foreach ($element->getCodeProp()->getAll() as $item) {
                $result[$item->getValue()] = $item->getDescription();
            }
        }

        return $result;
    }

    public static function getProperties(int $sectionId, string $type): ?array
    {
        $result = [];
        $arSectionNavChain = [];

        $resSectChain = \CIBlockSection::GetNavChain(CATALOG_IBLOCK_ID, $sectionId);
        while ($arResSectChain = $resSectChain->Fetch()) {
            $arSectionNavChain[] = $arResSectChain['ID'];
        }

        if ($arSectionNavChain) {
            $arSelect = ['ID'];

            $propType = match ($type) {
                'list' => 'PROPERTY_LIST',
                'detail' => 'PROPERTY_DETAIL',
            };

            $arSelect[] = $propType;

            $resPropElement = \CIBlockElement::GetList(
                [],
                [
                    'IBLOCK_ID'           => CATALOG_PROPERTY_IBLOCK_ID,
                    'PROPERTY_SECTION_ID' => $arSectionNavChain[0],
                ],
                false,
                false,
                $arSelect
            );

            while ($arResPropElement = $resPropElement->Fetch()) {
                $result[] = $arResPropElement['PROPERTY_' . mb_strtoupper($type) . '_VALUE'];
            }
        }

        return $result;
    }
}