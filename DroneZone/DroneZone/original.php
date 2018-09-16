
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
  border:3px solid red;
  background-color:#999999;
  height:14%;
  width:100%;
}

#down{
  border:3px solid red;
  background-color:#999999;
  height:14%;
  width:100%;
}

#left{
  border:3px solid red;
  background-color:#999999;
  height:70%;
  width:12.5%;
  float:left;
}

#right{
  border:3px solid red;
  background-color:#999999;
  height:70%;
  width:12.5%;
  float:right;
}

#map-canvas{
  margin:auto;
  border:3px solid red;
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
  color:red;
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
    var buildmarks = [];
    var j = 0;
    var table = [];
    var table1 = [];
    var t = 0;
    var w = 0;
    var numplanes = 0;

  
    function displayObjLocation(marker)
    {
      var divLat = document.getElementById('ObjLat');
      var divLong = document.getElementById('ObjLong');

      divLat.innerHTML = "Latitude: " + marker.lat();
      divLong.innerHTML = "Longitude: " + marker.lng();
    }

    function displayDroneLocation(marker)
    {
      var divLat = document.getElementById('DroneLat');
      var divLong = document.getElementById('DroneLong');

      divLat.innerHTML = "Latitude: " + marker.lat();
      divLong.innerHTML = "Longitude: " + marker.lng();
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

    function getBuild()
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
        /*
        var square = new google.maps.MarkerImage(
          'red square.png',
          new google.maps.Size(8,8),
          null,
          null,
          new google.maps.Size(8,8)
        );

        google.maps.event.addListener(map, 'zoom_changed', function() 
        {
          var pixelSize = 8;
          var maxSize = 350;
          var zoom = map.getZoom();
          var relativePixelSize = Math.round(pixelSize*Math.pow(2,zoom));

          if(relativePixelSize > maxSize)
            relativePixelSize = maxSize;

          mark.setIcon(new google.maps.MarkerImage(
            mark.getIcon().url,
            null,
            null,
            null,
            new google.maps.Size(relativePixelSize, relativePixelSize)
          ));
        });*/
        buildmarks.push(mark);
      }
    }

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
            var square = "red square.png";
      
            for (y = 0; y < table1.length; y++)
            {
              mark1 = new google.maps.Marker({
                position:
                {
                  lat:parseFloat(table1[y]["latitude"]), 
                  lng:parseFloat(table1[y]["longitude"])
                },
                map:map,
                icon:square
              });
              buildmarks.push(mark1);
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

        google.maps.event.addListener(mymark, 'click', function(){
          displayDroneLocation(mymark.getPosition());
        });

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
      			lng:parseFloat(table[row]["longitude"])
          },
      		map:map,
      		icon:my_icon
      	});


        var line = new google.maps.Polyline({
          path:[
            {lat:parseFloat(table[row]["latitude"]),lng:parseFloat(table[row]["longitude"])},
            {lat:parseFloat(table[row]["latitude"] + .02 ),lng:parseFloat(table[row]["longitude"] + .02)}     
          ],
          map:map
        });
        
        lines.push(line);
      	markers.push(mark);

        google.maps.event.addListener(mark, 'click', function(){
          displayObjLocation(mark.getPosition());
        });

        var velocity = parseFloat(table[row]["velocity"]);
        var altitude = parseFloat(table[row]["geo_altitude"]);
        var objID = table[row]["callsign"];
        //var acceleration = (velocity - initialVelocity)/1.5;

        //Not working for some reason
        if(altitude == null)
        {
          altitude = 0;
        }

        var divVel = document.getElementById('ObjVel');
        var divAlt = document.getElementById('ObjAlt');
        //var divAccel = document.getElementById('ObjAccel');
        var divID = document.getElementById('ObjID');

        divVel.innerHTML = "Velocity: " + velocity;
        divAlt.innerHTML = "Altitude: " + altitude;
        //divAccel.innerHTML = "Acceleration: " + acceleration;
        divID.innerHTML = "Callsign: " + objID;

      	var d = getDistanceFromLatLonInKm(mymark.getPosition().lat(),mymark.getPosition().lng(),parseFloat(table[row]["latitude"]),parseFloat(table[row]["longitude"]));

        if (d < 1)
        {
          alert("Warning: Approaching restricted zone");
        }
      }

      w += 1;

      if(w > 3)
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