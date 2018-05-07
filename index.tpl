<!-- text-en-content.tpl -->

<{if isset($data.readyImg)}>

<div class="container" id="converter_con">

	<div class="row" >

		<div class="col-sm-6 mt-3">

			<img src="<{$SITE_URL}>images/banner/<{$data.readyImg}>" class="img-fluid" alt="bkvishwakrma.in"/>	

			<div class="sharePost">

			<button type="button" class="btn btn-block btn-primary mt-1" id="fbShare" name="fbShare" value="Share On Facbook">

				<i class="fa fa-facebook-square fa-2x rsp text-white"> <label >Share On Facbook</label></i>

			</button>

			<a href="<{$SITE_URL}>images/banner/<{$data.readyImg}>" download class="btn btn-success btn-block mt-1" > <i class="fa fa-arrow-circle-o-down fa-2x rsp"> <label> Download Now </label></i></a>

			</div>	

		</div>

	</div>

</div><hr/>

<{/if}>

<div class="container my-3">
	<!--a href="tel:8948998514"><i class="fa fa-mobile fa-2x"></i> Call Now If Any Query </a-->
	<div class="row">

		<{foreach from = $data.theme key=key item=item}>

		<div class="col-sm-6 mb-2">

			<a href="<{$SITE_URL}>match/<{$item.theme_tpl}>.php?action=convertImg&q=<{$key}>">

				<img src="<{$SITE_URL}>images/match-img/<{$item.theme_img}>" alt="<{$item.theme_title}>" class="img-fluid"/>

			</a>	

		</div>	

		<{/foreach}>

	</div>	

</div>



<script>

	// Send ajax for convert Image

	$(document).ready(function(){

		// Share 

		var current_url = window.location.href;

		document.getElementById('fbShare').onclick = function() {

		  FB.ui({

			method: 'share',

			display: 'popup',

			href: current_url,

		  }, function(response){});

		}

	});

</script>	