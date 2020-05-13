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
           // arrays of Teams, distances, colors (dat.team, and dat.distance respectively)
            var Teams_arr = [];
            var dist_arr = [];
            var colour_arr = [];
            response.data.forEach(function(dat) {
                Teams_arr.push(dat.team);
                dist_arr.push(dat.distance);
                //Colour Setting
                if(dat.team === "Typhoon Squadron"){
                    colour_arr.push("darkviolet");
                }else if (dat.team === "Gloucester Penguins"){
                    colour_arr.push("red");
                }else if (dat.team === "Friends & Family"){
                    colour_arr.push("yellow");
                }else if(dat.team === "HMS Grimsby"){
                    colour_arr.push("navy");
                }else if(dat.team === "Thunderer Squadron"){
                    colour_arr.push("darkturquoise");
                }else if(dat.team === "Taurus Squadron"){
                    colour_arr.push("green");
                }else if(dat.team === "Trojan Squadron"){
                    colour_arr.push("magenta")
                }else if(dat.team === "Worldline & Friends"){
                    colour_arr.push("deepskyblue")
                }else if(dat.team === "Looseheadz"){
                    colour_arr.push("springgreen");
                }else if(dat.team === "Crypt Runners"){
                    colour_arr.push("darkorange");
                }else{
                    colour_arr.push("grey");
                }
            });
            //Horizontal bar chart to act as leaderboard
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