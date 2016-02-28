<div class="wrapper" style="display: table">
    <ul class="top-menu">
        <li><a href="?view=add">Добавить контакт в рассылку</a></li>
        <li><a href="?view=send_mass">Массовая рассылка</a></li>

        <li><a href="?view=sended">Просмотр статусов рассылок</a></li>
        <?php if (($us->get_prava() == 1) or ($us->get_prava() == 3)):?>
    <li><a href="/cancel">Отменить отправку всех</a></li>

        <li><a href="?view=add_mass">Массовая загрузка</a></li>
    <li><a href="?view=settings">Изменение данных</a></li>
       <?php endif ?>
        <li><a href="?view=view_ver">Показать список рассылки</a></li>
        <li><a href="?view=search">Поиск</a></li>
        <li><a href="?view=exit">Выход</a></li>
    </ul>
</div>