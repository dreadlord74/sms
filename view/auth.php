<form method="post" action="<?=PATH?>?do=auth">
    <div class="auth"> 
        <div>
            <p>Введите логин: </p>
            <p>Введите пароль: </p>
        </div>
        <div>
            <p><input type="text" id="login" name="login"/></p>
            <p><input type="password" id="pass" name="pass"/></p>
        </div>
        <input type="submit" id="btn" value="Отправить" style="  margin-right: 10px;
  margin-left: 10px;
  width: 340px;"/>
    </div>
    </form>