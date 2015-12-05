<script type="application/javascript">
    function redirect(){
        document.location.href = "<?=PATH?>?view=sended&do=get_ras";//доделать
    }
</script>
<script src="<?=VIEW?>js/sendmass.js"></script>
<script src="<?=VIEW?>js/device_status.js"></script>

<fieldset style="width: 695px; height: auto;">
<legend>Устройства</legend>
<ul class="hr" id="devices">
<?php foreach ($res['data'] as $device):?>
<li id="lol" class="<?php if ($device['power'] == 0) echo "not_work"; else echo "work";?>" title="<?php if ($device['power'] == 1) echo "Сервис запущен!"; else echo "Сервис не запущен!";?>">
<?=$device['manufacturer']." ".$device['device_name']?>
</li>
<?php endforeach?>
</ul>
</fieldset>

<p>Тема рассылки: <input type="text" id="tema"/></p>
<p>Текст сообщения:</p>
<textarea id="text" rows="10" cols="100"></textarea><br />
<input type="button" id="btn" value="Отправить"/>