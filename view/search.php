<script type="text/javascript">
$("document").ready(function(){
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
    
    $("#btn").click(function(){
        var bool = false, phone = $("#phone").val();
        bool = check_phone();
        
        if (bool){
            $.ajax({
                url: "./?view=search&get=phone",
                type: "POST",
                data: "phone="+phone,
                success: function(data){
                    if (data){
                        data = $.parseJSON(data);
                        
                        $.each(data, function(i, item){
                            $("#div").text(item.fam+" "+item.name+" "+item.otch+" "+item.date+" "+item.date_ver);
                        });
                    }else if(data == "Не удается найти запись с таким номером"){
                        alert(data);
                        $("#phone").val("");
                    }
                }
            });
        }
    });
});
</script>

<input type="text" id="phone"/>
<input type="button" id="btn" value="Поиск"/>

<div id="div"></div>