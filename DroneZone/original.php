
<html>
<head>
<title>DroneZone</title>
		<script src="https://cdn.pubnub.com/sdk/javascript/pubnub.4.4.1.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" />
</head>
<body>

<!--CSS CODE-->
<style type = "text/css">

#main{
	margin:auto;
}

#legends{
	border: 3px solid #000;
	background-color: rgba(240, 240, 240, .6);
	padding: 10px;
	margin: 10px;
	width:10%;
}

#objstats{
	border: 3px solid #000;
	background-color: rgba(240, 240, 240, .6);
	padding: 10px;
	margin: 10px;
	width:12%;
}

#dronestats{
	border: 3px solid #000;
	background-color: rgba(240, 240, 240, .6);
	padding: 10px;
	margin: 10px;
	width:12%;
}

#map-canvas{
	margin:auto;
	border:3px outset darkred;
	height:100%;
	width:100%;
}

#object{
	text-align: center;
	width:100%;
}

#owndrone{
	text-align: center;
	width:100%;
}

#ObjLat{
	text-align: center;
	width:100%;
}

#ObjLong{
	text-align: center;
	width:100%;
}

#ObjAlt{
	text-align: center;
	width:100%;
}

#ObjVel{
	text-align: center;
	width:100%;
}

#ObjAccel{
	text-align: center;
	width:100%;
}

#ObjID{
	text-align: center;
	width:100%;
}

#DroneLat{
	text-align: center;
	width:100%;
}

#DroneLong{
	text-align: center;
	width:100%;
}

#DroneAlt{
	text-align: center;
	width:100%;
}

#DroneVel{
	text-align: center;
	width:100%;
}

#DroneAccel{
	text-align: center;
	width:100%;
}

#DroneID{
	text-align: center;
	width:100%;
}

button{
		display:block;
		height:20px;
		margin-top:10px;
		margin-bottom:10px;
}
.planes{
	height: 2%;
	width: 12%;
	float: right;
}

.vessels{
	height: 2%;
	width: 12%;
	float: right;
}

.player{
	height: 2%;
	width: 12%;
	float: right;
}

.grid{
	height: 2%;
	width: 12%;
	float: right;
}

.friend{
	height: 2%;
	width: 12%;
	float: right;
}

ul{
	list-style: none;
}

body{
	background-color:#FFF;
}

h1{
	color:#000;
}

</style>

	<!--HTML CODE-->
	<!-- button html, more found at: https://stackoverflow.com/questions/42828187/how-to-take-a-screenshot-in-html-with-javascript -->
	<button onclick="takeScreenShot()">Where have I been?</button>
	<div id="map-canvas">
	</div>

	<div id = "main">
		<div id = "map-canvas"></div>
		<div id = "legends">
			<h1 id = "key" style = "font-size:15px; text-align: center"><b>Legend</b></h1>
			<h1 style = "font-size:12px; text-align: left"><b>Own Drone:</b><img src = "mydrone.png" class = "player"/></h1>
			<h1 style = "font-size:12px; text-align: left"><b>Aircraft:</b><img src = "flight.png" class = "planes"/></h1>
			<!-- <h1 style = "font-size:12px; text-align: left"><b>Vessel:</b><img src = "vessel.png" class = "vessels"/></h1> -->
			<h1 style = "font-size:12px; text-align: left"><b>Friend Drone:</b><img src = "friendDrone.png" class = "friend"/></h1>
			<h1 style = "font-size:12px; text-align: left"><b>Restricted Zone:</b><img src = "red square.png" class = "grid"/></h1>
		</div>
		<div id = "objstats">
			<h3 id = "object" style = "font-size:15px;"><b>Selected Object Stats</b></h3>
			<div id = "ObjLat" class="col-xs-4" style = "font-size:12px"></div>
			<div id = "ObjLong" class="col-xs-4" style = "font-size:12px"></div>
			<div id = "ObjAlt" class="col-xs-4" style = "font-size:12px"></div>
			<div id = "ObjVel" class="col-xs-4" style = "font-size:12px"></div>
			<div id = "ObjAccel" class="col-xs-4" style = "font-size:12px"></div>
			<div id = "ObjID" class="col-xs-4" style = "font-size:12px"></div>
		</div>
		<div id = "dronestats">
			<h1 id = "owndrone" style = "font-size:15px;"><b>OwnDrone Stats</b></h1>
			<div id = "DroneLat" class="col-xs-4" style = "font-size:12px"></div>
			<div id = "DroneLong" class="col-xs-4" style = "font-size:12px"></div>
			<div id = "DroneAlt" class="col-xs-4" style = "font-size:12px"></div>
			<div id = "DroneVel" class="col-xs-4" style = "font-size:12px"></div>
			<div id = "DroneAccel" class="col-xs-4" style = "font-size:12px"></div>
			<div id = "DroneID" class="col-xs-4" style = "font-size:12px"></div>
		</div>
	</div>

	<!--JAVASCRIPT CODE-->
	<script>
		window.lat = 28.059489;
		window.lng = -82.412234;
		var lat2 = 28.078300;
		var lng2 = -82.569800;
		var friendlat;
		var friendlng;
		var UPDATE_INTERVAL = 2500;
		var map;
		var legend;
		var mymark;
		var lineCoords = [];
		var markers = [];
		var lines = [];
		var dronelines = [];
		var table = [];
		var table1 = [];
		var t = 0;
		var w = 0;
		var numplanes = 0;
		var polys = [];
		var dir;
		var table2 = [];
		//var vesselmark;
		//var vessels = [];
		
		var prevDronePos = [];
		var droneIndex = 0;
		var firstIter = 0;
		var droneLineShow = 1;
		
		var droneVelocity = 0;
		var velFirstTimeLock = 0;
		
		var droneAccelArray = [];
		var droneAcceleration = 0;
		var droneAccelIndex = 0;

		prevDronePos.push(28.059489);
		prevDronePos.push(-82.412234);

		droneAccelArray.push(droneAcceleration);
		droneAccelIndex++;

