<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$ID = $_REQUEST['ID'];
$ch = curl_init('https://api.jowi.club/v010/restaurants/'.$ID.'?api_key=OOegKvQnY16H_B9vByokFrN5co10S4hRY5t3HrBe&sig=cde0e444ce0f973');

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$response = curl_exec($ch);
curl_close($ch);
$result = json_decode($response,true);
foreach ($result['categories'] as $item) {?>
    <p><strong>Категория:</strong> <?=$item['title'];?></p>
    <div class="detail">
    <ul>
    <?
    foreach ($item['courses'] as $courses) {?>
        <li><?=$courses['title'];?>
        <li class="image_preview">
            <img src="<?=$courses['image_url'];?>" alt="" width="100px">
        </li>
        <ul>
            <li>Возможность онлайн заказа: <?echo $courses['online_order'] === 'true'?'Да':'Нет'; ?></li>
            <li>Цена для онлайн заказа: <?=$courses['price_for_online_order'];?></li>
            <li>Описание: <?=$courses['description'];?></li>
        </ul>
        </li>
    <?}?>
    </ul>
    </div>
<?}
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');