
$("#go").click(function(){
    $.post("http://13.59.66.63/memcon/go.php",
    {
        url: $('#url').val(),
    },
    function(data, status){
        alert("Data: " + data + "\nStatus: " + status);
    });
});

console.log("Ready");