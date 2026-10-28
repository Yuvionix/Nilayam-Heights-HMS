<?php 
require_once("../../../Includes/config.php"); 
require_once("../../../Includes/session.php"); 

require_role('admin', '../../../login.php');

$meta = array();
if(isset($_GET['id'])){
	$get_id = (int)$_GET['id'];
	$stmt = $con->prepare("SELECT * FROM staff WHERE id = ?");
	$stmt->bind_param("i", $get_id);
	$stmt->execute();
	$res = $stmt->get_result();
	if($row = $res->fetch_assoc()){
		$meta = $row;
	}
	$stmt->close();
}
?>
<div class="container-fluid">
	<div id="msg"></div>
	
	<form action="" id="manage-user">	
		<input type="hidden" name="id" value="<?php echo htmlspecialchars(isset($meta['id']) ? $meta['id']: '') ?>">
		<div class="form-group">
			<label for="name">Email</label>
			<input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars(isset($meta['email']) ? $meta['email']: '') ?>" required>
		</div>
		<div class="form-group">
			<label for="username">Username</label>
			<input type="text" name="username" id="username" class="form-control" value="<?php echo htmlspecialchars(isset($meta['username']) ? $meta['username']: '') ?>" required autocomplete="off">
		</div>
		<div class="form-group">
			<label for="password">Password</label>
			<input type="password" name="password" id="password" class="form-control" value="" autocomplete="off">
			<?php if(isset($meta['id'])): ?>
			<small><i>Leave this blank if you don't want to change the password.</i></small>
		<?php endif; ?>
		</div>
	</form>
</div>
<script>
	$('.select2').select2({
		placeholder:"Please select here",
		width:"100%"
	})
	
	$('#manage-user').submit(function(e){
		e.preventDefault();
		start_load()
		$.ajax({
			url:'ajax.php?action=save_user',
			method:'POST',
			data:$(this).serialize(),
			success:function(resp){
				if(resp == 1){
					alert_toast("Data successfully saved",'success')
					setTimeout(function(){
						location.reload()
					},1500)
				} else if(resp == 2){
					$('#msg').html('<div class="alert alert-danger">Username already exists.</div>')
					end_load()
				} else {
					$('#msg').html('<div class="alert alert-danger">An error occurred while saving.</div>')
					end_load()
				}
			}
		})
	})
</script>
