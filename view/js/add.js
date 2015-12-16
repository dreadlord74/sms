$(document).ready(function(){
                var i = 0, year = new Date(), date;
                
                function check_mail(){
                    if ($("#mail").val() != ""){
                        var regV = /^[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}$/;
                        if ($("#mail").val().match(regV) != null){
                            return true;
                        }else{
                            alert("Почта введена неверно!");
                            $("#mail").val("");
                            return false;
                        }
                    }else{
                        return true;
                    }
                }
                
                function check_phone(){
                    if ($("#phone").val() !=""){
                        var regV = /^89\d{9}$/;
                        if ($("#phone").val().match(regV) != null){
                            return true;
                        }else{
                            alert("Номер телефона введен неверно!");
                            $("#phone").val("");
                            return false;
                        }
                    }else{
                        return true;
                    }
                }
                
                function check_date(save){
                    var data = $("#date").val();
                    var regv_date = /(19|20)\d\d-((0[1-9]|1[012])-(0[1-9]|[12]\d)|(0[13-9]|1[012])-30|(0[13578]|1[02])-31)/g;
                    if (data.match(regv_date) != null){
                        if (save){
                            date = data;
                        }
                        return true;
                    }else{
                        while (data.match(regv_date) == null){
                            data = prompt("Указана неверная дата, повторите:");
                        }
                    }
                    
                    if (data.match(regv_date) != null){
                        if (save){
                            date = data;
                            alert("Дата сохранена!");
                        }
                    }else{
                        if (save){
                            alert("Дата не сохранена!");
                        }
                    }
                }
                
                $("#save").click(function(){
                    check_date(true);             
                });
                
                $("#delete").click(function(){
                    date = false;
                    $("#date").val("");
                })
                
                function clear (){
                    $("#fam").val("");
                    $("#name").val("");
                    $("#otch").val("");
                    $("#phone").val("");
                    $("#mail").val("");
                    $("#date").val("");
                }
                
                function check_fio(){
                    regv = /^[А-Яа-я]{1,}$/g;

                    if ($("#fam").val().match(regv) == null){
                        alert("Фамилия введена неверно!");
                        $("#fam").val("");
                        while($("#fam").val().match(regv) == null){
                            $("#fam").val(prompt("Введеите фамилию:"));
                        }
                    }
                    
                    if ($("#name").val().match(regv) == null){
                        alert("Имя введено неверно!");
                        $("#name").val("");
                        while($("#name").val().match(regv) == null){
                            $("#name").val(prompt("Введеите имя:"));
                        }
                    }
                    
                    if ($("#otch").val().match(regv) == null){
                        alert("Отчество введено неверно!");
                        $("#otch").val("");
                        while($("#otch").val().match(regv) == null){
                            $("#otch").val(prompt("Введеите отчество:"));
                        }
                    }
                    
                    if (($("#fam").val().match(regv) != null) && ($("#name").val().match(regv) != null) && ($("#otch").val().match(regv) != null)){
                        return true;
                    }else{
                        alert("Что-то пошло не так!");
                        return false;
                    }
                    
                }
                
                $("#btn").click(function(){
                    var check = false;
                    check = check_fio();
                    if (check){
                        check = check_mail();
                        if (check){
                            check = check_phone();
                            if (check){
                                check = check_date(false);
                                if (check){
                                    var data = $("form").serialize();
                                    $.ajax({
                                       url: './?view=add_new',
                                       type: 'POST',
                                       data: data,
                                       success: function(data){
                                            
                                            if(data){
                                                $("#div").prepend("<p class='added'>"+data+"</p>").css("visibility", "visible");
                                                i++;
                                                $("#p").text("Добавленные за эту сессию: "+i);
                                                clear();
                                                if(date){
                                                    $("#date").val(date);    
                                                }
                                            }else{
                                                alert("Не работает");
                                            }
                                       }
                                    }); 
                                }
                            }
                        }
                    }
                });
                
$("#check_phone").change(function(){
    $.ajax({
        url: "./?view=not_view&do=confirm_reg",
        type: "POST",
        before: $("#check_phone").addClass("loading"),
        data: "ver="+$("#check_phone").prop("checked"),
        success: function(data){
            if (!data){
                $("#check_phone").prop("checked", false);
            }
        },
        complete: function(){
            setTimeout(3000);
            $("#check_phone").removeClass("loading");
        },
        error: function(){
            alert("Не удалось выполнить");
        }
    });
});

$("#click").click(function(){
    if ($("#text").val().length < 10){
        alert("Текст сообщения не может быть короче 10 символов")
    }else{
        $.ajax({
            url: "./?view=not_view&do=confirm_msg",
            type: "POST",
            data: "text="+$("#text").val(),
            success: function(data){
                if (data){
                    alert("Текст сообщения изменен успешно!");
                }else{
                    alert("Не удалось изменить текст сообщения")
                }
                
            },
            error: function(){
                alert("Не удалось выполнить");
            }
        });
    }
});

});