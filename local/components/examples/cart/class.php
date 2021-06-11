<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Sale,
    Bitrix\Main\Loader,
    Bitrix\Main\Application;

class ExampleCart extends CBitrixComponent
{

    private function checkModules()
    {
        if (!Loader::includeModule('sale') && !Loader::includeModule('iblock')) {
            throw new \Exception('Не загружен модуль sale / iblock');
        }
        return true;
    }

    public function GetProps($id)
    {
        $cache_time = $this->arParams['CACHR_TIME'];
        $product = Sale\Internals\BasketTable::getById($id)->fetch();
        $id = $product['PRODUCT_ID'];
        $forbidden_codes = ['BLOG_POST_ID', 'MORE_PHOTO'];
        $dbItem = \Bitrix\Iblock\ElementTable::getList(array(
            'select' => ['*', 'DETAIL_PAGE_URL' => 'IBLOCK.DETAIL_PAGE_URL'],
            'filter' => ['ID' => $id],
        ));
        if($arItem = $dbItem->fetch())  
        {
            $dbProperty = \CIBlockElement::getProperty($arItem['IBLOCK_ID'], $arItem['ID'], array("sort", "asc"), array());
            $props['DETAIL_PAGE_URL'] = CIBlock::ReplaceDetailUrl($arItem['DETAIL_PAGE_URL'], $arItem, false, 'E');
            $pic_id = (!empty($arItem['PREVIEW_PICTURE'])) ? $arItem['PREVIEW_PICTURE'] : $arItem['DETAIL_PICTURE'];
            $props['PREVIEW_PICTURE'] = CFile::GetPath($pic_id);
            while ($arProperty = $dbProperty->GetNext()) 
            {
                if (!empty($arProperty['VALUE']) && !in_array($arProperty['CODE'], $forbidden_codes)) 
                {
                    $props[ $arProperty['NAME'] ] = $arProperty['VALUE'];
                } 
            }
        }
        return $props;
    }
    // изменение количества
    public function NewQuantity($id, $quantity)
    {
        if(!empty($id) && !empty($quantity) && is_numeric($id) && is_numeric($quantity))
        {
            if($id > 0 && $quantity > 0){
                $data = [
                    'QUANTITY' => $quantity
                ];
                $update = Sale\Internals\BasketTable::update($id, $data);
            }
        }
        return $update;
    }
    // удаление товаров
    public function Delete($id)
    {
        if(!empty($id) && is_numeric($id) && $id > 0)
        {
            $delete = Sale\Internals\BasketTable::delete($id);
        }
        return $delete;
    }

    public function CheckChanges()
    {
        $request = Application::getInstance()->getContext()->getRequest();
        $type = $request->get("type");
        if($type == 'delete')
        {
            self::Delete($request->get("id"));
        } 
        if($type == 'quantity') 
        {
            self::NewQuantity($request->get("id"), $request->get("quantity"));
        }
        return true;
    }

    public function GetCart()
    {
        $items = [];
        $basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());
        $fullPrice = $basket->getBasePrice();
        $productCount = count($basket->getQuantityList());
        $basketItems = $basket->getBasketItems();
        foreach ($basket as $basketItem) 
        {
            $props = $this->GetProps($basketItem->getId());
            $preview_pic = $props['PREVIEW_PICTURE'];
            $url = $props['DETAIL_PAGE_URL'];
            unset($props['PREVIEW_PICTURE'], $props['DETAIL_PAGE_URL']);
            //$item->getDefaultPrice(); // цена по умолчанию
            //$item->getDiscountPrice();
            $items[] = [
                'NAME' => $basketItem->getField('NAME'), 
                'QUANTITY' => $basketItem->getQuantity(),
                'ID' => $basketItem->getId(),
                'BASE_PRICE' => $basketItem->getBasePrice(),
                'PRICE' => $basketItem->getPrice(), // getPrice
                'SUM' => $basketItem->getFinalPrice(),
                'PICTURE_SRC' => $preview_pic,
                'DETAIL_PAGE_URL' => $url,
                'PROPERTIES' => $props,
            ];
        }
        
        $result = array(
            "SUM" => $fullPrice,
            "QUANTITY" => $productCount,
            "ITEMS" => $items,
        );
        return $result;
    }

    //Родительский метод проходит по всем параметрам переданным в $APPLICATION->IncludeComponent
    //и применяет к ним функцию htmlspecialcharsex. В данном случае такая обработка избыточна.
    //Переопределяем.
    public function onPrepareComponentParams($arParams)
    {
        $result = array(
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => isset($arParams["CACHE_TIME"]) ?$arParams["CACHE_TIME"]: 36000000,
        );
        return $result;
        /*
        global $USER;
        $arParams['USER_ID'] = $USER->GetID();
        return $arParams;
        */
    }

    // этот блок - перекрыие метода executeComponent и отказ от component.php
    public function executeComponent()
    {
        if($this->startResultCache()) //startResultCache используется не для кеширования html, а для кеширования arResult
        {
            // проверки
            $this->checkModules();
            $this->CheckChanges();
            // все функции перед выводом
            $this->arResult = $this->GetCart();
            // принятие шаблона
            $this->includeComponentTemplate();
        }
        return $this->arResult;
    }
}?>