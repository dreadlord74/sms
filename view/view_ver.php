<table style="border: 1px solid black;">
<tr>
    <td>ФИО</td>
    <td>Номер телефона</td>
    <td>e-mail</td>
    <td>Дата внесения в базу</td>
</tr>
<?php foreach($res as $item):?>
<tr>
    <td><?=$item['fam']." ".$item['name']." ".$item['otch']?></td>
    <td><?=$item['phone']?></td>
    <td><?=$item['mail']?></td>
    <td><?=$item['date']?></td>
</tr>
<?php endforeach?>
</table>