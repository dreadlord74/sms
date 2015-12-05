<table style="border: 1px solid black;">
<tr>
    <td>ФИО</td>
    <td>Номер телефона</td>
    <td>e-mail</td>
    <td>Дата зополнения анкеты</td>
    <td>Дата верификации телефона</td>
</tr>
<?php foreach($res as $item):?>
<tr>
    <td><?=$item['fam']." ".$item['name']." ".$item['otch']?></td>
    <td><?=$item['phone']?></td>
    <td><?=$item['mail']?></td>
    <td><?=$item['date']?></td>
    <td><?=$item['date_ver']?></td>
</tr>
<?php endforeach?>
</table>