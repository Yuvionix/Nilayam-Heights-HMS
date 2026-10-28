<?php
$page_title = "Complaints Resolution";
require_once("header.php");

$msg = "";

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $comp_id = (int)$_POST['comp_id'];
    $new_status = $_POST['status'];

    if (in_array($new_status, array('Pending', 'In Progress', 'Resolved'), true)) {
        $resolved_sql = ($new_status === 'Resolved') ? ", resolved_at = NOW()" : "";
        mysqli_query($con, "UPDATE pg_complaints SET status = '$new_status' $resolved_sql WHERE id = $comp_id");
        $msg = "Complaint #$comp_id status updated to $new_status.";
    }
}

$comp_q = mysqli_query($con, "SELECT c.*, s.name as student_name, s.phone as student_phone, r.room_number, b.bed_label 
                              FROM pg_complaints c 
                              JOIN pg_students s ON c.student_id = s.id 
                              LEFT JOIN pg_rooms r ON s.room_id = r.id 
                              LEFT JOIN pg_beds b ON s.bed_id = b.id 
                              ORDER BY c.id DESC");
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h2 style="font-size: 20px; font-weight: 700; color: #0f172a;">Student Maintenance & Room Complaints</h2>
        <p style="font-size: 13px; color: #64748b; margin-top: 2px;">Resolve Wi-Fi, plumbing, electrical, and room service requests submitted by students.</p>
    </div>
</div>

<?php if (!empty($msg)): ?>
    <div style="padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-size: 13px; font-weight: 500; background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0;">
        <?php echo htmlspecialchars($msg); ?>
    </div>
<?php endif; ?>

<div class="pg-table-card" style="background:#fff; border-radius:10px; border:1px solid #e2e8f0; overflow:hidden;">
    <table style="width:100%; border-collapse:collapse; text-align:left;">
        <thead>
            <tr style="background:#f8fafc; border-bottom:1.5px solid #e2e8f0;">
                <th style="padding:14px 18px; font-size:12px; color:#64748b;">Complaint Details</th>
                <th style="padding:14px 18px; font-size:12px; color:#64748b;">Student & Room</th>
                <th style="padding:14px 18px; font-size:12px; color:#64748b;">Category</th>
                <th style="padding:14px 18px; font-size:12px; color:#64748b;">Filed On</th>
                <th style="padding:14px 18px; font-size:12px; color:#64748b;">Status</th>
                <th style="padding:14px 18px; font-size:12px; color:#64748b; text-align:right;">Warden Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($comp_q) === 0): ?>
                <tr>
                    <td colspan="6" style="text-align:center; padding:36px; color:#64748b;">No complaints submitted by students.</td>
                </tr>
            <?php else: ?>
                <?php while ($c = mysqli_fetch_assoc($comp_q)): ?>
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:14px 18px; max-width:320px;">
                            <strong style="font-size:14px; color:#0f172a; display:block;"><?php echo htmlspecialchars($c['title']); ?></strong>
                            <p style="font-size:12px; color:#475569; margin-top:2px;"><?php echo htmlspecialchars($c['comment']); ?></p>
                        </td>
                        <td style="padding:14px 18px;">
                            <div style="font-weight:700; color:#0f172a; font-size:13px;"><?php echo htmlspecialchars($c['student_name']); ?></div>
                            <div style="font-size:11px; color:#0099e5; font-weight:600;"><?php echo htmlspecialchars($c['room_number']); ?> (<?php echo htmlspecialchars($c['bed_label']); ?>)</div>
                            <div style="font-size:11px; color:#64748b;"><i class='bx bx-phone'></i> <?php echo htmlspecialchars($c['student_phone']); ?></div>
                        </td>
                        <td style="padding:14px 18px;">
                            <span style="background:#f1f5f9; color:#475569; padding:4px 10px; border-radius:6px; font-size:11px; font-weight:600;">
                                <?php echo htmlspecialchars($c['category']); ?>
                            </span>
                        </td>
                        <td style="padding:14px 18px; font-size:12px; color:#64748b; white-space:nowrap;">
                            <?php echo date("d M Y, h:i A", strtotime($c['created_at'])); ?>
                        </td>
                        <td style="padding:14px 18px;">
                            <?php if ($c['status'] === 'Resolved'): ?>
                                <span style="background:#dcfce7; color:#166534; padding:4px 10px; border-radius:20px; font-size:11px; font-weight:700;">Resolved</span>
                            <?php elseif ($c['status'] === 'In Progress'): ?>
                                <span style="background:#e0f2fe; color:#0369a1; padding:4px 10px; border-radius:20px; font-size:11px; font-weight:700;">In Progress</span>
                            <?php else: ?>
                                <span style="background:#fef3c7; color:#92400e; padding:4px 10px; border-radius:20px; font-size:11px; font-weight:700;">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td style="padding:14px 18px; text-align:right;">
                            <form method="POST" action="complaints_mgmt.php" style="display:inline-flex; gap:6px;">
                                <input type="hidden" name="comp_id" value="<?php echo $c['id']; ?>">
                                <input type="hidden" name="update_status" value="1">
                                <?php if ($c['status'] !== 'In Progress' && $c['status'] !== 'Resolved'): ?>
                                    <button type="submit" name="status" value="In Progress" style="background:#e0f2fe; color:#0369a1; border:1px solid #bae6fd; padding:5px 10px; border-radius:6px; font-size:11px; font-weight:600; cursor:pointer;">
                                        Mark In-Progress
                                    </button>
                                <?php endif; ?>
                                <?php if ($c['status'] !== 'Resolved'): ?>
                                    <button type="submit" name="status" value="Resolved" style="background:#dcfce7; color:#15803d; border:1px solid #bbf7d0; padding:5px 10px; border-radius:6px; font-size:11px; font-weight:600; cursor:pointer;">
                                        Mark Resolved
                                    </button>
                                <?php else: ?>
                                    <span style="color:#10b981; font-size:12px; font-weight:600;"><i class='bx bx-check-circle'></i> Completed</span>
                                <?php endif; ?>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once("footer.php"); ?>
