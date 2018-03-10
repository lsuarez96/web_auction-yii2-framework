/**
 * Created by Luisito Suarez on 16/11/2017.
 */
$(document).ready(function () {
    setInterval(function () {
        console.log('monitoreo');
        $.ajax({
            url:"index.php?r=subasta/notify-winners",
            type:"GET",//method
            success:function (data) {
                console.log('no hubo fallo');
                if(data.length>0){
                    var res=$.parseJSON(data);
                    var cantNot=parseInt(res['cantNotif']);

                        $("#notificacionesNuevas").html(cantNot);
                }
            },
            error:function () {
                console.log('algo fallo');
            }
        });
    },10*1000);
});

// function monitor() {
//     console.log('monitoreo');
//    $.ajax({
//        url:"index.php?r=subasta/notify-winners",//process request url
//        type:"GET",//method
//        success:function (data) {//success callback function
//            console.log('no hubo fallo');
//        },
//        error:function () {//fail callback function
//            console.log('algo fallo');
//        }
//    });
// }