
<html>
<head>
<title>DroneZone</title>
    <script src="https://cdn.pubnub.com/sdk/javascript/pubnub.4.4.1.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" />
</head>
<body>

<!--CSS CODE-->
<style type = "text/css">

#main{
  margin:auto;
}

#top{
  border:3px solid darkred;
  background-color:#F0F0F0;
  height:14%;
  width:100%;
}

#down{
  border:3px solid darkred;
  background-color:#F0F0F0;
  height:14%;
  width:100%;
}

#left{
  border:3px solid darkred;
  background-color:#F0F0F0;
  height:70%;
  width:12.5%;
  float:left;
}

#right{
  border:3px solid darkred;
  background-color:#F0F0F0;
  height:70%;
  width:12.5%;
  float:right;
}

#map-canvas{
  margin:auto;
  border:3px solid darkred;
  height:70%;
  width:75%;
}

#object{
  text-align: center;
  width:100%;
}

#owndrone{
  text-align: center;
  width:100%;
}

#legends{
  text-align: center;
  width:100%;
}

#chat{
  text-align: center;
  width:100%;
}

#ObjLat{
  width:33%;
  height:30%;
}

#ObjLong{
  width:40%;
  height:30%;
}

#ObjAlt{
  width:26%;
  height:30%;
}

#ObjVel{
  width:33%;
  height:30%;
}

#ObjAccel{
  width:40%;
  height:30%;
}

#ObjID{
  width:26%;
  height:30%;
}

#DroneLat{
  width:33%;
  height:30%;
}

#DroneLong{
  width:40%;
  height:30%;
}

#DroneAlt{
  width:26%;
  height:30%;
}

#DroneVel{
  width:33%;
  height:30%;
}

#DroneAccel{
  width:40%;
  height:30%;
}

#DroneID{
  width:26%;
  height:30%;
}

.planes{
  height:10%;
  width: 25%;
  display: block;
  margin:0 auto;
}

.vessels{
  height:10%;
  width: 25%;
  display: block;
  margin:0 auto;
}

.player{
  height:10%;
  width: 25%;
  display: block;
  margin:0 auto;
}

.grid{
  height:25%%;
  width: 25%;
  display: block;
  margin:0 auto;
}

.friend{
  height:10%;
  width: 25%;
  display: block;
  margin:0 auto;
}

body{
  background-color:#333333;
}

h1{
  color:#C01120;
}

