$(function() {

$( "#form" ).submit(function( event ) {
  console.log($('input[type="url"]').val());
  event.preventDefault();
});


});
