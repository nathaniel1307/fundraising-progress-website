// Script to handle populating data tables from mysql db and add run distances to db
$(document).ready(function() {
    var URL = window.location.href;
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const access_key = urlParams.get('key');

    if (access_key !== "5846") {
        alert("Please use the link you have been given to register as a runner.");
        window.location = "/";
    }
});


$(document).ready(function() {
    // this is the id of the form
    $("#addrunnerform").submit(function(e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.

        $('#addrunnerspinner').removeClass('invisible');
        $('#addrunnerspinner').addClass('visible');
        $('#runnersubmitbtn').prop('disabled', true);

        var team_name = $("#runnerNameInput").val();

        var URL = window.location.href;
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const access_key = urlParams.get('key');

        $.ajax({    //create an ajax request to addrunner.php
            type: "GET",
            url: "./php/addrunner.php",
            dataType: "html",   //expect json to be returned
            data: {
                team_name: team_name,
            },
            success: function(response){
                $('#addrunnerspinner').removeClass('visible');
                $('#addrunnerspinner').addClass('invisible');
                $('#runneraddedalert').removeClass('hide');
                $('#runneraddedalert').addClass('show');
                console.log(response)
            }
        });
    });
});
