<?php
$page_title = "Broadcast Notice";
require_once("header.php");

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_announcement'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $importance = $_POST['importance'];

    if (!empty($title) && !empty($content)) {
        $stmt = mysqli_prepare($con, "INSERT INTO pg_announcements (title, content, importance, created_at) VALUES (?, ?, ?, NOW())");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sss", $title, $content, $importance);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            $msg = "Announcement broadcasted successfully to all PG student residents!";
        }
    }
}

$ann_q = mysqli_query($con, "SELECT * FROM pg_announcements ORDER BY id DESC");
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h2 style="font-size: 20px; font-weight: 700; color: #0f172a;">Broadcast Student PG Notices</h2>
        <p style="font-size: 13px; color: #64748b; margin-top: 2px;">Publish Wi-Fi, gate timing, sanitation, and festival notices to resident students.</p>
    </div>
</div>

<?php if (!empty($msg)): ?>
    <div style="padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-size: 13px; font-weight: 500; background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0;">
        <?php echo htmlspecialchars($msg); ?>
    </div>
<?php endif; ?>

<div style="display: grid; grid-template-columns: 1fr 1.4fr; gap: 24px; align-items: flex-start;">
    <!-- Form -->
    <div style="background: #ffffff; border-radius: 10px; border: 1px solid #e2e8f0; padding: 24px;">
        <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin-bottom: 16px;">Publish New Announcement</h3>
        
        <form method="POST" action="announcements.php">
            <div style="margin-bottom: 14px;">
                <label style="font-size: 12px; font-weight: 600; color: #334155;">Notice Title *</label>
                <input type="text" name="title" placeholder="e.g. Wi-Fi Maintenance Tonight" required style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1.5px solid #cbd5e1; font-size: 13px; margin-top: 4px;">
            </div>

            <div style="margin-bottom: 14px;">
                <label style="font-size: 12px; font-weight: 600; color: #334155;">Priority Level</label>
                <select name="importance" style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1.5px solid #cbd5e1; font-size: 13px; margin-top: 4px;">
                    <option value="normal">Normal Notice</option>
                    <option value="urgent">Urgent / Important Notice</option>
                </select>
            </div>

            <div style="margin-bottom: 18px;">
                <label style="font-size: 12px; font-weight: 600; color: #334155;">Message Content *</label>
                <textarea name="content" rows="5" placeholder="Enter complete announcement details for student residents..." required style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1.5px solid #cbd5e1; font-size: 13px; margin-top: 4px;"></textarea>
            </div>

            <button type="submit" name="post_announcement" style="background: #0099e5; color: #fff; border: none; padding: 10px 20px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; width: 100%;">
                <i class='bx bx-broadcast'></i> Broadcast to All Students
            </button>
        </form>
    </div>

    <!-- Active List -->
    <div style="display: flex; flex-direction: column; gap: 14px;">
        <h3 style="font-size: 15px; font-weight: 700; color: #0f172a;">Active Notices</h3>
        <?php while ($a = mysqli_fetch_assoc($ann_q)): ?>
            <div style="background: #ffffff; border-radius: 10px; border: 1px solid #e2e8f0; padding: 18px 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                    <span style="font-size: 11px; color: #64748b;"><?php echo date("d M Y, h:i A", strtotime($a['created_at'])); ?></span>
                    <span style="background: <?php echo $a['importance'] === 'urgent' ? '#fee2e2; color: #b91c1c;' : '#e0f2fe; color: #0369a1;'; ?> padding: 2px 8px; border-radius: 12px; font-size: 10px; font-weight: 700;">
                        <?php echo strtoupper($a['importance']); ?>
                    </span>
                </div>
                <h4 style="font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 4px;"><?php echo htmlspecialchars($a['title']); ?></h4>
                <p style="font-size: 12px; color: #475569; margin: 0; line-height: 1.5;"><?php echo nl2br(htmlspecialchars($a['content'])); ?></p>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php require_once("footer.php"); ?>
