<?php 
 $timestamp = time();
?>
 
<link rel="stylesheet" type="text/css" href="assets/uploadify/uploadify.css">
<link rel="stylesheet" href="assets/tagEditor/jquery.tag-editor.css">
<script src="assets/uploadify/jquery.uploadify.min.js" type="text/javascript"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js" type="text/javascript"></script>
<script src="assets/tagEditor/jquery.tag-editor.js"></script>
<script src="assets/tagEditor/jquery.caret.min.js"></script>
<style>
.tag-editor {
  list-style-type: none !important;
  padding: 0 5px 0 0 !important;
  margin: 0 !important;
}
.contentarea ul li, .content_area ol li {
   padding:0px !important;
	
}
.contentarea ul li:before {
	content:'' !important;
}
#result-display {
  height: 250px !important;
  background-color: #FFF;
}

.tag-editor .green-tag .tag-editor-tag { color: #45872c !important; background: #e1f3da !important; }
.tag-editor .green-tag .tag-editor-delete { background-color: #e1f3da !important; }
</style>

 <!-- C O N T E N T -->
    <div class="content_wrapper">
    	<div class="content_block no-sidebar">
        	<h1><span>เที่ยงนี้กินอะไร ?</span></h1>
            <div class="fl-container">
                <div class="posts-block">             
                    <div class="contentarea">
                    <div class="col-sm-6">
                    <div class="row" id="result-display" style="display:none">
                        <div class="form-group">
                        	<center>
                            <h3><span>ขอบคุณที่สำหรับไอเดียครับ...</span></h3>
                            <span><a href="<?php echo PRO_URL?>home">กลับหนัาหลัก</a></span>
                        	</center>  
                        </div>                      
                    </div>
                    <div class="row" id="frm-display"> 
                    <form name="postForm" id="postForm" method="post" role="form">
                      <div class="form-group form-group-default ">
                      <div class="featured_image_full" id="preview-img"></div>
                        <input type="file" id="file_upload" class="form-control" >
                        <input type="hidden" name="file_name" id="file_name" value="" />
                      </div>
                      <div class="row">
                        <div class="col-sm-6">
                          <div class="form-group form-group-default ">
                            <label>ชื่อเมนู</label>
                            <input type="text" class="form-control" name="name" required placeholder="ใส่ชื่อเมนูอาหาร" >
                          </div>
                        </div>
                        <div class="col-sm-6">
						  <div class="form-group form-group-default input-group">
							<label>ราคา</label>
							<input type="text" name="price" class="form-control numericOnly">
							<span class="input-group-addon">บาท</span>
						  </div>
                        </div> 
                      </div> 
                      <div class="form-group  form-group-default ">
                        <label>ชื่อร้าน</label>
                        <input type="text" class="form-control" name="shop_name" placeholder="ใส่ชื่อร้าน" required>
                      </div>
                      <div class="form-group  form-group-default">
                        <label>ประเภทอาหาร</label>
                        <textarea id="tags" name="tags" required></textarea>
                      </div>
                      <div class="row">
                        <div class="col-sm-6">
						  <div class="form-group form-group-default disabled">
							<label>Latitude</label>
							<input type="text" class="form-control" name="display-lat" id="display-lat" disabled>
                            <input type="hidden" name="lat" id="lat" value="" />
						  </div>
                        </div> 
                        <div class="col-sm-6">
						  <div class="form-group form-group-default disabled">
							<label>Longitude</label>
							<input type="text" class="form-control" name="display-lng" id="display-lng" disabled>
                            <input type="hidden" name="lng" id="lng" value="" />
						  </div>
                        </div> 
                      </div> 
                     <div class="row">
                     	<input type="hidden" name="action" value="sendPost" />
                     	<button class="btn btn-danger rsu big" type="submit" id="btn-share">แชร์</button>
                     </div>
                    </form><!-- 
    				<script src="assets/pages/js/form_elements.js" type="text/javascript"></script> -->
                    </div>
                    </div>                     
                	</div>
                </div>
                <div class="left-sidebar-block">
                    <!-- Left Sidebar Text -->
                </div><!-- .left-sidebar -->
                <div class="clear"><!-- ClearFix --></div>
            </div><!-- .fl-container -->
            <div class="right-sidebar-block">
            </div><!-- .right-sidebar -->
            <div class="clear"><!-- ClearFix --></div>
        </div>
    </div>
  <script>
  $( document ).ready(function () {
    getLocation();
    $('#file_upload').uploadify({
      'formData'     : {
        'timestamp' : '<?php echo $timestamp;?>',
        'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
      },
      'swf'      : '<?php echo PRO_URL?>assets/uploadify/uploadify.swf',
      'uploader' : '<?php echo PRO_URL?>upload.php',
      'fileTypeDesc' : 'Image Files',
      'fileTypeExts' : '*.gif; *.jpg; *.png',
      'fileSizeLimit' : '1024KB',
      'buttonClass'  : 'btn btn-default',
      'buttonText'  : '<span class="rsu big">เลือกรูปภาพ</span>',
      'width'     : 160,
      'height'     : 35,
	  'onUploadSuccess' : function(file, data, response) {
		  if(data.length>30){
		  		  alert('Wrong File Path');
				  $("#file_upload").show();
		  }else{
			$("#preview-img").html("<img src='tmp/"+data+"' style='width:100%!important;height:auto!important'><div class='featured_image_wrapper' onclick='deletePic('"+data+"');return false;'><a href='#'>x ลบรูปภาพ</a></div>");
			$("#file_name").val(data);
		  }
       },
	   'onUploadComplete' : function(file) {
		   $("#file_upload").hide();
	   }

    });
	
	$('#tags').tagEditor({
       autocomplete: { delay: 0, position: { collision: 'flip' }, source: [<?php echo getTagSugguest()?>] },
       forceLowercase: false,
	   minLength: 3 ,
	   delimiter:',;',
	   clickDelete: true,
       placeholder: '...'
     });
	 
	$(".numericOnly").keypress(function (e) {
    	if (String.fromCharCode(e.keyCode).match(/[^0-9]/g)) return false;
	});
	 

  });
  
  function deletePic(filename)
  {
	  $("#preview-img").html('');
	  $("#file_upload").show();
	  
  }

  function getLocation()
  {
      if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(showPosition);

      }
  }

  function showPosition(position)
  {
      var lat = position.coords.latitude;
      var lng = position.coords.longitude;
      $("#lat, #display-lat").val(lat);
      $("#lng, #display-lng").val(lng);
  }
  
  $("#postForm").validate({
	  rules: {			
		file_name: {
			required: true
		},
		name: "required",	
		shop_name: "required",
		tags: "required",	
		price: {
		  required: true,
		  number: true
		}
	  },
	  messages: {
		file_name: {
			required: "กรุณาเลือกรูปภาพ"
		},
		name: "กรุณาระบุชื่ออาหาร",
		shop_name: "กรุณาระบุชื่อร้าน",
		tags: "กรุณาระบุหมวดหมู่อาหาร",
		price: {
		  required: "กรุณาระบุราคา",
		  number: "ข้อมูลราคาไม่ถูกต้อง"
		}

  	  },
	  submitHandler: function(form) {
		var file = $("#file_name").val();
		if (file =="") {
			return false;
		}
		$.post( "<?php echo PRO_URL?>action_tak.php", $( "#postForm" ).serialize())
		.done(function( data ) {
			if (data) {
				$("#result-display").css('display','').delay(600);
				$("#frm-display").css('display','none').delay(30);
				<!--window.location.href = "<?php echo PRO_URL?>home";-->
			}
		});
  	  }
	});
	
</script>