<ul class="hr">
    <li><a href="?view=add">Добавить нового</a></li>
    <li><a href="?view=send_mass">Массовая рассылка</a></li>
    <li><a href="/cancel">Отменить отправку всех</a></li>
    <li><a href="?view=view_ver">Показать верифицированых</a></li>
    <li><a href="?view=add_mass">Массовая загрузка</a></li>
    <li><a href="?view=sended">Просмотр статусов рассылок</a></li>
<?php
    if (($us->get_prava() == 1) or ($us->get_prava() == 3))
        echo<<<HTML
    <li><a href="?view=settings">Изменение данных</a></li>
HTML;
?>
    
    <li><a href="?view=search">Поиск</a></li>
    <li><a href="?view=exit">Выход</a></li>
</ul>