// Script to handle populating datatables from mysql db and add run distances to db
$(document).ready(function() {
    var URL = window.location.href;
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const access_key = urlParams.get('key');

    if (access_key == null) {
        alert("Please use the unique link you have been given to submit a run.");
    }

    $.fn.dataTable.ext.errMode = 'throw';

    $('#uniquetable').DataTable( {
        "processing": true,
        "serverSide": true,
        "searching": false,
        "paging": true,
        "ajax": {
            "url": "./php/accesstable.php",
            "data": {
                "access_key" : access_key
            }
        },
        "columns": [
            { "data": "run_timestamp" },
            { "data": "runner_name" },
            { "data": "distance_km" }
        ]
    });
});

$(document).ready(function() {
    var URL = window.location.href;
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const access_key = urlParams.get('key');

    $.ajax({    //create an ajax request to getTeamdata.php
        type: "GET",
        url: "./php/getTeamdata.php",
        dataType: "json",   //expect json to be returned
        data: { 
            access_key: access_key, 
        },                
        success: function(response){
            $("#addentryheading").text("Add Entry for " + response.teamname);
            $("#distheading").text(response.teamname + " Distances Submitted");
            //alert(response);
            // Add options
            $.each(response.runners, function(i, item) {
                $("#runnersSelect").append('<option>' + item.runner_name + '</option>');
            });
        }
    });
});

$(document).ready(function() {
    $("#addrunnerbutton").click(function(e) {
        var newRunnerName = $("#runnerNameInput").val();
        $("#runnersSelect").append('<option>' + newRunnerName + '</option>');
        $("#runnersSelect").val(newRunnerName);
        $("#runnersSelect").attr("disabled", true); 
    });
});

$(document).ready(function() {
    // this is the id of the form
    $("#addrunform").submit(function(e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.

        $('#addrunspinner').removeClass('invisible');
        $('#addrunspinner').addClass('visible');
        $('#runsubmitbtn').prop('disabled', true);

        var runnerName = $("#runnersSelect").val();
        var runDist = $("#runDistanceInput").val();

        var URL = window.location.href;
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const access_key = urlParams.get('key');

        $.ajax({    //create an ajax request to addrun.php
            type: "GET",
            url: "./php/addrun.php",             
            dataType: "html",   //expect json to be returned
            data: {
                access_key: access_key,
                name: runnerName, 
                distance: runDist,
            },
            success: function(response){
                $('#addrunspinner').removeClass('visible');
                $('#addrunspinner').addClass('invisible');
                $('#runaddedalert').removeClass('hide');
                $('#runaddedalert').addClass('show');
                console.log(response);
                console.log('logged run for: ' + response.run_name_raw);

            }
        });
    });
});



/* WORKING AJAX EXAMPLE
$(document).ready(function() {
    $('#example').DataTable( {
        "ajax": '../dbtabletest/ajax/data/arrays.txt'
    });
}); */