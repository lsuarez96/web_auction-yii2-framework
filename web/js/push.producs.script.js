/**
 * Created by Luisito Suarez on 5/11/2017.
 */
$(document).ready(function () {
    $(".push_button").click(function(event) {

        var idprod=$(this).attr('prod');
        //alert(idprod);
        var money= $("#puja"+idprod).val();
        ///alert(money);
        if(money!=undefined && money>0){
            $.get('index.php?r=subasta/puch',{idprod:idprod,money:money},function(data) {
                if(data.length>0){
                    var res=$.parseJSON(data);
                    var id=res['idprod'];
                    var money=res['money'];
                    var act=res['actividad'];
                    if(!res['message']) {
                        console.log('entro al if del money');
                      //  alert($(".precio"+idprod+" span").html());
                        $("#precio"+idprod+" span").html("$" + money);
                        var textf=$("#puja"+idprod);
                        textf.val("");
                        money=parseInt(money)+1;
                        textf.attr('placeholder',"$"+((money)));
                        textf.attr('min',money);
                        $("#actividad"+idprod).html("Actividad del producto: "+act+" pujas");
                    }
                }

            });
        }
    });
});

