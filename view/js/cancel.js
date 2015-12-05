$("document").ready(function(){
    $("body").on('click','a[href="/cancel"]',function(){
        $.ajax({
            url: "./?view=not_view",
            type: 'GET',
            data: "do=cancel",
            success: function(data){
                if (data){

                    data = $.parseJSON(data);

                    var error = false, vivod = "";
                    $.each(data, function(i, item){
                        if (item != 0){
                            error = true;
                        }else if(item == 0){
                            vivod = vivod + "На устройстве "+i+" отмена отправки прошла успешно\r\n";
                        }
                    });

                    if (!error){
                        alert("На всех устройствах отменена отправки прошла успешно!");
                    }else{
                        alert(vivod);
                    }
                }else{
                    alert("Отмена отправки не удалась!");
                }
            }
        });
        return false;
    });
});