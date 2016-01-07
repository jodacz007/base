<html>
<head>
	<title></title>
	<script src="{{URL::to('/')}}/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
</head>
<body>
    <script>
        jQuery(document).ready(function() {       
		    if (navigator.geolocation) {
		        navigator.geolocation.getCurrentPosition(showPosition);
		    } else {
		        var loc = "Geolocation is not supported by this browser.";
		        console.log(loc);
		    }
			function showPosition(position) {
			    var loc = "Latitude: " + position.coords.latitude + 
			    "\nLongitude: " + position.coords.longitude;
			    console.log(loc);
			}
        });
    </script>
</body>
</html>