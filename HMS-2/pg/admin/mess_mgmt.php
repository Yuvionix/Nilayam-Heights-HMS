<?php
$page_title = "Mess Schedule";
require_once("header.php");

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_menu'])) {
    $day_id = (int)$_POST['day_id'];
    $breakfast = trim($_POST['breakfast']);
    $lunch = trim($_POST['lunch']);
    $snacks = trim($_POST['evening_snacks']);
    $dinner = trim($_POST['dinner']);

    $up = mysqli_prepare($con, "UPDATE pg_mess_menu SET breakfast = ?, lunch = ?, evening_snacks = ?, dinner = ? WHERE id = ?");
    if ($up) {
        mysqli_stmt_bind_param($up, "ssssi", $breakfast, $lunch, $snacks, $dinner, $day_id);
        mysqli_stmt_execute($up);
        mysqli_stmt_close($up);
        $msg = "Meal menu updated successfully!";
    }
}

$menu_q = mysqli_query($con, "SELECT * FROM pg_mess_menu ORDER BY id ASC");
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h2 style="font-size: 20px; font-weight: 700; color: #0f172a;">Weekly Student Mess Menu Configuration</h2>
        <p style="font-size: 13px; color: #64748b; margin-top: 2px;">Update daily breakfast, lunch, evening tea & snacks, and dinner schedules.</p>
    </div>
</div>

<?php if (!empty($msg)): ?>
    <div style="padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-size: 13px; font-weight: 500; background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0;">
        <?php echo htmlspecialchars($msg); ?>
    </div>
<?php endif; ?>

<div style="display: flex; flex-direction: column; gap: 16px;">
    <?php while ($m = mysqli_fetch_assoc($menu_q)): ?>
        <div style="background:#fff; border-radius:10px; border:1px solid #e2e8f0; padding:20px; box-shadow:0 2px 6px rgba(0,0,0,0.02);">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; border-bottom:1px solid #f1f5f9; padding-bottom:8px;">
                <h3 style="font-size:16px; font-weight:700; color:#0099e5;"><?php echo htmlspecialchars($m['day_name']); ?></h3>
                <span style="font-size:11px; color:#64748b;">4 Meals Cycle</span>
            </div>

            <form method="POST" action="mess_mgmt.php">
                <input type="hidden" name="day_id" value="<?php echo $m['id']; ?>">
                <input type="hidden" name="update_menu" value="1">

                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:12px; margin-bottom:12px;">
                    <div>
                        <label style="font-size:11px; font-weight:600; color:#334155; text-transform:uppercase;">Breakfast</label>
                        <input type="text" name="breakfast" value="<?php echo htmlspecialchars($m['breakfast']); ?>" style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #cbd5e1; font-size:12px; margin-top:4px;">
                    </div>
                    <div>
                        <label style="font-size:11px; font-weight:600; color:#334155; text-transform:uppercase;">Lunch</label>
                        <input type="text" name="lunch" value="<?php echo htmlspecialchars($m['lunch']); ?>" style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #cbd5e1; font-size:12px; margin-top:4px;">
                    </div>
                    <div>
                        <label style="font-size:11px; font-weight:600; color:#334155; text-transform:uppercase;">Evening Snacks</label>
                        <input type="text" name="evening_snacks" value="<?php echo htmlspecialchars($m['evening_snacks']); ?>" style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #cbd5e1; font-size:12px; margin-top:4px;">
                    </div>
                    <div>
                        <label style="font-size:11px; font-weight:600; color:#334155; text-transform:uppercase;">Dinner</label>
                        <input type="text" name="dinner" value="<?php echo htmlspecialchars($m['dinner']); ?>" style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #cbd5e1; font-size:12px; margin-top:4px;">
                    </div>
                </div>

                <div style="text-align:right;">
                    <button type="submit" style="background:#0099e5; color:#fff; border:none; padding:6px 16px; border-radius:6px; font-size:12px; font-weight:600; cursor:pointer;">
                        Update <?php echo htmlspecialchars($m['day_name']); ?> Menu
                    </button>
                </div>
            </form>
        </div>
    <?php endwhile; ?>
</div>

<?php require_once("footer.php"); ?>
