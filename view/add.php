	<script src="<?=VIEW."js/add.js"?>"></script>
	<form>
    <div class="center"> 
        <div class="add_new">
            <p>Введите фамилию: </p>
            <p>Введите имя: </p>
            <p>Введите отчество: </p>
            <?=$vivod_left?>
            <p>Введите номер телефона: </p>
            <p>Введите e-mail: </p>
            <p>Дата заполнения анкеты: <br />
            <span><input type="button" id="save" title="Запонить дату" alt="Запомнить дату" value="Запомнить"/>
            <input title="Удалить дату" alt="Удалить дату" type="button" id="delete" value="Удалить"/></span>
            </p>
        </div>
        <div class="add_new">
            <p><input type="text" id="fam" name="fam"/></p>
            <p><input type="text" id="name" name="name"/></p>
            <p><input type="text" id="otch" name="otch"/></p>
            <?=$vivod_right?>
            <p><input type='text' id="phone" name="phone"/></p>
            <p><input type="text" id="mail" name="mail"/></p>
            <p><input type="text" id="date" name="date" title="Дата в формате Год-месяц-число (<?=date("Y-m-d")?>)"/></p>
        </div>
        <input type="button" id="btn" value="Отправить" style="float: right; margin-right: 10px;"/>
    </div>
    </form>
    <p id="p"><?php if (isset($_SESSION['count'])) echo "Добавленно за эту сессию:". $_SESSION['count'];?></p>
    <div id="div" class="left_div"><?=$_SESSION['added']?></div>
    <fieldset class="right_div">
    <legend>Настройки</legend>
    <div class="checkbox"><input class="checkbox" type="checkbox" id="check_phone" <?php if ($us->get_conf_reg()) echo "checked=\"checked\""?>/><label for="check_phone">Подтверждать номер телефона?</label></div>
        <p>Текст для подтверждения:</p>
    <textarea name="text" id="text" style="margin: 0px; width: 332px; height: 172px;"><?php if ($us->get_conf_msg()) echo $us->get_conf_msg() ?></textarea>
    <input type="button" id="click" value="Изменить текст" style="float: right;"/>
    </fieldset>