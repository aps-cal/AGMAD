
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//header('Content-Type: application/javascript');
$(document).ready(function(){
    alert ('Fired!');
    $('#Reports').hidden=true;
});
 
$('.Student').mouseover(function (){
    $('.Reports').hidden=false;
});
    
$('.Student').mouseout(function (){
    $('.Reports').hidden=true;
});       