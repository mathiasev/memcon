
$("#go").click(function(){
    $.post("http://ec2-18-220-220-188.us-east-2.compute.amazonaws.com/mem/go.php",
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
    $.post("http://ec2-18-220-220-188.us-east-2.compute.amazonaws.com/mem/go2.php",
    {
        url: $('#url').val(),
    },
    function(data, status){
		$('#result').html(data);
		$.scrollTo('#download',800);
    });
});



console.log("Ready");

