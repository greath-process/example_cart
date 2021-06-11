<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->addExternalCss('/bitrix/css/main/bootstrap.css');?>
<div id="table">
<?if ($_POST) $APPLICATION->RestartBuffer();?>
<?if(!empty($arResult['ITEMS'])):?>
<table class="table">
  <thead>
    <tr>
      <th scope="col"> </th>
      <th scope="col">Наименование</th>
      <th scope="col">Характеристики</th>
      <th scope="col">Стоимость</th>
      <th scope="col">Количество</th>
      <th scope="col">Сумма</th>
      <th scope="col"> </th>
    </tr>
  </thead>
  <tbody>
  <?foreach($arResult['ITEMS'] as $item):?>
    <tr id="<?=$item['ID']?>">
      <th scope="row"><a href="<?=$item['DETAIL_PAGE_URL']?>"><?if(!empty($item['PICTURE_SRC'])):?><img width="100" height="100" src="<?=$item['PICTURE_SRC']?>"><?endif?></a></th>
      <td><a href="<?=$item['DETAIL_PAGE_URL']?>"><?=$item['NAME']?></a></td>
      <td><?foreach($item['PROPERTIES'] as $prop_name => $prop_value) echo $prop_name.': '.$prop_value.'; <br>'?></td>
      <td><?=number_format($item['PRICE'], 0, ',', ' ' );?><?if($item['PRICE'] != $item['BASE_PRICE']):?><br><strike><?=number_format($item['BASE_PRICE'], 0, ',', ' ' );?></strike><?endif?></td>
      <td><input type="number" min="1" value="<?=$item['QUANTITY']?>"></td>
      <td><?=number_format($item['SUM'], 0, ',', ' ' );?></td>
      <td><a title="удалить" class="delete" href="javascript:void(0);">x</a></td>
    </tr>
    <?endforeach?>
  </tbody>
</table>
<h4>Итого:</h4>
<table class="table">
  <thead>
    <tr>
      <th scope="col">Количество товаров в корзине</th>
      <th scope="col">Сумма</th>
    </tr>
  </thead>
  <tbody>
    <tr id="total">
      <td><?=$arResult['QUANTITY']?></td>
      <td><?=number_format($arResult['SUM'], 0, ',', ' ' );?></td>
    </tr>
  </tbody>
</table>
<?else:?>
<h2>Корзина пуста</h2>
<?endif?>
<?if ($_POST) die();?>
</div>