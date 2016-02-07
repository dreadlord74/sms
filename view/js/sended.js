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

                        //$(".tab_"+id).find("div").detach();
                        var data = $.parseJSON(data);

                        var count = data.length;
                       // pagination(count);
                        
                       // table.append("<div class='row'><span>ФИО</span><span>Телефон</span><span>Статус</span></div>");
                        var it = 0;
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
/*
                            if (item.phone == null)
                                phone = item.phone1;
                            else
                                phone = item.phone;

                            if (it <= 32){
                                if (it == 0){
                                    table.append("<div class='page' style='display: block;'>");

                                    if (i % 2 != 0){
                                        table.append("<div class='row' style='background: gainsboro; color: #878BB6;' id='"+id+"'><span>"+item.fam+" "+item.name+" "+item.otch+"</span><span>"+phone+"</span><span>"+status+"</span></div>");
                                    }else {
                                        table.append("<div class='row' id='" + id + "'><span>" + item.fam + " " + item.name + " " + item.otch + "</span><span>" + phone + "</span><span>" + status + "</span></div>");
                                    }
                                    table.append("</div>");
                                }else{
                                    if (i % 2 != 0){
                                        table.append("<div class='row' style='background: gainsboro; color: #878BB6;' id='"+id+"'><span>"+item.fam+" "+item.name+" "+item.otch+"</span><span>"+phone+"</span><span>"+status+"</span></div>");
                                    }else {
                                        table.append("<div class='row' id='" + id + "'><span>" + item.fam + " " + item.name + " " + item.otch + "</span><span>" + phone + "</span><span>" + status + "</span></div>");
                                    }
                                }
                                it++;
                            }else{
                                table.append("<div class='page' style='display: none;'>");

                                if (i % 2 != 0){
                                    table.append("<div class='row' style='background: gainsboro; color: #878BB6;' id='"+id+"'><span>"+item.fam+" "+item.name+" "+item.otch+"</span><span>"+phone+"</span><span>"+status+"</span></div>");
                                }else {
                                    table.append("<div class='row' id='" + id + "'><span>" + item.fam + " " + item.name + " " + item.otch + "</span><span>" + phone + "</span><span>" + status + "</span></div>");
                                }

                                table.append("</div>");
                            }

*/
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