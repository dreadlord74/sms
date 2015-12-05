<script>
$("document").ready(function(){
    $("#sub").click(function(e){
        e.preventDefault();
        var formData = new FormData($('form')[0]);
        $.ajax({
              type: "POST",
              processData: false,
              contentType: false,
              url: "./?view=not_view&do=upload",
              data:  formData,
              success: function(data){
                alert(data);
                if(data){
                    data = jQuery.parseJSON(data);
                    
                    alert(data);
                    var count = 0, div = $("#div");
                    
                    $.each(data, function(i, item){
                        count++;
                        div.prepend("<p><input value='"+item.fam+"' type='text' name='fam' id='fam'/><input value='"+item.name+"'  type='text' name='name' id='name'/><input value='"+item.otch+"'  type='text' name='otch' id='otch'/><input value='"+item.phone+"'  type='text' name='phone' id='phone'/><input value='"+item.date+"'  type='text' name='date' id='date'/></p>").css("visibility", "visible");
                    });
                }  
              }
        });
    });
});
</script>

<div class="center">
<form action="" enctype="multipart/form-data">
<input type="file" name="file"/>
<input type="submit" id="sub"/>
</form>
</div>

<p id="p"></p>
<div id="div" class="left_div">

</div>