<script type="text/javascript">
    $(document).ready(function(){
        var count_pass = 0;
        function auth(pass)
        {
            var data = $(".auth").serialize();
            //alert(data);
            $.ajax({
                url: "./?do=auth",
                type: "POST",
                data: data+"&password="+pass,
                success: function(data){
                    alert(data);
                    if (data == "true")
                    {
                        document.location.href = "<?=PATH?>";
                    } else if (data == "false")
                    {
                        alert("Неверный пароль!");
                        count_pass++;
                        var password = prompt("На ваш телефон выслан пароль для входа. Ожидание ввода:");
                        auth(password);
                    }
                }
            });
        }

        $("#btn").click(function(e){
            e.preventDefault();

            var data = $(".auth").serialize();
            $.ajax({
                url: "./?do=auth",
                type: "POST",
                data: data,
                success: function(data){
                    alert(data);
                    if (data == "false"){
                        alert("Такой комбинации логина и пароля не неайдено, либо ваша учетная запись отключена.");
                        $("#pass").val("");
                    }else if (data == "true"){
                        document.location.href = "<?=PATH?>";
                    }else if (data == "pass"){
                        var password = prompt("На ваш телефон выслан пароль для входа. Ожидание ввода:");
                        auth(password);
                    }
                },
                error: function(){
                    alert("Произошла ошибка! Повторите попытку позже.");
                }
            });

        });
    });
</script>
<form class="auth" method="post" action="<?=PATH?>?do=auth">
    <label>Введите логин: <input style="margin-left: 8px;" type="text" id="login" name="login" /></label>
    <label>Введите пароль: <input type="password" id="pass" name="pass"/></label>
    <input type="submit" id="btn" value="Отправить" style="  margin-right: 10px;
  margin-left: 10px;
  width: 288px;"/>
</form>