<?php if(isset($res)):?>

<form action="<?=PATH?>?view=sended" method="get" class="sended_center">
    <?php foreach($res as $value):?>
        <input class="checkbox" type="checkbox" name="<?=$value['id']?>" id="<?=$value['id']?>"> <label for="<?=$value['id']?>"><?=$value['tema']?></label><br />
    <?php endforeach?>
        <input type="hidden" name="view" value="sended">
        <input type="hidden" name="do" value="get_ras">
        <input type="submit" name="sub" value="Просмотреть" style="float: right">
</form>

<?php endif?>

<?php if(isset($result)):?>
    <?php
        $info = array();
    ?>
    <?php foreach($result as $key => $value):?>

        <?php $info['ids'][] = $value[$key]['id_rassilki']; $info['names'][] = $value[$key]['tema'];?>
<div id="main-<?=$value[$key]['id_rassilki']?>" class="main">
        <div style="float: left;">

        <table class="tab_<?=$value[$key]['id_rassilki']?>" id="tab">
            <tr>
                <td>ФИО</td>
                <td>Номер телефона</td>
                <td>Статус</td>
            </tr>
        <?php foreach($value as $item):?>
                <tr>
                    <td><?=$item['fam']." ".$item['name']." ".$item['otch']?></td>
                    <td><?=$item['phone']?></td>
                    <?php
                        if ($item['delivered'] == '1'){
                            $status = "Доставлено";
                        }elseif($item['is_error'] == '1'){
                            $status = "Ошибка";
                        }else{
                            $status = "Отправлено";
                        }
                    ?>
                    <td><?=$status?></td>
                </tr>
        <?php endforeach?>
        </table>
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
        <li><a class="current" href="#main-<?=$info['ids'][0]?>"><?=$info['names'][0]?></a></li>
    <?php foreach($info['names'] as $key => $name):?>
        <?php if ($key == 0) continue?>
        <li><a class href="#main-<?=$info['ids'][$key]?>"><?=$name?></a></li>

    <?php endforeach?>
    </ul>
    <script type="application/javascript">var ids = new Array(<?=implode(', ', $info['ids'])?>, 0);</script>
    <script src="<?=VIEW?>js/sended.js" type="text/javascript"></script>
    <script type="application/javascript">
                $(document).ready(function() {
                    $("a").click(function () {
                        $(".current").removeClass("current");
                        $(this).addClass("current");
                        var elementClick = $(this).attr("href");
                        var destination = $(elementClick).offset().top;
                        $("html:not(:animated),body:not(:animated)").animate({scrollTop: destination}, 800);
                    });
                });
    </script>
<?php endif?>

