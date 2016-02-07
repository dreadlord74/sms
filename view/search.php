<script type="text/javascript">
$("document").ready(function(){
    $('#phone').mask('8(999)999-99-99');

    $("#btn").click(function(){
        var phone = $("#phone").val();
            $.ajax({
                url: "./?view=search&get=phone",
                type: "POST",
                data: "phone="+phone,
                success: function(data){

                    if (data != "[]"){
                        data = $.parseJSON(data);
                            alert(data.fam+" "+data.name+" "+data.otch);
                            console.log(data.fam+" "+data.name+" "+data.otch);
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
        <input style="text-align: center" type="text" required placeholder="8(999)999-99-99" id="phone"/>
        <input type="button" id="btn" value="Поиск"/>
    </div>

</div>
