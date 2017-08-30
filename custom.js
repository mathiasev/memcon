
$("#go").click(function(){
    $.post("http://13.59.66.63/memcon/go.php",
    {
        url: $('#url').val(),
    },
    function(data, status){
		var js = "<script>jQuery(\"input[id$=\'-value\']\").change(function () {var total = 0.0;jQuery(\"input[id$=\'-value\']\").each(function (index, value) {var val = parseFloat(jQuery(\"#\" + index +\"-qty\").text()) * jQuery(value).val();jQuery(\"#\" + index + \"-total\").text(val);total += val;});jQuery(\"#tot-val\").text(total);});</script>";
		$('#result').html(data + js);
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

