
<html>
<head>
<title>DroneZone</title>
    <script src="https://cdn.pubnub.com/sdk/javascript/pubnub.4.4.1.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" />
</head>
<body>
	<div class="container">
      <h1>DroneZone</h1>
      <div id="map-canvas" style="width:600px;height:400px"></div>
    </div>

	<script>

	window.lat = 28.059489;
    window.lng = -82.412234;
    var map;
    var mark;
    var row;
    var lineCoords = [];
    var markers = [];
    var j = 0;
    var table = [];
    var infowindow;
    var content;
    var symbol;
    var latlng;

    //Remove all markers 
    function removeMarkers()
    {
    	for(i=0; i<markers.length; i++)
   	 	{
        	markers[i].setMap(null);
    	}
	}

	//
    var initialize = function() {
      	map  = new google.maps.Map(document.getElementById('map-canvas'), {center:{lat:lat,lng:lng},zoom:8});

      	lineCoords.push(new google.maps.LatLng(window.lat, window.lng));
    };

    window.initialize = initialize;

    //Redraw erasees the icons and redraws them, so like planes
    //and drones. Payload is the parameter of data coming in
    //from the database
    var redraw = function(payload) 
    {
    	removeMarkers();
    	
    	lat = payload.message.lat;
        lng = payload.message.lng;

    	mark = new google.maps.Marker({
    		position:
    		{
    			lat:lat, 
    			lng:lng
    		}, 
    		map:map, 
    		icon: "drone.png"
    	});

    	markers.push(mark);
    	console.log(mark);
    	
    	var array = [];

    	$.ajax({
    		url: 'server.php',
    		type: 'post',
    		data: 'fetch',
    		success: function(array) 
    		{
    			 table = JSON.parse(array);
    		}
    	});
		console.log(table);


      //For loop going through the database of each row within the table
      //parseFloat is making the varchars within the database into 
      //a float
      for (row = 0; row < table.length; row++)
      {	
      		mark = new google.maps.Marker({
      			position:
      			{
      				lat:parseFloat(table[row]["Latitude"]), 
      				lng:parseFloat(table[row]["Longitude"])
      			},
      			map:map, 
      			icon:"flight.png"
      		});
      		markers.push(mark);
      		latlng = new google.maps.LatLng(lat, lng);
       	 	//Need to convert to strings
			infowindow = new google.maps.InfoWindow({
				position:latlng;
			});      	

   			google.maps.event.addListener(mark, 'click', function()
    		{
    			infowindow.getPosition();
    			infowindow.open(map, mark);
    		});
      	}
    };
/*
    function addInfoWindow(marker, lat, lng, content)
    {
    	infoWindow = new google.maps.InfoWindow({
    		content:
    		{
    			lat:parseFloat(lat),
    			lng:parseFloat(lng)
    		}
    	});

   		google.maps.event.addListener(marker, 'click', function()
    	{
    		//infoWindow.setContent("<div style='height:60px;width:200px;'>" + content + "<br>coordinates:" + marker.getposition() + "</div>");
    		map.setCenter(marker.getPosition()); //Center to the object
    		infoWindow.open(map, marker);
    	});
    }*/
    /*
    service.getDetails({
    	place
    },
    	 function(){
    		var infoMarker = new.google.Marker({
    			position:
    			{
      				lat:parseFloat(table[row]["Latitude"]), 
      				lng:parseFloat(table[row]["Longitude"])
    			}
    		})
    	}
    	google.maps.event.addListener(marker, 'click', function() {
    		infowindow.setContent('<div><strong>' + place.name + '</strong><br>' +
                'Place ID: ' + place.place_id + '<br>' +
                place.formatted_address + '</div>');
              infowindow.open(infoMarker, this);
    	});
    })
	*/
    var pnChannel = "map-channel";

    //pubnub keys
    var pubnub = new PubNub
    ({
      	publishKey: 'pub-c-d607d8cb-1244-4d79-b0f7-3a78a7b56806',
      	subscribeKey: 'sub-c-e74ca0e0-b5d8-11e7-bcea-b64ad28a6f98'
    });

    pubnub.subscribe({channels: [pnChannel]});
    pubnub.addListener({message:redraw});
    
    //setInterval is a method that will do pubnub.publish every 2.5 seconds, pubnub sends a message to the
    //channel and causes the redraw
    setInterval(function() 
    {
    	pubnub.publish({channel:pnChannel, message:{lat:window.lat + 0.001, lng:window.lng + 0.001}});
    	j = j + 1;
    }, 2500);

	</script>

	<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyC0vqbMMyzdBgqQvproOxEq-BjQH7b2cjk&callback=initialize"></script>
</body>
</html>