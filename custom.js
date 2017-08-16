
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
$("#go2").click(function(){
    $.post("http://13.59.66.63/memcon/go2.php",
    {
        url: $('#url').val(),
    },
    function(data, status){
		$('#result').html(data);
		$.scrollTo('#download',800);
    });
});


console.log("Ready");