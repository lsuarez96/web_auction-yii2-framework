/**
 * Created by Luisito Suarez on 9/11/2017.
 */
var ind = 1;
$(document).ready(function () {
    $(window).scroll(function () {
        if (Math.round($(window).scrollTop()) == Math.round($(document).height() - $(window).height())) {
            var type = $(".pagina:last").attr('typ');
            var subtype = $(".pagina:last").attr('subtyp');
            var filter = $("#filter-product-list").attr("filter");
            var page = parseInt($(".pagina:last").attr('page'));
            if (type != undefined) {
                if (page == undefined) {
                    page = 0;
                }
                page = parseInt(page) + 1;
                $.get('index.php?r=producto/get-products-by-type', {type: type, page: page}, function (data) {
                    var amountData = $("#type-product-list div.pagina").length;
                    if (data.length > 0) {
                        $("#type-product-list").append(data);
                    } else if ($('.load-info').attr('id') == undefined) {
                        $("#type-product-list").append('<div class="load-info container-fluid" id="no-more-data" style="height: 40px; width: 100%; text-align: center; margin-top: 40px;  display: inline-block;"><div class=" alert alert-info"><p >No hay mas informacion para ser cargada</p></div></div>');
                    }
                });
            } else if (subtype != undefined) {
                if (page == undefined) {
                    page = 0;
                }
                page = parseInt(page) + 1;
                $.get('index.php?r=producto/get-products-by-subtype', {
                    type: subtype,
                    page: page
                }, function (data) {
                    if (data.length > 0) {
                        $("#subtype-product-list").append(data);

                    } else if ($('.load-info').attr('id') == undefined) {
                        $("#subtype-product-list").append('<div class="load-info container-fluid" id="no-more-data" style="height: 40px; width: 100%; text-align: center; margin-top: 40px;  display: inline-block;"><div class=" alert alert-info"><p >No hay mas informacion para ser cargada</p></div></div>');
                    }
                });
            } else if (filter != undefined) {
                if (page == undefined) {
                    page = 0;
                }
                page = parseInt(page) + 1;
                $.get('index.php?r=producto/get-products-filter', {filter: filter, page: page}, function (data) {
                    if (data.length > 0) {
                        $("#filter-product-list").append(data);
                        console.log("puso los datos");
                    } else if ($('.load-info').attr('id') == undefined) {
                        $("#filter-product-list").append('<div class="load-info container-fluid" id="no-more-data" style="height: 40px; width: 100%; text-align: center; margin-top: 40px;  display: inline-block;"><div class=" alert alert-info"><p >No hay mas informacion para ser cargada</p></div></div>');
                    }
                }).fail(function () {
                    // alert("method has fail");
                    console.log("method failure");
                });
            } else if ($("#notifications-container").first().attr('sec') == 'notificaciones') {
                //alert($("tr.pagina").last().attr('pag'));
                page = parseInt($("#notifications-container .pagina:last").attr('pag'));

                if (page == undefined) {
                    page=0;
                }
                page = parseInt(page) + 1;
               // alert(page);
                $.get('index.php?r=notificaciones/notificaciones-scroll', {page: page}, function (data) {

                    if (data.length > 0) {
                        $("#notif-table").append(data);
                    } else if ($('.load-info').attr('id') == undefined) {
                        $("#notifications-container").append('<div class="load-info container-fluid" id="no-more-data" style="height: 40px; width: 100%; text-align: center; margin-top: 40px;  display: inline-block;"><div class=" alert alert-info"><p >No hay mas informacion para ser cargada</p></div></div>');
                    }
                });

            }
        }
    });


});
