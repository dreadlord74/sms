	<script src="<?=VIEW."js/add.js"?>"></script>
	<!--<form>
        <div class="center">
            <div class="add_new">
                <p>Введите фамилию: </p>
                <p>Введите имя: </p>
                <p>Введите отчество: </p>
                <?=$vivod_left?>
                <p>Введите номер телефона: </p>
                <p>Введите e-mail: </p>
            </div>
            <div class="add_new">
                <p><input type="text" id="fam" name="fam"/></p>
                <p><input type="text" id="name" name="name"/></p>
                <p><input type="text" id="otch" name="otch"/></p>
                <?=$vivod_right?>
                <p><input type='text' id="phone" name="phone" required placeholder="8(999)999-99-99"/></p>
                <p><input type="text" id="mail" name="mail"/></p>
            </div>
            <input type="button" id="btn" value="Отправить" style="float: right; margin-right: 10px;"/>
        </div>
    </form>-->

    <form>
        <div class="center">
            <label for="fam">Введите фамилию: <input type="text" id="fam" name="fam" /></label>
            <label for="name">Введите имя: <input type="text" id="name" name="name" /></label>
            <label for="otch">Введите отчество: <input type="text" id="otch" name="otch" /></label>
            <?=$dopOptions?>
            <label for="phone">Введите телефон:* <input type='text' id="phone" name="phone" required placeholder="8(999)999-99-99"/></label>
            <label for="mail">Введите e-mail: <input type="text" id="mail" name="mail"/></label>
            <input type="button" id="btn" value="Добавить" style="    height: 35px;
    width: 270px;
    margin-top: 11px;
    margin-left: 18px;
    position: absolute;"/>
        </div>
    </form>



    <p id="p"><?php if (isset($_SESSION['count'])) echo "Добавленно за эту сессию:". $_SESSION['count'];?></p>
    <div id="div" class="left_div"><?=$_SESSION['added']?></div>
    <!--<fieldset class="right_div">
    <legend>Настройки</legend>
    <div class="checkbox"><input class="checkbox" type="checkbox" id="check_phone" <?php if ($us->get_conf_reg()) echo "checked=\"checked\""?>/><label for="check_phone">Подтверждать номер телефона?</label></div>
        <p>Текст для подтверждения:</p>
    <textarea name="text" id="text" style="margin: 0px; width: 332px; height: 172px;"><?php if ($us->get_conf_msg()) echo $us->get_conf_msg() ?></textarea>
    <input type="button" id="click" value="Изменить текст" style="float: right;"/>
    </fieldset>-->