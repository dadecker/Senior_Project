<?php
	function fetch()
	{
		$link = mysqli_connect("localhost", "root", "Southflorida8", "opensky");
		if ($result = mysqli_query($link, "SELECT * FROM aircraft"))
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
		$link = mysqli_connect("localhost", "root", "Southflorida8", "opensky");
		if ($result = mysqli_query($link, "SELECT * FROM buildings"))
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

	if (isset($_POST['fetch']))
	{
		echo fetch();
	}

	if (isset($_POST['fetch1']))
	{
		echo fetch1();
	}
	
?>