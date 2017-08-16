
$("#go").click(function(){
    $.post("http://13.59.66.63/memcon/go.php",
    {
        url: $('#url').val(),
    },
    function(data, status){
		$('#result').html(data);
		$.scrollTo('#download',800);
    });
});
$("#domains").click(function(){
    
});


console.log("Ready");