/**
 * Created by Luisito Suarez on 24/11/2017.
 */
// $('#export-pdf').click(function(){
//     $.get('index.php?r=site/pdf',function(data){
//         if(data.length>0){
//             var doc = new jsPDF('p', 'in', 'letter');
//
// // We'll make our own renderer to skip this editor
//             var specialElementHandlers = {
//                 '#editor': function(element, renderer){
//                     return true;
//                 }
//             };
//
// // All units are in the set measurement for the document
// // This can be changed to "pt" (points), "mm" (Default), "cm", "in"
//             doc.fromHTML(data, 15, 15, {
//                 'width': 170,
//                 'elementHandlers': specialElementHandlers
//             });
//             doc.save('reporte.pdf');
//         }
//     });
// });