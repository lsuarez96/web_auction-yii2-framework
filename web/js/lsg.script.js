/**
 * Created by Luisito Suarez on 22/10/2017.
 */

$(document).ready(function () {
    $(".megamenu").megamenu();
    //drawShopNavBar();
  //  drawMegaPanel();
    

});



function drawShopNavBar() {
    $.get("index.php?r=tipo/get-types-for-nav-bar", function (data) {
       //console.log(data);
        $("ul#shop_menu").html(data);

    });
}

function drawMegaPanel(){
    $.get("index.php?r=subtipo/get-subtypes-for-shop-nav-bar", function (data) {
        //console.log(data);
        $(".megapanel").html(data);

    });

}



