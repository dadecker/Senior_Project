<?php
	function fetch()
	{
		$link = mysqli_connect("131.247.3.209:10060", "User1", "1rm@", "aircrafts");
		if ($result = mysqli_query($link, "SELECT * FROM opensky"))
		{
			if (!mysqli_query($link, "SET @a:='this will not work'")) 
			{
        		printf("Error: %s\n", mysqli_error($link));
    		}

    		$array = mysqli_fetch_all($result, MYSQLI_ASSOC);
    		mysqli_free_result($result);

    		return json_encode($array);
		}
	}

	function fetch1()
	{
		$link = mysqli_connect("131.247.3.209:10060", "User1", "1rm@", "buildings");
		if ($result = mysqli_query($link, "SELECT * FROM areas"))
		{
			if (!mysqli_query($link, "SET @a:='this will not work'")) 
			{
        		printf("Error: %s\n", mysqli_error($link));
    		}

    		$array1 = mysqli_fetch_all($result, MYSQLI_ASSOC);
    		mysqli_free_result($result);

    		return json_encode($array1);
		}
	}
	function fetch2(){

		$link = mysqli_connect("131.247.3.209:10060", "User1", "1rm@", "test");
		if ($result = mysqli_query($link, "SELECT * FROM vessels")){


			if (!mysqli_query($link, "SET @a:='this will not work'")) {
        			printf("Error: %s\n", mysqli_error($link));
    		}

    		$array = mysqli_fetch_all($result, MYSQLI_ASSOC);
    		mysqli_free_result($result);

    		
    		return json_encode($array);
		}
	}

	if (isset($_POST['fetch']))
	{
		echo fetch();
	}

	if (isset($_POST['fetch1']))
	{
		echo fetch1();
	}
	
    if (isset($_POST['fetch2']))
    {

		echo fetch2();
	}
?>