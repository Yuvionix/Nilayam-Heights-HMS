<?php
require_once '../../Includes/config.php';
require_once '../../Includes/session.php';
require_role(array('member', 'admin'), '../../login.php');

$vendor_autoload = __DIR__ . '/assets/barcode/vendor/autoload.php';
if (file_exists($vendor_autoload)) {
    require_once $vendor_autoload;
}

$raw_ids = isset($_POST['ids']) ? (is_array($_POST['ids']) ? $_POST['ids'] : explode(',', $_POST['ids'])) : array();
$clean_ids = array_filter(array_map('intval', $raw_ids));

// If member, they can only print their own member barcode
if (isset($_SESSION['account']) && $_SESSION['account'] === 'member') {
    $my_uid = isset($_SESSION['uid']) ? (int)$_SESSION['uid'] : 0;
    $clean_ids = array($my_uid);
}

if (empty($clean_ids)) {
    echo "<div class='alert alert-warning m-3'>No records selected for printing.</div>";
    exit;
}

$generator = class_exists('Picqer\Barcode\BarcodeGeneratorPNG') ? new Picqer\Barcode\BarcodeGeneratorPNG() : null;
$placeholders = implode(',', array_fill(0, count($clean_ids), '?'));
$types = str_repeat('i', count($clean_ids));
$stmt = $con->prepare("SELECT id, username as name, address as caddress, email, phone FROM member WHERE id IN ($placeholders)");
$stmt->bind_param($types, ...$clean_ids);
$stmt->execute();
$qry = $stmt->get_result();
while ($row = $qry->fetch_assoc()) {
    $tracking_id = str_pad($row['id'], 8, '0', STR_PAD_LEFT);
    $name = $row['name'];
    $caddress = $row['caddress'];
?>
<div class="container-fluid" id="toPrint">
<style type="text/css">
	#bcode-field{
		width:calc(100%) ;
    	align-items: center;
	}
	#bcode{
		max-height: inherit;
		max-width: inherit;
		width:calc(100%) ;
		height: 10vh;
	}
	#bcode-label {
        font-weight: 700;
        font-size: 17px;
        text-align: justify;
        text-align-last: justify;
        text-justify: inter-word;
	}
	#dfield p{
		margin: unset
	}
	.text-center{
		text-align:center;
	}
</style>
	<div class="" id="bcode-field">
		<?php if ($generator): ?>
			<img id="bcode" src="data:image/png;base64,<?php echo base64_encode($generator->getBarcode($tracking_id, "C128")) ?>">
		<?php endif; ?>
		<div id="bcode-label"><?php echo htmlspecialchars(preg_replace('/([0-9])/s','$1 ', $tracking_id)); ?></div>
	</div>
	<br>
	<div class="col-lg-12 text-center" id="dfield">
	<p><large><b><?php echo htmlspecialchars($name) ?></b></large></p>
	<hr>
	<p><small><b><?php echo htmlspecialchars($caddress) ?></b></small></p>
	</div>
</div>
<?php }
$stmt->close();
?>
