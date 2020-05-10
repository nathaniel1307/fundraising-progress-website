$(document).ready(function() {
    var ctx = document.getElementById('leaderboardChart');

    // AJAX request to get each Teams progress
    $.ajax({    //create an ajax request to getProgress.php
        type: "GET",
        url: "./php/getTeamProgress.php",
        dataType: "json",   //expect json to be returned
        data: {
            //access_key: access_key, 
        },
        success: function(response){
            //alert(response.totaldist);
            //console.log(response.data) // array of Teams and distances (data.team, and data.distance respectively)
            var Teams_arr = [];
            var dist_arr = [];
            var colour_arr = [];
            response.data.forEach(function(dat) {
                Teams_arr.push(dat.team);
                dist_arr.push(dat.distance);
                //Colour Setting
                if(dat.team === "Typhoon Squadron"){
                    colour_arr.push("purple");
                }else if (dat.team === "Gloucester Penguins; for Ben"){
                    colour_arr.push("red");
                }else if (dat.team === "Community"){
                    colour_arr.push("yellow");
                }else if(dat.team === "HMS Grimsby"){
                    colour_arr.push("navy");
                }else if(dat.team === "Thunderer Squadron"){
                    colour_arr.push("blue");
                }else{
                    colour_arr.push("grey");
                }
            });

            var myChart = new Chart(ctx, {
                type: 'horizontalBar',
                data: {
                    labels: Teams_arr,
                    datasets: [{
                        label: 'km run',
                        data: dist_arr,
                        backgroundColor: colour_arr,
                        barPercentage: 0.7
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }],
                        xAxes: [{
                            ticks: {
                                beginAtZero: true
                            },
                            scaleLabel: {
                                display: true,
                                labelString: "Distance run (km)",
                            }
                        }]
                    },
                    legend: {
                        display: false
                    },
                    maintainAspectRatio: false,
                }
            });
        }
    });
});