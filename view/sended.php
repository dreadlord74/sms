<?php if(isset($res)):?>

<form action="<?=PATH?>?view=sended" method="get" class="sended_center">

    <?php foreach($res as $value):?>
        <input class="checkbox" type="radio" name="on" value="<?=$value['id']?>" id="<?=$value['id']?>"> <label for="<?=$value['id']?>"><?=$value['tema']?></label><br />
    <?php endforeach?>
        <input type="hidden" name="view" value="sended">
        <input type="hidden" name="do" value="get_ras">
        <input type="submit" name="sub" value="Просмотреть" style="float: right">
</form>

<?php endif?>

<?php if(isset($result)):?>
    <?php if ($wait['COUNT(sended_to_phone)'] > 0):?>
    <script type="text/javascript">
        $(document).ready(function(){
            alert("Из-за большой очереди отправляемых смс ваша расслка нанётся через <?=(int)($wait['COUNT(sended_to_phone)']/4)?> минут");
        });
    </script>
    <?php endif?>
    <?php
        $info = array();

    ?>
    <?php foreach($result as $key => $value):?>

        <?php $info['ids'][] = $value[$key]['id_rassilki']; $info['names'][] = $value[$key]['tema']; $i = 0;?>
<div id="main-<?=$value[$key]['id_rassilki']?>" class="main">
        <div style="float: left; position:relative;">
        <script type="text/javascript">
            jQuery(window).load(function() {
                $('.tab_<?=$value[$key]['id_rassilki']?>').MyPagination({height: 600, fadeSpeed: 400, id: <?=$value[$key]['id_rassilki']?>});
            });
        </script>

            <div class="tab_<?=$value[$key]['id_rassilki']?>" id="tab">
                <div class="row">
                    <span>ФИО</span>
                    <span>Номер телефона</span>
                    <span>Статус</span>
                </div>
        <?php foreach($value as $item):?>
                <?php $i++;
                    if ($i % 2 == 0){
                        echo "<div class='row'>";
                    }else{
                        echo "<div class='row' style='background: gainsboro; color: #878BB6;'>";
                    }
                ?>
                    <span><?=$item['fam']." ".$item['name']." ".$item['otch']?></span>
                    <span><?=($item['phone']) ? $item['phone'] : $item['phone1']?></span>
                    <?php
                        if ($item['delivered'] == '1'){
                            $status = "Доставлено";
                        }elseif($item['is_error'] == '1'){
                            $status = "Ошибка";
                        }else{
                            $status = "Отправлено";
                        }
                    ?>
                    <span><?=$status?></span>
                </div>
        <?php endforeach?>
            </div>
            <div class="pagination">

                <ul>

                    <li><a href="#" id="prev" class="prevnext">« Туда</a></li>

                    <li><a href="#" id="next" class="prevnext">Сюда »</a></li>

                </ul>

                <br />

                <div id="page_number" class="page_number"></div>

            </div>

        </div>
    <div class="stat_msg">
        <div class="msg" id="msg_<?=$value[$key]['id_rassilki']?>"></div>
        <p class="stat"><span class="sended_<?=$value[$key]['id_rassilki']?>" id="sended"></span><span class="deliv_<?=$value[$key]['id_rassilki']?>" id="deliv"></span>
        <span class="otprav_<?=$value[$key]['id_rassilki']?>" id="otprav"></span><span class="error_<?=$value[$key]['id_rassilki']?>" id="error"></span></p>
        <canvas style="position: relative; float: right;" id="countries_<?=$value[$key]['id_rassilki']?>" width="600" height="400"></canvas>
    </div>
</div>
    <?php endforeach?>

    <ul id="nav">
        <li><a class="current" id="a" href="#main-<?=$info['ids'][0]?>"><?=$info['names'][0]?></a></li>
    <?php foreach($info['names'] as $key => $name):?>
        <?php if ($key == 0) continue?>
        <li><a class href="#main-<?=$info['ids'][$key]?>"><?=$name?></a></li>

    <?php endforeach?>
    </ul>
    <script type="application/javascript">var ids = new Array(<?=implode(', ', $info['ids'])?>, 0);</script>
    <script src="<?=VIEW?>js/sended.js" type="text/javascript"></script>
    <script type="application/javascript">
                $(document).ready(function() {
                    $("#a").click(function () {
                        $(".current").removeClass("current");
                        $(this).addClass("current");
                        var elementClick = $(this).attr("href");
                        var destination = $(elementClick).offset().top;
                        $("html:not(:animated),body:not(:animated)").animate({scrollTop: destination}, 800);
                    });
                });
    </script>
<?php endif?>

