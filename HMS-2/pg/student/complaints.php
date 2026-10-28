<?php
$page_title = "Complaints";
require_once("header.php");

$msg = "";
$msg_type = "";

// Handle complaint submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_complaint'])) {
    $title = trim(isset($_POST['title']) ? $_POST['title'] : '');
    $category = trim(isset($_POST['category']) ? $_POST['category'] : 'General');
    $comment = trim(isset($_POST['comment']) ? $_POST['comment'] : '');

    if (!empty($title) && !empty($comment)) {
        $stmt = mysqli_prepare($con, "INSERT INTO pg_complaints (student_id, title, category, comment, status, created_at) VALUES (?, ?, ?, ?, 'Pending', NOW())");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "isss", $current_student_id, $title, $category, $comment);
            if (mysqli_stmt_execute($stmt)) {
                $msg = "Complaint submitted successfully! Warden will review shortly.";
                $msg_type = "success";
            } else {
                $msg = "Failed to submit complaint. Please try again.";
                $msg_type = "danger";
            }
            mysqli_stmt_close($stmt);
        }
    } else {
        $msg = "Please provide both title and details for the complaint.";
        $msg_type = "danger";
    }
}

// Fetch complaints for this student
$q_comp = mysqli_query($con, "SELECT c.*, s.name as student_name 
                              FROM pg_complaints c 
                              JOIN pg_students s ON c.student_id = s.id 
                              WHERE c.student_id = $current_student_id 
                              ORDER BY c.id DESC");
?>

<div class="page-title-banner">
    <h2>COMPLAINTS</h2>
    <button type="button" class="btn-create-action" onclick="openComplaintModal()">
        <i class='bx bx-plus'></i> Create
    </button>
</div>

<?php if (!empty($msg)): ?>
    <div style="padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-size: 13px; font-weight: 500; <?php echo $msg_type === 'success' ? 'background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0;' : 'background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca;'; ?>">
        <?php echo htmlspecialchars($msg); ?>
    </div>
<?php endif; ?>

<div class="pg-table-card">
    <table class="pg-table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Comment</th>
                <th>Complained on</th>
                <th>Status</th>
                <th>created by</th>
                <th style="text-align: right;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($q_comp) === 0): ?>
                <tr>
                    <td colspan="6" class="no-data-cell">
                        No Data Found
                    </td>
                </tr>
            <?php else: ?>
                <?php while ($row = mysqli_fetch_assoc($q_comp)): ?>
                    <tr>
                        <td style="font-weight: 600; color: #0f172a;">
                            <?php echo htmlspecialchars($row['title']); ?>
                            <div style="font-size: 11px; color: #64748b;"><?php echo htmlspecialchars($row['category']); ?></div>
                        </td>
                        <td style="max-width: 300px; color: #475569;">
                            <?php echo nl2br(htmlspecialchars($row['comment'])); ?>
                        </td>
                        <td style="color: #64748b; white-space: nowrap;">
                            <?php echo date("d/m/Y h:i A", strtotime($row['created_at'])); ?>
                        </td>
                        <td>
                            <?php if ($row['status'] === 'Resolved'): ?>
                                <span style="background: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">Resolved</span>
                            <?php elseif ($row['status'] === 'In Progress'): ?>
                                <span style="background: #e0f2fe; color: #0369a1; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">In Progress</span>
                            <?php else: ?>
                                <span style="background: #fef3c7; color: #92400e; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($row['student_name']); ?>
                        </td>
                        <td style="text-align: right;">
                            <span style="color: #94a3b8; font-size: 12px;">Submitted</span>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Create Complaint Modal -->
<div class="modal-overlay" id="complaintModal">
    <div class="modal-box">
        <div class="modal-box-header">
            <h4><i class='bx bx-message-square-error'></i> Register New Complaint</h4>
            <button type="button" class="modal-close-btn" onclick="closeComplaintModal()">&times;</button>
        </div>
        <form method="POST" action="complaints.php">
            <div class="modal-box-body">
                <div style="margin-bottom: 16px;">
                    <label style="font-size: 13px; font-weight: 600; color: #334155;">Issue Category</label>
                    <select name="category" class="form-control-pg" required>
                        <option value="Wi-Fi / Internet">Wi-Fi / Internet Connectivity</option>
                        <option value="Plumbing / Water">Plumbing / Bathroom Geyser</option>
                        <option value="Drinking Water / RO">Drinking Water / RO Purifier</option>
                        <option value="Electrical / Fan / Lights">Electrical / Room Fan / Lights</option>
                        <option value="Room Cleaning / Housekeeping">Room Cleaning / Housekeeping</option>
                        <option value="Noise / Other">Noise / Other Resident Issue</option>
                    </select>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="font-size: 13px; font-weight: 600; color: #334155;">Complaint Title <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="title" placeholder="e.g. Wi-Fi router on 4th floor disconnected" class="form-control-pg" required>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="font-size: 13px; font-weight: 600; color: #334155;">Detailed Description <span style="color:#ef4444;">*</span></label>
                    <textarea name="comment" rows="4" placeholder="Explain the problem clearly for the maintenance technician..." class="form-control-pg" required></textarea>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" onclick="closeComplaintModal()" style="padding: 10px 20px; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; color: #64748b; font-size: 13px; font-weight: 600; cursor: pointer;">Cancel</button>
                    <button type="submit" name="create_complaint" class="btn-create-action" style="padding: 10px 24px; border-radius: 8px;">
                        <i class='bx bx-send'></i> Submit Complaint
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function openComplaintModal() {
        document.getElementById('complaintModal').style.display = 'flex';
    }
    function closeComplaintModal() {
        document.getElementById('complaintModal').style.display = 'none';
    }
</script>

<?php require_once("footer.php"); ?>
