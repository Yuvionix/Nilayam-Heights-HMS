<?php 
  require_once("../../Includes/config.php"); 
  require_once("../../Includes/session.php"); 
  require_role(array('member', 'admin'), '../../login.php');

$id = 0;
$total_amount = 0;

if(isset($_GET['id'])){
	$get_id = (int)$_GET['id'];
	$stmt = $con->prepare("SELECT b.*, m.username FROM billing b INNER JOIN member m ON m.id = b.member_id WHERE b.id = ?");
	$stmt->bind_param("i", $get_id);
	$stmt->execute();
	$res = $stmt->get_result();
	if($row = $res->fetch_assoc()){
		if (isset($_SESSION['account']) && $_SESSION['account'] === 'member') {
			$my_uid = isset($_SESSION['uid']) ? (int)$_SESSION['uid'] : 0;
			if ((int)$row['member_id'] !== $my_uid) {
				http_response_code(403);
				echo "<div class='alert alert-danger m-3'>Unauthorized payment request.</div>";
				exit;
			}
		}
		$id = $row['id'];
		$total_amount = $row['total_amount'];
	}
	$stmt->close();
}
?>
<div class="containe-fluid">
    <form action="" id="maage-payment">
    <input type="hidden" name="id" value='<?php echo $id ?>'>
        <div class="form-group">
            <label for="">Amount Due</label>
            <input type="number" id="amount_due" class="form-control text-right" step="any" disabled value="<?php echo $total_amount ?>">
        </div>
        <div class="form-group">
            <label for="">Amount Payed</label>
            <input type="number" id="amount_payed" class="form-control text-right" step="any" value="0">
        </div>
    </form>
</div>