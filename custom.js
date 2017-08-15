
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
    $.post("http://13.59.66.63/memcon/domains/domains.php",
    {
        registered: 'true',
    },
    function(data, status){
		$('#result').html(data);
    });
});


console.log("Ready");