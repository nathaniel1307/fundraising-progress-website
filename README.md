# fundraising-progress-website
A website for displaying multiple teams progress along a route. Allowing each team to add there own progress in a seperate area


# Things you need to use this
This website will not work out of the box. You will need to set up the following:
- MySQL Database (see below)
- mapbox access token
- geoJSON file or lineString coordinates of your route (use https://maps.openrouteservice.org/ to generate one), alternatively uncomment the greatCircle code in relayroute.js for a point-to-point route


# Database structure
Enter the details for accessing your MySQL databse anywhere in the php code you see "DB credentials".

accesskeys
* columns: id, access_key,team_name,table_name
* description: this is the table of unique links for each team (access_key), the team name (team_name) and the name of the table where their distances are to be saved

total_distances
* columns: id,team,distance
* description: one row for each team, this data is used to populate the leaderboard on the map page

TEAM1table
* columns: id,timestamp,runner_name,distance
* description: For example this could be called TEAM1table (this would be named whatever you put in accesskeys as the tablename) the submitrun page form fills go to this table and update the distance in total_distance for that team. 

# Usage
Give each team a link like https://cuas.org.uk/uasgardenrelay/submitrun/?key=7sdhe3d78cnq00zxcnasd where the key is the access_key in the accesskeys table. They use this to submit distances, this adds to their teams total on the map page and moves the marker along the route on the map.
