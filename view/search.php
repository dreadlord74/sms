<script type="text/javascript">
$("document").ready(function(){
    $('#phone').mask('89999999999');

    $("#btn").click(function(){
        var phone = $("#phone").val();
            $.ajax({
                url: "./?view=search&get=phone",
                type: "POST",
                data: "phone="+phone,
                success: function(data){
                    if (data != "[]"){
                        data = $.parseJSON(data);

                        $.each(data, function(i, item){
                            alert(data.fam+" "+data.name+" "+data.otch);
                        });
                    }else if(data == "[]"){
                        alert("Не удается найти запись с таким номером");
                        $("#phone").val("");
                    }
                }
            });
    });
});
</script>
<div class="wrapper">
    <div class="search">
        <input type="text" required placeholder="8999999999" id="phone"/>
        <input type="button" id="btn" value="Поиск"/>
    </div>

</div>
