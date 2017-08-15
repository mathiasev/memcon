
$("#go").click(function(){
    $.post("https://google.com",
    {
        name: "Donald Duck",
        city: "Duckburg"
    },
    function(data, status){
        alert("Data: " + data + "\nStatus: " + status);
    });
});

console.log("Loaded");