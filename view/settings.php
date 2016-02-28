<div class="settings">
    <form method="post" action="<?=PATH?>/?view=settings&do=update" name="settings">
        <label>Логин: </label><input disabled type="text" name="login" value="<?=$_SESSION['login']?>"/>
        <label>Новый пароль: <input type="password" name="new_pass"/></label>
        <label>Пароль: <input type="password" name="pass"/></label>
        <input type="submit" name="seb" value="Применить"/>
    </form>
</div>