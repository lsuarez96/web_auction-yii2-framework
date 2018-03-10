/**
 * Created by Luisito Suarez on 8/11/2017.
 */

    $(".boton-post").click('click',function(event){
        var coment=$("#coment").val();
        var product=$(this).attr('id');
        $.get("index.php?r=comentario/post-coment",{product:product,coment:coment},function (data) {
            var res=$.parseJSON(data);
            if(!res['message']) {
                var usuario=res['usuario'];
                var append = '<div class="row"><h3 class="col-sm-2">'+usuario+': </h3><p class="col-sm-10">'+coment+'</p></div>';
                $("#comentarios").append(append);
                $("#coment").val("");
            }
        });
    });


