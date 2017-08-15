
$("#go").click(function(){
    $.post("http://13.59.66.63/memcon/go.php",
    {
        url: $('#url').val(),
    },
    function(data, status){
		$('#result').innerHTML(data);
    });
});

console.log("Ready");