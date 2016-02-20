$(document).ready(function(){
var attempts = 0;
    function check_data(text, lenght){
        if (text.length < lenght)
            return false;
        else
            return true;
    }

    function send(id, text, tema){
        $.ajax({
            url: "./?view=send_mass&do=send",
            type: "POST",
            data: "text="+text+"&tema="+tema+"&id="+id,
            success: function(data){
                //alert(data);
				$("#div_al").append(data);
                if (data){
                    data = $.parseJSON(data);
                    var error = false, vivod = "Отправка прошла успешно на следующих устройствах: ";
                    $.each(data, function(i, item){
                        if (item == 0){
                            vivod = vivod + i+" ";
                        }else{
                            error = true;
                        }
                    });

                    if (!error){
                        alert("Отправка прошла успешно!");
                        redirect();
                    }else
                        alert(vivod);
                }else
                    alert("Отправка запрещена!");
            }
        });
    }

    function attempt(text, tema, pass, id){
        $.ajax({
            url: "./?view=send_mass&do=attempt",
            type: "POST",
            data: "id="+id+"&pass="+pass,
            success: function(data){
                //alert(data);
                if (data)
                    send(data, text, tema);
                else{
                    alert("Пароль введен неверно!");
                    attempts++;
                    if (attempts > 5)
                        alert("Пароль был ввведен неверно 5 раз. Повторите попытку позже!");
                    else
                        get_pass(text, tema);
                }
            }
        });
    }

    function get_pass(text, tema){
        $.ajax({
            url: "./?view=send_mass&do=get_pass",
            type: "POST",
            data: "text="+text+"&tema="+tema,
            success: function(data){
               // alert(data);
                if (data){
                    var pass = prompt("На ваш номер отправлено сообщение с паролем. Введите пароль для запуска рассылки. Не закрывайте поле ввода пока не введёте пароль.");
                    attempt(text, tema, pass, data);
                }else
                    alert("Не удалось выполнить смс-подтверждение, повторите позже.");
            }
        });
    }

    $("#btn").click(function(){
        var text = $("#text").val(), tema = $("#tema").val();
        if (check_data(text, 10))
            if (check_data(tema, 5)){
            var conf = confirm("Вы подтверждаете, что хотите осуществить рассылку?");
                if (conf)
                    get_pass(text, tema);
                else
                    alert("Рассылка отменена.");
            }else
                alert("Длина темы не может быть меньше 5 символов");
       else
           alert("Длина сообщения не сожет быть меньше 10 символов");
    });
});