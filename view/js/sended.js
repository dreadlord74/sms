$("document").ready(function(){

    function pagination (count_rows, page){
        var perpage = 30;//количество элементов на страницу

        var count = (count_rows / perpage); //количество страниц

        if (!count) count = 1;//минимум одна страница

        var start_pos = (page - 1) * perpage;
    }


    
    function update (){
        $.ajax({
            url: "./model/auto.php",
            type: "POST"
        });
    }
    
    function get_sended(id){
            var table = $(".tab_"+id), val = id;
            $.ajax({
                url: "./?view=sended&do=get",
                type: "POST",
                data: "id="+val,
                success: function(data){
                    if (data){

                        var status = "", errors = 0, send = 0, deliv = 0, count = 0, otprav = 0, msg = "", phone;

                        $(".tab_"+id).find("tr").detach();
                        var data = $.parseJSON(data);

                        var count = data.length;
                       // pagination(count);
                        
                        table.append("<tr><td>ФИО</td><td>Телефон</td><td>Статус</td></tr>");
                        
                        $.each(data, function(i, item){

                            if (item.is_error == 1){
                                status = "Ошибка";
                                errors++;
                            }else if (item.delivered == 1){
                                status = "Доставлено";
                                deliv++;
                            }else{
                                status = "Отправлено";
                                send++;
                            }
                            count++;

                            if (item.phone == null)
                                phone = item.phone1;
                            else
                                phone = item.phone;
                            console.log(phone);

                            if (i % 2 != 0){
                                table.append("<tr style='background: gainsboro; color: #878BB6;' id='"+id+"'><td>"+item.fam+" "+item.name+" "+item.otch+"</td><td>"+phone+"</td><td>"+status+"</td></tr>");
                            }else {
                                table.append("<tr id='" + id + "'><td>" + item.fam + " " + item.name + " " + item.otch + "</td><td>" + phone + "</td><td>" + status + "</td></tr>");
                            }
                             msg = item.msg;
                        });
                    $(".sended_"+id).text("Всего отправлено: "+count); $(".deliv_"+id).text("Доставлено: "+deliv);
                    otprav = errors+send;
                    $(".error_"+id).text("Ошибок: "+errors);
                    $(".otprav_"+id).text("Не доставлено: "+otprav);
                    $("#msg_"+id).text(msg);
                    
                    var countries= document.getElementById("countries_"+id).getContext("2d");
                    var pieData = [
                       {
                          value: deliv,
                          color:"#878BB6"
                       },
                       {
                          value : otprav,
                          color : "#4ACAB4"
                       },
                       {
                          value : errors,
                          color : "#FF8153"
                       }
                    ];
                    
                    new Chart(countries).Pie(pieData);
                        errors = 0; send = 0; deliv = 0; count = 0;
                    }
                }
        
            });
        }

function update_page (ids){
    $.each(ids, function(i, item){
        update();
        if (item != 0)
            get_sended(item);
    });
}
    update_page(ids);

        if (otprav != 0){
            clearInterval(timer);

            var timer = setInterval(function(){
                update_page(ids);
            },10000);
        }
});