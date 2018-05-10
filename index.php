<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->AddHeadString('<link rel="stylesheet" href="/css/owl/owl.carousel.min.css">');
$APPLICATION->AddHeadString('<link rel="stylesheet" href="/css/owl/owl.theme.default.min.css">');?>
    <style>
        .news-item{
            display: block;
            min-height: 300px;
            margin: 5px;
            padding: 5px;
            border-color: #cccccc;
            border-style: solid;
            border-width: 1px;
            clear: both;
        }
        .previewContainer {
            margin-bottom: 5px;
            padding: 5px;
            float: left;
            width: 270px;
        }
        .previewImage {
            display: block;
            margin: 0px;
            padding: 0px;
            height: 259px;
        }
        .timetable{
            display: block;
            clear: both;
        }
        .divTable{
            display: block;
            width: 100%;
        }
        .divTableRow {
            display: table-row;
        }
        .divTableHeading {
            background-color: #EEE;
            display: table-header-group;
        }
        .divTableCell, .divTableHead {
            border: 1px solid #999999;
            display: table-cell;
            padding: 3px 10px;
        }
        .divTableCell ul{
            list-style: none;
            padding: 0;
        }
        .divTableHeading {
            background-color: #EEE;
            display: table-header-group;
            font-weight: bold;
        }
        .divTableFoot {
            background-color: #EEE;
            display: table-footer-group;
            font-weight: bold;
        }
        .divTableBody {
            display: table-row-group;
        }
    </style>
<?
//$api_key = 'OOegKvQnY16H_B9vByokFrN5co10S4hRY5t3HrBe';
//$api_secret = '7HzPDey7F3lLRdDVtY7BPp74uBlhJcaXMdMjZNzJMn13_SoPfNW-0g';
//sig = 'cde0e444ce0f973';
$ch = curl_init('https://api.jowi.club/v010/restaurants?api_key=OOegKvQnY16H_B9vByokFrN5co10S4hRY5t3HrBe&sig=cde0e444ce0f973');

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$response = curl_exec($ch);
curl_close($ch);
$result = json_decode($response,true);
    global $USER;
    if ($USER->IsAuthorized()){?>
        <div class="news-list">
            <?foreach ($result['restaurants'] as $item) { ?>
                <div class="news-item">
                <div class="previewContainer owl-carousel owl-theme">
                <?foreach ($item['images'] as $img) { ?>
                    <div class="previewImage item"><img src="<?= $img['url']; ?>" alt="<?= $img['description']; ?>" class="previewImage" style="width:259px"></div>
                <?}?>
                </div>
                    <div class="details">
                    <div class="title">
                        <a href="detail.php?ID=<?= $item['id']; ?>"><?= $item['title']; ?></a>
                    </div>
                    <?if(!$item['address']== ''){?>
                <p class="address">Адрес: <?=$item['address'];?></p>
                    <?}?>
                        <p>Сумма среднего счета: <?=$item['average_amount_bill'];?></p>
                        <p>Сумма доставки по умолчанию: <?=$item['delivery_price'];?></p>
                        <p>Среднее время доставки: <?=$item['delivery_time'];?></p>
                        <div class="timetable">
                            Режим работы:
                                <div class="divTable">
                                    <div class="divTableBody">
                                        <div class="divTableRow">
                                            <div class="divTableCell">Пн</div>
                                            <div class="divTableCell">Вт</div>
                                            <div class="divTableCell">Ср</div>
                                            <div class="divTableCell">Чт</div>
                                            <div class="divTableCell">Пт</div>
                                            <div class="divTableCell">Сб</div>
                                            <div class="divTableCell">Вс</div>
                                        </div>
                                        <div class="divTableRow">
                                <?
                                $week_days = array(null, 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
                                $work_modes = array('offline', 'online', 'bron');
                                $workdays = array();
                                foreach ($item as $restaurant) {
                                    foreach ($item['work_timetable'] as $wt) {
                                        $workdays[$week_days[$wt['day_code']]][$work_modes[$wt['timetable_code']]] = array(
                                            'open_time' => $wt['open_time'],
                                            'close_time' => $wt['close_time'],
                                            'day_off' => $wt['day_off']
                                        );
                                    }
                                }
                                foreach ($workdays as $workday){?>
                                    <div class="divTableCell">
                                        <ul>
                                            <li><strong>Офлайн режим:</strong>
                                                <ul>
                                                    <li>Время открытия: <?=$workday['offline']['open_time']?></li>
                                                    <li>Время закрытия: <?=$workday['offline']['close_time']?></li>
                                                    <li>Выходной день: <?echo $workday['offline']['day_off']==='true'?'Да':'Нет';?></li>
                                                </ul>
                                            </li>
                                            <li><strong>ON-LINE заказа:</strong>
                                                <ul>
                                                    <li>Время открытия: <?=$workday['online']['open_time']?></li>
                                                    <li>Время закрытия: <?=$workday['online']['close_time']?></li>
                                                    <li>Выходной день: <?echo $workday['online']['day_off']==='true'?'Да':'Нет';?></li>
                                                </ul>
                                            </li>
                                            <li><strong>ON-LINE бронирования:</strong>
                                                <ul>
                                                    <li>Время открытия: <?=$workday['bron']['open_time']?></li>
                                                    <li>Время закрытия: <?=$workday['bron']['close_time']?></li>
                                                    <li>Выходной день: <?echo $workday['bron']['day_off']==='true'?'Да':'Нет';?></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                <?}?>
                                        </div>
                                    </div>
                                </div>
                        </div>
                </div>
                </div>
            <?}?>
        </div>
        <?if (($result['error'] != 3 )||($result['http_code'] != 200)) {
            echo $result['message'];
        }
    }else{
        $APPLICATION->IncludeComponent(
            "bitrix:system.auth.form",
            "",
            Array(
                "REGISTER_URL" => "",
                "FORGOT_PASSWORD_URL" => "",
                "PROFILE_URL" => "",
                "SHOW_ERRORS" => "Y"
            ),
            false
        );
    }
?>
    <script src="/js/owl.carousel.js"></script>
    <script>
        $('.owl-carousel').owlCarousel({
            items:1,
            loop: true,
            lazyLoad:true,
            margin:10
        });
    </script>
<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');?>