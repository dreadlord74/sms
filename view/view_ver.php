<script type="text/javascript">
    $(document).ready(function(){
        $(".row input").click(function(){
            var id = $(this).val();
            if ($(this).prop("checked"))
                var to = 1;
            else
                var to = 0;
            $.ajax({
                url: "./?view=view_ver&change",
                type: "POST",
                data: "id="+id+"&to="+to,
                success: function(data){
                    console.log(data);
                }
            });
        });
    });
</script>
<div class="wrapper">
    <div class="view-tab">
        <div class="row" style="border-bottom: 1px solid #000;">
            <span>ФИО</span>
            <span>Номер телефона</span>
            <span>e-mail</span>
            <span>Дата внесения в базу</span>
            <span>Управление</span>
        </div>
        <?php foreach($res as $item):?>
            <div class="row" id="<?=$item[id]?>">
                <span><?=$item['fam']." ".$item['name']." ".$item['otch']?></span>
                <span><?=$item['phone']?></span>
                <span><?=$item['mail']?></span>
                <span><?=$item['date']?></span>
                <span><!--<div>Изменить</div><div>Удалить</div>-->
                <label><input type="checkbox" value="<?=$item[id]?>" <?= ($item[phone_send]) ? "checked" : ""?> />Отправка</label></span>
            </div>
        <?php endforeach?>
    </div>
</div>
