
<script src="uploadify/jquery.uploadify.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="uploadify/uploadify.css">

<div class="modal fade fill-in" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="modalFormLabel" aria-hidden="true">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
    <i class="pg-close"></i>
</button>
<div class="modal-dialog ">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="text-left p-b-5"><span class="semi-bold">วันนี้กิน...<span></h2>
        </div>
        <form>
        <div class="modal-body">
           <div class="form-group">
            <div id="queue"></div>
            <input type="file" id="file_upload" name="file_upload" multiple="false">
          </div>
          <div class="form-group">
            <input type="text" class="form-control" id="name" name="name"  placeholder="ชื่ออาหาร">
          </div>
          <div class="form-group">
             <input type="text" class="form-control" id="price" name="price" placeholder="ราคา">
          </div>
          <div class="form-group">
            <input type="text" class="form-control" id="location" name="location" placeholder="สถานที่">
            <div class="col-xs-6 col-md-4">
              <input type="hidden" id="lat" name="lat" value="">
              <input type="hidden" id="lng" name="lng" value="">
              <span id="my-location"></span>
            </div>
          </div>
         </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary"> โพสเลย </button>
        </div>
        </form>

    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>



<script>

  $( document ).ready(function () {
    getLocation();

    $('#file_upload').uploadify({
      'formData'     : {
        'timestamp' : '<?php echo $timestamp;?>',
        'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
      },
      'swf'      : 'uploadify/uploadify.swf',
      'uploader' : 'uploadify/uploadify.php',
      'fileTypeDesc' : 'Image Files',
      'fileTypeExts' : '*.gif; *.jpg; *.png',
      'fileSizeLimit' : '1024KB',
      'buttonClass'  : 'btn btn-warning',
      'buttonText'  : 'เลือกรูปภาพ',
      'width'     : 160,
      'height'     : 35

    });

  });

  var x = document.getElementById("my-location");
  function getLocation()
  {
      if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(showPosition);

      } else {
          x.innerHTML = "Geolocation is not supported by this browser.";
      }
  }

  function showPosition(position)
  {
      var lat = position.coords.latitude;
      var lng = position.coords.longitude;
      $("#lat").val(lat);
      $("#lng").val(lng);
      x.innerHTML = "" + lat + "/" + lng + "<br>";
  }


</script>
