/**
 * Created by Luisito Suarez on 12/11/2017.
 */
var timers=$("div.producto");
$("div.product-timer").addClass('btn btn-danger');
for(var i=1;i<=timers.length;i++){
  var fecha=new Date($("div.producto:nth-of-type("+i+") div.product-timer").attr("fecha"));
//$(".product-timer")
    $("div.producto:nth-child("+i+") div.product-timer").countdown(fecha,function(event) {
    $(this).html(
        event.strftime(''+'<span class=week-cont><b>%-w</b></span> week%!w '+'<span class="day-cont"><b>%-d</b></span> day%!d '+'<span class="hour-cont"><b>%H</b></span> hr '+'<span><b>%M</b></span>min '+'<span><b>%S</b></span> sec ')
    )
}).on('update.countdown',function(event) {
        for(var j=1;j<=timers.length;j++) {
            timers = $("div.producto");
            if ($("div.producto:nth-of-type(" + j + ") div.product-timer span.week-cont b").html() == 0) {
                $("div.producto:nth-of-type(" + j + ") div.product-timer").addClass("btn btn-info");
                $("div.producto:nth-of-type(" + j + ") div.product-timer").removeClass("btn btn-danger");
            } else {
                $("div.producto:nth-of-type(" + j + ") div.product-timer").removeClass("btn btn-info");
                $("div.producto:nth-of-type(" + j + ") div.product-timer").addClass("btn btn-success");
                $("div.producto:nth-of-type(" + j + ") div.product-timer").removeClass("btn btn-danger");
            }
            if ($("div.producto:nth-of-type(" + j + ") div.product-timer span.week-cont b").html() == '0' && $("div.producto:nth-of-type(" + j + ") div.product-timer span.day-cont b").html() == '0') {
                $("div.producto:nth-of-type(" + j + ") div.product-timer").removeClass("btn btn-info");
                $("div.producto:nth-of-type(" + j + ") div.product-timer").removeClass("btn btn-success");
                $("div.producto:nth-of-type(" + j + ") div.product-timer").addClass("btn btn-warning");
                $("div.producto:nth-of-type(" + j + ") div.product-timer").removeClass("btn btn-danger");
            }
            if ($("div.producto:nth-of-type(" + j + ") div.product-timer span.week-cont b").html() == '0' && $("div.producto:nth-of-type(" + j + ") div.product-timer span.day-cont b").html() == '0' && parseInt($("div.producto:nth-of-type(" + j + ") div.product-timer span.hour-cont b").html()) < 12) {
                $("div.producto:nth-of-type(" + j + ") div.product-timer").removeClass("btn btn-info");
                $("div.producto:nth-of-type(" + j + ") div.product-timer").removeClass("btn btn-success");
                $("div.producto:nth-of-type(" + j + ") div.product-timer").removeClass("btn btn-warning");
                $("div.producto:nth-of-type(" + j + ") div.product-timer").addClass("btn btn-danger");
            }
        }
});
}


$(".produc-timer").addClass('btn btn-danger');
    var fecha2=new Date($(".produc-timer").attr("fecha"));

//$(".product-timer")
    $("div.produc-timer").countdown(fecha2,function(event) {
        $(this).html(
            event.strftime(''+'<span class=week-cont><b>%-w</b></span> week%!w '+'<span class="day-cont"><b>%-d</b></span> day%!d '+'<span class="hour-cont"><b>%H</b></span> hr '+'<span><b>%M</b></span>min '+'<span><b>%S</b></span> sec ')
        )
    }).on('update.countdown',function(event) {

            if ($("div.produc-timer span.week-cont b").html() == 0) {
                $("div.produc-timer").addClass("btn btn-info");
                $("div.produc-timer").removeClass("btn btn-danger");
            } else {
                $("div.produc-timer").removeClass("btn btn-info");
                $("div.produc-timer").addClass("btn btn-success");
                $("div.produc-timer").removeClass("btn btn-danger");
            }
            if ($("div.produc-timer span.week-cont b").html() == '0' && $("div.produc-timer span.day-cont b").html() == '0') {
                $("div.produc-timer").removeClass("btn btn-info");
                $("div.produc-timer").removeClass("btn btn-success");
                $("div.produc-timer").addClass("btn btn-warning");
                $("div.produc-timer").removeClass("btn btn-danger");
            }
            if ($("div.produc-timer span.week-cont b").html() == '0' && $("div.produc-timer span.day-cont b").html() == '0' && parseInt($("div.produc-timer span.hour-cont b").html()) < 12) {
                $("div.produc-timer").removeClass("btn btn-info");
                $("div.produc-timer").removeClass("btn btn-success");
                $("div.produc-timer").removeClass("btn btn-warning");
                $("div.produc-timer").addClass("btn btn-danger");
            }

    });