</style>

  <!--HTML CODE-->

  <div id = "main">
    <div id = "top">
      <h1 id = "object" style = "font-size:20px;"><b>Selected Object Stats</b></h1>
      <div id = "ObjLat" class="col-xs-4" style = "font-size:20px"></div>
      <div id = "ObjLong" class="col-xs-4" style = "font-size:20px"></div>
      <div id = "ObjAlt" class="col-xs-4" style = "font-size:20px"></div>
      <div id = "ObjVel" class="col-xs-4" style = "font-size:20px"></div>
      <div id = "ObjAccel" class="col-xs-4" style = "font-size:20px"></div>
      <div id = "ObjID" class="col-xs-4" style = "font-size:20px"></div>
    </div>
    <div id = "left">
      <h1 id = "legends" style = "font-size:20px;"><b>Legend</b></h1>
        <h1 style = "font-size:15px; text-align: center"><b>Own Drone</b></h1>
        <img src = "mydrone.png" class = "player"/>
        <h1 style = "font-size:15px; text-align: center"><b>Aircraft</b></h1>
        <img src = "flight.png" class = "planes"/>
        <h1 style = "font-size:15px; text-align: center"><b>Vessel</b></h1>
        <img src = "vessels.png" class = "vessels"/>
        <h1 style = "font-size:15px; text-align: center"><b>Friend Drone</b></h1>
        <img src = "friendDrone.png" class = "friend"/>
        <h1 style = "font-size:15px; text-align: center"><b>Restricted Zone</b></h1>
        <img src = "red square.png" class = "grid"/>
    </div>
    <div id = "right">
      <h1 id = "chat" style = "font-size:20px;"><b>Chat</b></h1>
    </div>
    <div id = "map-canvas"></div>
    <div id = "down">
      <h1 id = "owndrone" style = "font-size:20px;"><b>OwnDrone Stats</b></h1>
      <div id = "DroneLat" class="col-xs-4" style = "font-size:20px"></div>
      <div id = "DroneLong" class="col-xs-4" style = "font-size:20px"></div>
      <div id = "DroneAlt" class="col-xs-4" style = "font-size:20px"></div>
      <div id = "DroneVel" class="col-xs-4" style = "font-size:20px"></div>
      <div id = "DroneAccel" class="col-xs-4" style = "font-size:20px"></div>
      <div id = "DroneID" class="col-xs-4" style = "font-size:20px"></div>
    </div>
  </div>

  <!--JAVASCRIPT CODE-->
  <script>

    window.lat = 28.059489;
    window.lng = -82.412234;
    var map;
    var mymark;
    var lineCoords = [];
    var markers = [];
    var lines = [];
    var table = [];
    var table1 = [];
    var t = 0;
    var w = 0;
    var numplanes = 0;
    var polys = [];
    var dir = ['N','S','E','W'];
      
    function displayObjStats(marker)
    {
      var divLat = document.getElementById('ObjLat');
      var divLong = document.getElementById('ObjLong');

      divLat.innerHTML = "Latitude: " + marker.lat().toFixed(5) + "&deg";
      divLong.innerHTML = "Longitude: " + marker.lng().toFixed(5) + "&deg";
    }

    function displayDroneLocation(marker)
    {
      var divLat = document.getElementById('DroneLat');
      var divLong = document.getElementById('DroneLong');

      divLat.innerHTML = "Latitude: " + marker.lat().toFixed(5) + "&deg";
      divLong.innerHTML = "Longitude: " + marker.lng().toFixed(5) + "&deg";
    }

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

    function getDistanceFromLatLonInKm(lat1,lon1,lat2,lon2)
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
      return deg * (Math.PI/180)
    }

    function latToDecMins(lat)
    {
     // get the decimal part of the number only
     var dec_lat = lat - Math.floor(lat);

     // get the number without the decimal
     var num_lat = Math.floor(lat);

     // multiply decimal by 60 to get minutes
     var lat_minutes = (60 * dec_lat).toFixed(5);

     // store and return result
     var result = num_lat + "&deg" + lat_minutes + "&acute";
     return result;
   }

   function lonToDecMins(lon)
   {
     var dec_lon = lon - Math.floor(lon);
     var num_lon = Math.floor(lon);
     var lon_minutes = (60 * dec_lon).toFixed(5);
     var result = num_lon + "&deg" + lon_minutes + "&acute";
     return result;
   }

   function latToMinSecs(lat)
   {
     // same steps as converting to decimal minutes
     var dec_lat = lat - Math.floor(lat);
     var num_lat = Math.floor(lat);
     var lat_minutes = 60 * dec_lat;

     // separate number from decimal of minutes
     var num_lat_minutes = Math.floor(lat_minutes);

     // separate decimal from number of minutes
     var dec_lat_minutes = lat_minutes - Math.floor(lat_minutes);

     // multiply minutes decimal by 60 to get seconds
     var lat_seconds = Math.floor(60 * dec_lat_minutes);

     var result = num_lat + "&deg " + num_lat_minutes + "&acute " + lat_seconds + "&quot";
     return result;
   }

   function lonToMinSecs(lon)
   {
     var dec_lon = lat - Math.floor(lon);
     var num_lon = Math.floor(lon);
     var lon_minutes = 60 * dec_lon;

     var num_lon_minutes = Math.floor(lon_minutes);
     var dec_lon_minutes = lon_minutes - Math.floor(lon_minutes);
     var lon_seconds = Math.floor(60 * dec_lon_minutes);

     var result = num_lon + "&deg " + num_lon_minutes + "&acute " + lon_seconds + "&quot";
     return result;
   }

   /* function getBuild()
    {
      var array1 = [];
      $.ajax({
        url: 'server.php',
        type: 'post',
        data: 'fetch1',
        success: function(array1)
        {
           table1 = JSON.parse(array1);
        }
      });

      var row;
      var square = "red square.png";

      for (row = 0; row < table1.length; row++)
      {
        mark = new google.maps.Marker({
          position:
          {
            lat:parseFloat(table1[row]["latitude"]),
            lng:parseFloat(table1[row]["longitude"])
          },
          map:map,
          icon:square
        });
        buildmarks.push(mark);
      }
    }*/

    var initialize = function()
    {
      map  = new google.maps.Map(document.getElementById('map-canvas'), {center:{lat:27.988489,lng:-82.452234},zoom:11});
      lineCoords.push(new google.maps.LatLng(window.lat, window.lng));
    };

    window.initialize = initialize;

    var redraw = function(payload)
    {
    	removeMarkers();

      lat = payload.message.lat;
      lng = payload.message.lng;

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
        //latToMinSecsString = latToMinSecs(mymark.getPosition().lat()).toString();
        //lonToMinSecsString = lonToMinSecs(mymark.getPosition().lng()).toString();
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
          label: table[row]["tailnumber"],
          clickable: true
      	});

        var velocity, altitude, tailnumber;
        var icao = mark.title;

        if (table[row]["icao24"] == icao)
        {
          velocity = parseFloat(table[row]["velocity"]);
          altitude = parseFloat(table[row]["altitude"]);
          tailnumber = table[row]["tailnumber"];
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

        lines.push(line);
      	markers.push(mark);

      	var d = getDistanceFromLatLonInKm(mymark.getPosition().lat(),mymark.getPosition().lng(),parseFloat(table[row]["latitude"]),parseFloat(table[row]["longitude"]));

        if (d < 1)
        {
          alert("Warning: Approaching restricted zone");
        }
      }

      w += 1;

      if(w > 10)
      {
        removeDot();
      }
    };

    var pnChannel = "map-channel";

    var pubnub = new PubNub({
      publishKey: 'pub-c-d607d8cb-1244-4d79-b0f7-3a78a7b56806',
      subscribeKey: 'sub-c-e74ca0e0-b5d8-11e7-bcea-b64ad28a6f98'
    });


    pubnub.subscribe({channels: [pnChannel]});
    pubnub.addListener({message:redraw});

    setInterval(function()
    {
    	pubnub.publish({channel:pnChannel, message:{lat:window.lat + getRandomArbitrary(-.007, .005), lng:window.lng + getRandomArbitrary(-.006, .005)}});
    }, 2500);

	</script>
	<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyC0vqbMMyzdBgqQvproOxEq-BjQH7b2cjk&callback=initialize"></script>
</body>
</html>
