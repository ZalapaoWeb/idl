<?php 
 if (isset($_POST['action']) && $_POST['action']=='send') {
	 $contact_id = saveContactInfomation($_POST);

	 if ($contact_id > 0) {
		$_SESSION['result'] = 'success';
		redirect('contactus2');
		exit();
	 }
 }

?>

<link rel="stylesheet" href="assets/pages/css/pages.css">  
 
 <!-- C O N T E N T -->
    <div class="content_wrapper">
    	<div class="content_block no-sidebar">
        	<h1><span>ติดต่อเรา</span></h1>
            <div class="fl-container">
                <div class="posts-block">
                    <div class="breadcrumbs">
                        <ul class="pathway">
                            <li><a href="<?php echo _path();?>">หน้าหลัก</a></li>
                            <li class="sep">:</li>
                            <li>ติดต่อเรา</li>
                        </ul>
                    </div><!-- .breadcrumbs -->                
                    <div class="contentarea">
                        <div class="row">
                        	<div class="span_full module_google_map">
                            	<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3875.3012845012154!2d100.555468!3d13.760696000000003!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30e29e94482da579%3A0x341e14edd7ccc629!2z4LiE4Liy4LiL4LmI4LiyIOC4hOC4reC4meC5guC4lCDguK3guYLguKjguIEt4LiU4Li04LiZ4LmB4LiU4LiHIENhc2EgQ29uZG8!5e0!3m2!1sth!2sth!4v1425657586085" width="100%" height="400" frameborder="0" style="border:0"></iframe>
                            </div>
                            <div class="span_full">
                            	<h3><span>contact information</span></h3>   <!-- 
                                <p><strong>5512 Lorem Ipsum Vestibulum Molesqu, Dolor Sit Amet, Egestas 666 13</strong></p> -->
                                <p><strong>Mobile</strong> +66 095 662 9797 (Speak Thai Only)</p>
                                <p><strong>Email:</strong> <a href="mailto:">info@ideaslunch.in.th</a></p>  
                                <p><strong>Facebook:</strong> <a href="#">facebook.com/ideaslunch</a></p> 
                                <hr>
                            </div>
                           </div>
						<div class="span_full" id="result">
					<?php 
						if (isset($_SESSION['result']) && $_SESSION['result']=='success') { ?>                       		<div style="height:200px;">
                            <p><center><h3><span>ได้รับข้อมูลสอบถามเรียบร้อย ขอบคุณครับ..</span></h3> </center></p>
                            </div>
                  	<?php 
						unset($_SESSION['result']);							
						} else { ?>
                        <form name="contactForm" id="contactForm" method="post" action="<?php basename($PHP_SELF)?>" role="form">
                          <div class="form-group form-group-default required ">
                            <label>อีเมล์</label>
                            <input type="email" class="form-control" name="email" placeholder="info@ideaslunch.in.th"  required>
                          </div>
                          <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group form-group-default required">
                                <label>ชื่อ-นามสกุล</label>
                                <input type="text" class="form-control" name="full_name"  required>
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="form-group form-group-default required">
                                <label>หมายเลขโทรศัพท์</label>
                                <input type="text" class="form-control" name="phone" required>
                              </div>
                            </div>
                          </div>  
                          <div class="form-group form-group-default required ">
                                <label>ข้อความ</label>
                                <textarea name="message" class="form-control" rows="4" required></textarea>
                          </div>                    
                          <div class="form-group">
                            <input type="hidden" name="action" value="send">
                            <button class="btn btn-danger" type="submit">ส่งข้อความ</button>
                          </div>
                        </form>
                      <?php } ?>
                        </div> <!--end span full-->
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
 <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js" type="text/javascript"></script>
 <script>
  $( document ).ready(function () {
	  
	$("#contactForm").validate({
	  rules: {			
		email: {
		  required: true,
		  email: true
		},
		full_name: "required",
		phone: "required",	
		message: "required",	
	  },
	  messages: {
		full_name: "กรุณาระบุชื่อนามสกุล",
		phone: "กรุณาระบุหมายเลขโทรศัพท์",
		message: "กรุณาระบุข้อความ",
		email: {
		  required: "กรุณาระบุอีเมล์",
		  email: "ข้อมูลอีเมล์ไม่ถูกต้อง"
		}
  	  }
	});
	
  });
 </script>