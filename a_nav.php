<!-- Modal -->
    <div class="modal fade fill-in" id="modalFillIn" tabindex="-1" role="dialog" aria-hidden="true">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        <i class="pg-close"></i>
      </button>
      <div class="modal-dialog ">
        <div class="modal-content">
			<?php include('body_post.php');?>
			<!-- 
          <div class="modal-header">
            <h5 class="text-left p-b-5"><span class="semi-bold">News letter</span> signup</h5>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-9 ">
                <input type="text" placeholder="Your email address here" class="form-control input-lg" id="icon-filter" name="icon-filter">
              </div>
              <div class="col-md-3 no-padding sm-m-t-10 sm-text-center">
                <button type="button" class="btn btn-primary btn-lg btn-large fs-15">Sign up</button>
              </div>
            </div>
            <p class="text-right sm-text-center hinted-text p-t-10 p-r-10">What is it? Terms and conditions</p>
          </div>
          <div class="modal-footer">
          </div>
        </div> -->
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    </div>
<!-- end Modal -->


	<img src="assets/img/my_preloader1.gif" id="preloader">
	<!-- H E A D E R -->
	<header>
    	<a href="<?php echo PRO_URL;?>" class="logo rsu" style="font-size:42px;font-weight:bold;text-decoration:none"><?php echo PRO_TITLE?><!-- <img src="assets/img/logo.jpg" alt=""> --></a>
  <script type="text/javascript">
			var noAudio = false;
			if(
				navigator.userAgent.match(/webOS/i) ||
				navigator.userAgent.match(/iPhone/i) ||
				navigator.userAgent.match(/iPod/i))
			{
				noAudio = true;
			}
			$(document).ready(function () {
				if (!noAudio) {
					$("#jquery_jplayer_1").jPlayer({
						ready: function (event) {
							$(this).jPlayer("setMedia", {
								mp3:"assets/media/track.mp3",
								oga:"assets/media/track.ogg"
							});
							$('.jp-play').click();
						},
						swfPath: "js",
						supplied: "mp3, oga",
						wmode: "window",
						loop : "1"
					});
				}
			});
        </script>
		<div id="jquery_jplayer_1" class="jp-jplayer"></div>
		<a href="javascript:void(0)" class="filter_toggler"></a>
		<div id="jp_container_1" class="jp-audio">
			<div class="jp-type-single">
				<div class="jp-gui jp-interface">
					<div class="jp-controls">
                        <a href="javascript:void(0)" class="jp-play"></a>
                        <a href="javascript:void(0)" class="jp-pause"></a>
					</div>
				</div>
			</div>
		</div>
		<nav>
            <ul class="menu">
                <li class="current-menu-item"><a href="javascript:;" data-target="#modalFillIn" data-toggle="modal" id="btnFillSizeToggler2" >เป็นคนออกไอเดีย<br><span class="arial small">ideas luncher</span></a></li><!--  data-target="#modalForm" data-toggle="modal"  -->
                <li><a href="<?php echo _path('aboutus');?>">เกี่ยวกับเรา<br><span class="arial small">about us</span></a></li>
                <li><a href="<?php echo _path('blog');?>">บทความ<br><span class="arial small">lunch blog</span></a></li>
                <li><a href="<?php echo _path('contactus');?>">ติดต่อเรา<br><span class="arial small">contact us</span></a></li>
			</ul><!-- .menu -->
        </nav>
	    <nav class="mobile_header">
            <select id="mobile_select"></select>
        </nav>
        <div class="clear"></div>
    </header>
