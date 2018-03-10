/**
 * Created by Luisito Suarez on 9/11/2017.
 */

$("#search_btn").click(function(event){
    event.preventDefault();
    filter_result=$("#filter_result").val();
    console.log(filter_result);
    location.href="index.php?r=producto/filter-by-text&filter_result="+filter_result;
    // $.get("index.php?r=producto/filter-by-text",{filter_result:filter_result},function (data) {
    //
    // });
});
