<script type="application/javascript">
    function redirect(){
        document.location.href = "<?=PATH?>?view=sended&do=get_ras";//доделать
    }
</script>
<script src="<?=VIEW?>js/sendmass.js"></script>
<script src="<?=VIEW?>js/device_status.js"></script>
<div class="wrapper" style="width: 600px;">
   <!-- <fieldset style="    padding: 5px 0;
    display: table;
    padding-left: 10px;">
        <legend>Устройства</legend>
        <ul style="display: table-row" id="devices">
            <?php foreach ($res['data'] as $device):?>
                <li id="lol" class="li <?php if ($device['power'] == 0) echo "not_work"; else echo "work";?>" title="<?php if ($device['power'] == 1) echo "Сервис запущен!"; else echo "Сервис не запущен!";?>">
                    <?=$device['manufacturer']." ".$device['device_name']?>
                </li>
            <?php endforeach?>
        </ul>
    </fieldset>-->
    <p style="margin-top: 20px;">Тема рассылки: <input type="text" id="tema"/></p>
    <p>Текст сообщения:</p>
    <textarea id="text" rows="10" cols="80"></textarea><br />
    <input type="button" id="btn" value="Отправить"/>
</div>
