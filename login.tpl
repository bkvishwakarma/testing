<!-- Login.tpl-->
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog zindex">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Login Now</h4>
				<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
			</div>
			<div class="modal-body text-center">
				<button type="button" class="btn btn-primary btn-block" onclick="login()"><i class="fa fa-facebook-square fa-2x"> <span class="rsp">Login With Facebook</span></i> </button>
			</div>
			<div class="modal-footer">
				<!--button type="button" class="btn btn-primary" data-dismiss="modal">Close</button-->
			</div>
		</div>
	</div>
</div>
<div id="status"></div>
<div id="fb-root"></div>

<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId            : '1744339689208302',
      autoLogAppEvents : true,
      xfbml            : true,
      version          : 'v2.11'
    });
  };
  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "https://connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
   function login(){
	FB.login(function(response){
		if(response.status=='connected')
		{
			getFbUserData();
		}
		else if(response.status=='not_authorized')
		{
			alert('We are not logged in ');
		}
		else
		{
			alert('We are not logged in ');
		}
	});
	function getFbUserData(){
		FB.api(
		  '/me','GET',{"fields":"id,name,first_name,last_name,email,birthday,hometown,gender,picture.width(500).height(500)"},
		  function(response) {
			  // Insert your code here
			  saveUserData(response);
		  }
		);
	}
	// end function for get user data
	//code for insert data record
	function saveUserData(user_details){
		var user_details = JSON.stringify(user_details)
		var action="user_details";
		$.ajax({
	    type:'post',
		url: '<{$SITE_URL}>/bk-login.php?ajax=1&action='+action,
		data:{'user_details':user_details},
		success:function(returnval){
			var user_details = $.parseJSON(returnval);
			var user_image = user_details['user_image'];
			var user_name = user_details['user_name'];
			$("#myModal").modal('hide');
			window.location.reload(1);
		}
	   });
	}
/*	<!-- end code for insert data record -->*/
   };
   
   
   // Modal Open For Loging
   $(document).ready(function(){
		var current_user = '<{$user.user_name}>';
		if(current_user != '')
		{
			var user_image = '<{$current_user.user_image}>';
			var user_name = '<{$current_user.user_name}>';
			var fb_id = '<{$current_user.fb_id}>';
		}
		else
		{
			$("#myModal").modal('show');
		}		
	});
</script>