//----------------------------- SCREEN SHOT FUNCTION -------------------------------------------
		var takeScreenShot = function() {
			html2canvas(document.getElementById("map-canvas"), {
				onrendered: function (canvas) {
						var tempcanvas=document.createElement('canvas');
						tempcanvas.width=350;
						tempcanvas.height=350;
						var context=tempcanvas.getContext('2d');
						context.drawImage(canvas,112,0,288,200,0,0,350,350);
						var link=document.createElement("a");
						link.href=tempcanvas.toDataURL('image/jpg'); 
						link.download = 'screenshot.jpg';
						link.click();
				}
			});
		}

//----------------------------------------------------------------------------------------------
		function displayObjStats(marker)
		{
			var divLat = document.getElementById('ObjLat');
			var divLong = document.getElementById('ObjLong');
			var divAlt = document.getElementById('ObjAlt');
			var NS, EW;
				
			if(marker.lat() > 0)
			{
				NS = 'N';
			}
			else
			{
				NS = 'S';
			}
			if(marker.lng() > 0)
			{
				EW = 'E';
			}
			else
			{
				EW = 'W';
			}
			divLat.innerHTML = "Latitude: " + Math.abs(marker.lat().toFixed(5)) + "&deg " + NS;
			divLong.innerHTML = "Longitude: " + Math.abs(marker.lng().toFixed(5)) + "&deg " + EW;
		}

		function displayDroneLocation(marker)
		{
			var divLat = document.getElementById('DroneLat');
			var divLong = document.getElementById('DroneLong');
			var divAlt = document.getElementById('DroneAlt');
			 
			if(marker.lat() > 0)
			{
				NS = 'N';
			}
			else
			{
				NS = 'S';
			}
			if(marker.lng() > 0)
			{
				EW = 'E';
			}
			else
			{
				EW = 'W';
			}
			divLat.innerHTML = "Latitude: " + Math.abs(marker.lat().toFixed(5)) + "&deg " + NS;
			divLong.innerHTML = "Longitude: " + Math.abs(marker.lng().toFixed(5)) + "&deg " + EW;
		}
	/*  I don't think this is used DD 3-21
		function getRandomArbitrary(min, max)
		{
			return Math.random() * (max - min) + min;
		}

		function removeMarkers()
		{
			for(var i=markers.length - 1; i >= 0 ; i--)
			{
				markers[i].setMap(null);
				markers.length--;
			}
		}
	*/
		function removeDot()
		{
			var k, g;

			for(var k = 0; k < (table.length - 1); k++)
			{
				if(lines[0])
				{
					lines[0].setMap(null);
					lines[0] = null;
					g = lines.shift();
				}
			}
		}
