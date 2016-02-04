$(document).ready(function(){
        setInterval(function(){
            $.ajax({
                url: "./?view=not_view&do=get_status",
                type: "POST",
                success: function(data){
                    if (data){
                        data = $.parseJSON(data);

                        $.each(data, function(i, item){
                            $("#lol").remove();
                            if (item.power == 1) {
                                var status = "work", power = "Сервис запущен!";
                            }else{
                                var status = "not_work", power = "Сервис не запущен!";
                            }
                            $("#devices").append("<li id='lol' class='li "+status+"' title='"+power+"'>"+item.manufacturer+" "+item.device_name+"</li>");
                        });
                    }
                }
            });
        },5000);
});
