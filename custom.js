
$("#go").click(function(){
    $.post("http://13.59.66.63/memcon/go.php",
    {
        url: $('#url').val(),
    },
    function(data, status){
		$('#result').html(data);
		    $('html, body').animate({
        scrollTop: $("#result").offset().top
    }, 2000);

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

jQuery("input[id$=\'-value\']").change(function () {
var total = 0.0;
jQuery("input[id$=\'-value\']").each(function (index, value) {
var val = parseFloat(jQuery("#" + index +"-qty").text()) * jQuery(value).val();
jQuery("#" + index + "-total").text(val);
total += val;
});
jQuery("#tot-val").text(total);});



console.log("Ready");