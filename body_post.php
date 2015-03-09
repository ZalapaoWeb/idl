<?php 
 $timestamp = time();
 if (isset($_POST['action']) && $_POST['action']=='send') {
	 $food_id = saveFoodData($_POST);

	 if ($food_id > 0) {
		$_SESSION['action_save'] = 'success';
		redirect('post');
		exit();
	 }
 }

?>
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
/* color tags */
.tag-editor .red-tag .tag-editor-tag { color: #c65353; background: #ffd7d7; }
.tag-editor .red-tag .tag-editor-delete { background-color: #ffd7d7; }
.tag-editor .green-tag .tag-editor-tag { color: #45872c; background: #e1f3da; }
.tag-editor .green-tag .tag-editor-delete { background-color: #e1f3da; }
</style>
 
<link rel="stylesheet" type="text/css" href="assets/uploadify/uploadify.css">
<link rel="stylesheet" href="assets/tagEditor/jquery.tag-editor.css">
<script src="assets/uploadify/jquery.uploadify.min.js" type="text/javascript"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js" type="text/javascript"></script>
<script src="assets/tagEditor/jquery.tag-editor.js"></script>
<script src="assets/tagEditor/jquery.caret.min.js"></script>

 <!-- C O N T E N T -->
    <div class="content_wrapper">
    	<div class="content_block no-sidebar">
        	<h1><span>เที่ยงนี้กินอะไร ?</span></h1>
            <div class="fl-container">
                <div class="posts-block">             
                    <div class="contentarea">
                    <?php 
						if (isset($_SESSION['action_save']) && $_SESSION['action_save']=='success') { ?>                       		<div style="height:200px;">
                            <p><center><h3><span>โพสข้อมูลเรียบร้อย ขอบคุณครับ..</span></h3> </center></p>
                            </div>
                  	<?php 
						unset($_SESSION['action_save']);							
						} else { ?>
                        
                        <div class="col-sm-6">
                        <div class="row"> 
                    <form name="postForm" id="postForm" method="post" action="<?php basename($PHP_SELF)?>" role="form">
                      <div class="form-group form-group-default required ">
                        <span id="preview-img"></span>
                        <input type="file" id="file_upload" class="form-control" required>
                        <input type="hidden" name="file_name" id="file_name" value="" />
                      </div>
                      <div class="row">
                        <div class="col-sm-6">
                          <div class="form-group form-group-default required">
                            <label>ชื่อเมนู</label>
                            <input type="text" class="form-control" name="name" required placeholder="ใส่ชื่อเมนูอาหาร" >
                          </div>
                        </div>
                        <div class="col-sm-6">
						  <div class="form-group form-group-default input-group">
							<label>ราคา</label>
							<input type="price" name="price" class="form-control">
							<span class="input-group-addon">บาท</span>
						  </div>
                        </div> 
                      </div> 
                      <div class="form-group  form-group-default required">
                        <label>ชื่อร้าน</label>
                        <input type="text" class="form-control" name="shop_name" placeholder="ใส่ชื่อร้าน" required>
                      </div>
                      <div class="form-group  form-group-default required">
                        <label>ประเภทอาหาร</label>
                        <textarea id="tags" name="tags"></textarea>
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
                     	<input type="hidden" name="action" value="send" />
                     	<button class="btn btn-danger rsu big" type="submit">แชร์</button>
                     </div>
                    </form><!-- 
    				<script src="assets/pages/js/form_elements.js" type="text/javascript"></script> -->
                        </div>
                        </div>
                     <?php } ?>
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
      'swf'      : 'assets/uploadify/uploadify.swf',
      'uploader' : 'upload.php',
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
		  }else{
			$("#preview-img").html("<img src='tmp/"+data+"' style='width:100%!important;height:auto!important'>");
			$("#file_name").val(data);
		  }
       }

    });
	
	$('#tags').tagEditor({
       autocomplete: { delay: 0, position: { collision: 'flip' }, source: ['ข้าว', 'ก๋วยเตี๋ยว', 'บุฟเฟ่', 'อาหารตามสั่ง', 'อาหารเกาหลี', 'อาหารจานเดียว', 'เกี๋ยวเตี๋ยว'] },
       forceLowercase: false,
	   minLength: 3 ,
	   delimiter:',;',
	   clickDelete: true,
       placeholder: '...'
     });

  });

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
		file_name: "required",
		name: "required",	
		shop_name: "required",	
		price: {
		  required: true,
		  number: true
		}
	  },
	  messages: {
		file_name: "กรุณาเลือกรูปภาพ",
		name: "กรุณาระบุชื่ออาหาร",
		shop_name: "กรุณาระบุชื่อร้าน",
		price: {
		  required: "กรุณาระบุราคา",
		  number: "ข้อมูลราคาไม่ถูกต้อง"
		}

  	  }
	});
</script>