//------------------------------ Velocity and acceleration functions -------------------------------------------------------------------------------------------------
		// current velocity function - output is too small 
		function getDroneVelocity(lat, lng, prevLat, prevLng)
		{
			var newLat = lat - prevLat;
			var newLng = lng - prevLng;
			var R = 6371000; // Radius of the earth in m
			var dLat = deg2rad(newLat);
			var dLon = deg2rad(newLng);
			var a = Math.sin(dLat/2) * Math.sin(dLat/2) + Math.cos(deg2rad(prevLat)) * Math.cos(deg2rad(lat)) * Math.sin(dLon/2) * Math.sin(dLon/2);
			var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
			var d = R * c; // distance in m
			var s = UPDATE_INTERVAL / 1000;
			var vel = d/s;
			droneAccelArray.push(vel);
			return vel; // m/s
		}

		function getDroneAcceleration()
		{
			return (droneAccelArray[droneAccelIndex] - droneAccelArray[droneAccelIndex - 1]) / (UPDATE_INTERVAL / 1000); // m/s^2
		}
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		function droneLineToggle()
		{
			if(droneLineShow == 1)
			{
				droneLineShow = 0;
				for(var i = 0; i < dronelines.length; i++)
				{
					dronelines[i].setMap(null);
				}
			}
			else
			{
				droneLineShow = 1;
				for(var i = 0; i < dronelines.length; i++)
					dronelines[i].setMap(map);
			}
		}

		function getDistanceFromLatLonInKm(lat1,lon1,lat2,lon2, alt1, alt2)
		{
			var R = 6371; // Radius of the earth in km
			var dLat = deg2rad(lat2-lat1);  // deg2rad below
			var dLon = deg2rad(lon2-lon1);
			var a = Math.sin(dLat/2) * Math.sin(dLat/2) + Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * Math.sin(dLon/2) * Math.sin(dLon/2);
			var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
			var d = R * c; // Distance in km
			return d;
		}

		function deg2rad(deg)
		{
			return deg * Math.PI/180;
		}

		function latToDecMins(lat)
		{
			// get the decimal part of the number only
			var dec_lat = lat - Math.floor(lat);

			// get the number without the decimal

			// multiply decimal by 60 to get minutes
			var lat_minutes = (60 * dec_lat).toFixed(5);
			if(lat > 0)
			{
				dir = 'N';
				var num_lat = Math.abs(Math.floor(lat));
			}
			else
			{
				dir = 'S';
				var num_lat = Math.abs(Math.floor(lat)+1);
			}
			// store and return result
			var result = num_lat + "&deg" + lat_minutes + "&acute " + dir;
			return result;
	 }

	 function lonToDecMins(lon)
	 {
			var dec_lon = lon - Math.floor(lon);
			var lon_minutes = (60 * dec_lon).toFixed(5);
			if(lon > 0)
			{
				dir = 'E';
				var num_lon = Math.floor(lon);
			}
			else
			{
				dir = 'W';
				var num_lon = Math.abs(Math.floor(lon)+1);
			}
			var result = num_lon + "&deg" + lon_minutes + "&acute " + dir;
			return result;
	 }

	 function latToMinSecs(lat)
	 {
			// same steps as converting to decimal minutes
			var dec_lat = lat - Math.floor(lat);
			var lat_minutes = 60 * dec_lat;

			// separate number from decimal of minutes
			var num_lat_minutes = Math.floor(lat_minutes);

			// separate decimal from number of minutes
			var dec_lat_minutes = lat_minutes - Math.floor(lat_minutes);

			// multiply minutes decimal by 60 to get seconds
			var lat_seconds = Math.floor(60 * dec_lat_minutes);
			if(lat > 0)
			{
				dir = 'N';
				var num_lat = Math.abs(Math.floor(lat));
			}
			else
			{
				dir = 'S';
				var num_lat = Math.abs(Math.floor(lat)+1);
			}
			var result = num_lat + "&deg " + num_lat_minutes + "&acute " + lat_seconds + "&quot " + dir;
			return result;
	 }

	function lonToMinSecs(lon)
	{
		var dec_lon = lat - Math.floor(lon);
		var lon_minutes = 60 * dec_lon;

		var num_lon_minutes = Math.floor(lon_minutes);
		var dec_lon_minutes = lon_minutes - Math.floor(lon_minutes);
		var lon_seconds = Math.floor(60 * dec_lon_minutes);
		if(lon > 0)
		{
			dir = 'E';
			var num_lon = Math.abs(Math.floor(lon));
		}
		else
		{
			dir = 'W';
			var num_lon = Math.abs(Math.floor(lon)+1);
		}
		var result = num_lon + "&deg " + num_lon_minutes + "&acute " + lon_seconds + "&quot " + dir;
		return result;
	}

		var initialize = function()
		{
			map  = new google.maps.Map(document.getElementById('map-canvas'), {center:{lat:lat,lng:lng},zoom:18});
			lineCoords.push(new google.maps.LatLng(window.lat, window.lng));
			legends = document.getElementById('legends')
			map.controls[google.maps.ControlPosition.LEFT_TOP].push(legends);
			objstats = document.getElementById('objstats')
			map.controls[google.maps.ControlPosition.LEFT_BOTTOM].push(objstats);
			dronestats = document.getElementById('dronestats')
			map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(dronestats);
		};

		window.initialize = initialize;

		var redraw = function(payload)
		{
			removeMarkers();

			lat = payload.message.lat;
			lng = payload.message.lng;

			friendlat = payload.message.lat2;
			friendlng = payload.message.lng2;

			if( t == 0)
			{
				var array1 = [];
				$.ajax({
					url: 'server.php',
					type: 'post',
					data: 'fetch1',
					success: function(array1)
					{
						table1 = JSON.parse(array1);
						var y;
						var h;

						for (y = 0; y < table1.length; y++)
						{
							if(table1[y]["height"] == 400)
							{
								h = 0.8;
							}
							else if(table1[y]["height"] < 400 && table1[y]["height"] > 0)
							{
								h = table1[y]["height"]/1000 + .4;
							}
							else
							{
								h = 0.35;
							}
							poly = new google.maps.Polygon({
								paths:[{lat:parseFloat(table1[y]["latitude"]+.01664),lng:parseFloat(table1[y]["longitude"]+.01664)}, {lat:parseFloat(table1[y]["latitude"]-.01664),lng:parseFloat(table1[y]["longitude"]+.01664)},
											 {lat:parseFloat(table1[y]["latitude"]-.01664),lng:parseFloat(table1[y]["longitude"]-.01664)},
											 {lat:parseFloat(table1[y]["latitude"]+.01664),lng:parseFloat(table1[y]["longitude"]-.01664)}],
												strokeColor: '#dd5151',
												strokeOpacity: 0.5,
												strokeWeight: 1,
												fillColor: '#dd5151',
												fillOpacity: h,
												map:map
							});
							polys.push(poly);
						}
					}
				});
				var array2 = [];
				$.ajax({
					url: 'server.php',
					type: 'post',
					data: 'fetch2',
					success: function(array2)
					{
						table2 = JSON.parse(array2);
						//console.log(table2);
						var z;
						for (z = 0; z < table2.length; z++)
						{
							var boat_icon =
							{
								path: 'M-0.4975017309188843,22.530683517456055 16.04547393321991,22.530683517456055 16.04547393321991,37.65946388244629 17.88358199596405,37.65946388244629 32.58845794200897,7.401884078979492 -0.4975017309188843,20.849687576293945z',
								fillOpacity: 1.0,
								strokeColor: '#000000',
								fillColor: '#228B22',
								rotation: parseFloat(table2[z]["heading"]) % 360.0,
								scale: .4
							}

							vesselmark = new google.maps.Marker({
								position:
								{
									lat:parseFloat(table2[z]["latitude"]),
									lng:parseFloat(table2[z]["longitude"])
								},
								map:map,
								icon:boat_icon,
								clickable: true
							});

							var velocityVes = parseFloat(table2[z]["speed"]);
							var shipID = table2[z]["ID"]
							
							var stringVelVes = velocityVes.toString();

							var infowindowVesVel = [];
							infowindowVesVel[z] = new google.maps.InfoWindow({
								content: "Velocity: " + stringVelVes
							});

							var infowindowVesCall = [];
							infowindowVesCall[z] = new google.maps.InfoWindow({
								content: "Ship ID: " + shipID
							});

							google.maps.event.addListener(vesselmark, 'click', (function(vesselmark, infowindowVesVel, infowindowVesCall) {
								return function(){
									displayObjStats(vesselmark.getPosition());
									infowindowVesVel.setContent(infowindowVesVel.content);
									infowindowVesVel.open(map, vesselmark);
									document.getElementById('ObjVel').innerHTML = infowindowVesVel.content;
									infowindowVesCall.setContent(infowindowVesCall.content);
									infowindowVesCall.open(map, vesselmark);
									document.getElementById('ObjID').innerHTML = infowindowVesCall.content;
								}
							})(vesselmark, infowindowVesVel[z], infowindowVesCall[z]));
							vessels.push(vesselmark);
						}
					}
				});
				t = 1;
			}

			$.ajax({
				url: 'server.php',
				type: 'post',
				data: 'fetch',
				success: function(array)
				{
					table = JSON.parse(array);
				}
			});

			var row;

			numplanes = table.length;

			for (row = 0; row < table.length; row++)
			{
				mymark = new google.maps.Marker({
					position:
					{
						lat:lat,
						lng:lng
					},
					map:map,
					icon:'mydrone.png'
				});
				markers.push(mymark);
				var infowindowDeg = [];
				latToDecMinsString = latToDecMins(mymark.getPosition().lat()).toString();
				lonToDecMinsString = lonToDecMins(mymark.getPosition().lng()).toString();
	
				infowindowDeg[row] = new google.maps.InfoWindow({
					content: "Degree Min Dec: " + latToDecMinsString + ", " + lonToDecMinsString
				});

				google.maps.event.addListener(mymark, 'click', (function(mymark, infowindowDeg) {
					return function(){
						displayDroneLocation(mymark.getPosition());
						infowindowDeg.setContent(infowindowDeg.content);
						infowindowDeg.open(map, mymark);
					}
				})(mymark, infowindowDeg[row]));

				friendmark = new google.maps.Marker({
					position:
					{
						lat:friendlat,
						lng:friendlng
					},
					map:map,
					icon:'friendDrone.png'
				});
				markers.push(friendmark);
                console.log(friendmark);
				var infowindowDeg2 = [];
				latToDecMinsString = latToDecMins(friendmark.getPosition().lat()).toString();
				lonToDecMinsString = lonToDecMins(friendmark.getPosition().lng()).toString();
	
				infowindowDeg2[row] = new google.maps.InfoWindow({
					content: "Degree Min Dec: " + latToDecMinsString + ", " + lonToDecMinsString
				});

				google.maps.event.addListener(friendmark, 'click', (function(friendmark, infowindowDeg2) {
					return function(){
						displayDroneLocation(friendmark.getPosition());
						infowindowDeg2.setContent(infowindowDeg2.content);
						infowindowDeg2.open(map, friendmark);
					}
				})(friendmark, infowindowDeg2[row]));

				var my_icon =
				{
					path: 'M30.980951,21.574887l0,-1.545626l-11.860449,-7.602415l0.269827,-7.10736c-0.135988,-3.726016 -2.736477,-4.319494 -3.115871,-4.319494s-2.948816,0.783869 -2.911854,4.319494l0.269827,7.10736l-11.860449,7.602351l0,1.545689l12.12882,-2.078516l0.30873,8.132174l-4.429014,2.83893l0,0.698101l4.81465,0c0.347009,0.543244 0.976674,0.918274 1.708972,0.941858c0.786319,0.025373 1.48533,-0.357391 1.857304,-0.941858l4.810905,0l0,-0.698101l-4.428945,-2.83893l0.308661,-8.132174l12.128889,2.078516l-0.000003,0.000001z',
					fillOpacity: 1.0,
					rotation: parseFloat(table[row]["heading"]),
					scale: .60
				}
				mark = new google.maps.Marker({
					position:
					{
						lat:parseFloat(table[row]["latitude"]),
						lng:parseFloat(table[row]["longitude"]),
					},
					map:map,
					icon:my_icon,
					title: table[row]["icao24"],
					label: table[row]["callsign"],
					clickable: true
				});

				var velocity, altitude, tailnumber;
				var icao = mark.title;

				if (table[row]["icao24"] == icao)
				{
					velocity = parseFloat(table[row]["velocity"]);
					altitude = parseFloat(table[row]["geo_altitude"]);
					tailnumber = table[row]["callsign"];
				}

				var stringVel = velocity.toString();
				var stringAlt = altitude.toString();

				var infowindowVel = [];
				infowindowVel[row] = new google.maps.InfoWindow({
					content: "Velocity: " + stringVel
				});

				var infowindowAlt = [];
				infowindowAlt[row] = new google.maps.InfoWindow({
					content: "Altitude: " + stringAlt
				});

				var infowindowCall = [];
				infowindowCall[row] = new google.maps.InfoWindow({
					content: "Tail Number: " + tailnumber
				});

				//Not working for some reason
				if(altitude == null)
				{
					altitude = 0;
				}

				var infowindowDegForAir = [];
				//latToDecMinsString = latToDecMins(mark.getPosition().lat()).toString();
				//lonToDecMinsString = lonToDecMins(mark.getPosition().lng()).toString();
				latToMinSecsString = latToMinSecs(mark.getPosition().lat()).toString();
				lonToMinSecsString = lonToMinSecs(mark.getPosition().lng()).toString();
				infowindowDegForAir[row] = new google.maps.InfoWindow({
					content: "Degree Min Sec: " + latToMinSecsString + ", " + lonToMinSecsString
				});

				google.maps.event.addListener(mark, 'click', (function(mark, infowindowDegForAir, infowindowVel, infowindowAlt, infowindowCall) {
					return function(){
						displayObjStats(mark.getPosition());
						infowindowVel.open(map, mark);
						document.getElementById('ObjVel').innerHTML = infowindowVel.content;
						infowindowAlt.setContent(infowindowAlt.content);
						infowindowAlt.open(map, mark);
						document.getElementById('ObjAlt').innerHTML = infowindowAlt.content;
						infowindowCall.setContent(infowindowCall.content);
						infowindowCall.open(map, mark);
						document.getElementById('ObjID').innerHTML = infowindowCall.content;
						infowindowDegForAir.setContent(infowindowDegForAir.content);
						infowindowDegForAir.open(map, mark);
						infowindowVel.setContent(infowindowVel.content);
					}
				})(mark, infowindowDegForAir[row], infowindowVel[row],infowindowAlt[row],infowindowCall[row]));

				var line = new google.maps.Polyline({
					path:[
						{lat:parseFloat(table[row]["latitude"]),lng:parseFloat(table[row]["longitude"])},
						{lat:parseFloat(table[row]["latitude"] + .02 ),lng:parseFloat(table[row]["longitude"] + .02)}
					],
					map:map
				});

// ------------------------------------ DRONE LINE ---------------------------------------------------------------------------------------------------------------------------------------
				var droneline = new google.maps.Polyline({
					path:[
						{lat:mymark.getPosition().lat(), lng:mymark.getPosition().lng()},
						{lat:prevDronePos[droneIndex], lng:prevDronePos[droneIndex+1]}
					],
					strokeColor: '#FF0000',
					strokeOpacity: 1.0,
					strokeWeight: 2,
					map:map
				});
				if(droneLineShow == 0)
				{
					droneline.setMap(null);
				}
				dronelines.push(droneline);
				droneIndex+=2;
				prevDronePos.push(lat);
				prevDronePos.push(lng);

				lines.push(line);
				markers.push(mark);

				var d = getDistanceFromLatLonInKm(mymark.getPosition().lat(),mymark.getPosition().lng(),parseFloat(table[row]["latitude"]),parseFloat(table[row]["longitude"]), parseFloat(table[row]["geo_altitude"]), 0);

				if (d < 1)
				{
					alert("Warning: Approaching restricted zone");
				}
			}

//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
			
	  if(velFirstTimeLock == 0)
        velFirstTimeLock = 1;
      /*else
      {
        droneVelocity = getDroneVelocity(mymark.getPosition().lat(), mymark.getPosition().lng(), prevDronePos[droneIndex], prevDronePos[droneIndex+1]);
        console.log("Velocity of drone: " + droneVelocity);
        
        droneAcceleration = getDroneAcceleration();
      	console.log("Acceleration of drone: " + droneAcceleration);
      	droneAccelIndex++;
      }*/

//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
			w += 1;

			if(w > 10)
			{
				removeDot();
			}
		};

		var pnChannel = "map-channel";

		var pubnub = new PubNub({
			publishKey: 'pub-c-e0bb1b7d-cdac-4257-9971-f15269d589c7',
			subscribeKey: 'sub-c-0082b83c-d466-11e7-aee1-6e8e9d2d00b1'
		});

		pubnub.subscribe({channels: [pnChannel]});
		pubnub.addListener({message:redraw});

		setInterval(function()
		{
			pubnub.publish({channel:pnChannel, message:{lat:window.lat + getRandomArbitrary(-.0007, .0005), lng:window.lng + getRandomArbitrary(-.0006, .0005), lat2:lat2 + getRandomArbitrary(-.0007, .0005), lng2:lng2 + getRandomArbitrary(-.006, .005)}});
		}, UPDATE_INTERVAL);

	</script>
	<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyC0vqbMMyzdBgqQvproOxEq-BjQH7b2cjk&callback=initialize"></script>
</body>
</html>
