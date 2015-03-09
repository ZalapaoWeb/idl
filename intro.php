<style>
#googlemaps { 
  height: 100%; 
  width: 100%; 
  position:absolute; 
  top: 0; 
  left: 0; 
  z-index: 0; /* Set z-index to 0 as it will be on a layer below the contact form */
}
 
#contactform { 
  position: relative; 
  z-index: 1; /* The z-index should be higher than Google Maps */
  width: 300px;
  margin: 60px auto 0;
  padding: 10px;
  background: black;
  height: auto;
  opacity: .45; /* Set the opacity for a slightly transparent Google Form */ 
  color: white;
}
</style>
<!-- Include the Google Maps API library - required for embedding maps -->
<script src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
 
<script type="text/javascript">
 
// The latitude and longitude of your business / place
var position = [13.7044742,100.5031655];
 
function showGoogleMaps() {
 
    var latLng = new google.maps.LatLng(position[0], position[1]);
 
    var mapOptions = {
        zoom: 16, // initialize zoom level - the max value is 21
        streetViewControl: false, // hide the yellow Street View pegman
        scaleControl: false, // allow users to zoom the Google Map
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        center: latLng
    };
 
    map = new google.maps.Map(document.getElementById('googlemaps'),
        mapOptions);
 
    // Show the default red marker at the location
	/*
    marker = new google.maps.Marker({
        position: latLng,
        map: map,
        draggable: false,
        animation: google.maps.Animation.DROP
    });
	*/
}
 
google.maps.event.addDomListener(window, 'load', showGoogleMaps);
</script>
<div id="googlemaps"></div>
<div class="landing_logo hided"><img src="images/idl-logo.png" alt=""></div>
<a href="<?php echo _path('home');?>" class="landing_enter hided"><img src="assets/img/landing_enter.png" alt=""></a>


</body>
</html>