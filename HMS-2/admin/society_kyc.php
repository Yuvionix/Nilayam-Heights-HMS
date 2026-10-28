<?php
require_once("../Includes/config.php");
require_once("../Includes/session.php");
require_role('admin', '../login.php');

// Handle KYC verification actions
$action_msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_kyc_status'])) {
    $kyc_id = (int)$_POST['kyc_id'];
    $new_status = in_array($_POST['status'], array('Verified', 'Rejected', 'Pending')) ? $_POST['status'] : 'Pending';
    $remarks = trim($_POST['remarks'] ?? '');
    $admin_user = $_SESSION['username'] ?? 'Admin Yuvraj';

    $upd = mysqli_prepare($con, "UPDATE society_kyc SET status = ?, reviewed_by = ?, reviewed_at = NOW(), remarks = ? WHERE id = ?");
    if ($upd) {
        mysqli_stmt_bind_param($upd, "sssi", $new_status, $admin_user, $remarks, $kyc_id);
        mysqli_stmt_execute($upd);
        mysqli_stmt_close($upd);
        $action_msg = "KYC status successfully updated to " . strtoupper($new_status) . "!";
    }
}

// Fetch all society KYCs
$kycs_query = mysqli_query($con, "
    SELECT sk.*, m.username AS member_username, m.email AS member_email, m.phone AS member_phone 
    FROM society_kyc sk 
    JOIN member m ON sk.member_id = m.id 
    ORDER BY sk.id DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Resident KYC Verification | Society Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
        body { background: #f8fafc; color: #1e293b; min-height: 100vh; padding: 24px; }
        .page-wrap { max-width: 1300px; margin: 0 auto; }
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .btn-back { display: inline-flex; align-items: center; gap: 6px; text-decoration: none; color: #0284c7; font-weight: 600; font-size: 14px; }
        .btn-back:hover { text-decoration: underline; }
        .card-main { background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 16px rgba(0,0,0,0.03); overflow: hidden; }
        .card-header { padding: 24px 30px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px; }
        .card-header h2 { font-size: 20px; font-weight: 700; color: #0f172a; }
        .card-header p { font-size: 13px; color: #64748b; margin-top: 2px; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { background: #f8fafc; padding: 14px 18px; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #e2e8f0; }
        td { padding: 16px 18px; border-bottom: 1px solid #f1f5f9; font-size: 13px; vertical-align: middle; }
        tr:hover td { background: #fbfdff; }
        .status-pill { padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; }
        .status-verified { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .status-pending { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
        .status-rejected { background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; }
        .btn-action { padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; border: none; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s; }
        .btn-verify { background: #10b981; color: #fff; }
        .btn-verify:hover { background: #059669; }
        .btn-reject { background: #ef4444; color: #fff; }
        .btn-reject:hover { background: #dc2626; }
        .doc-chip { display: inline-flex; align-items: center; gap: 4px; background: #e0f2fe; color: #0369a1; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; text-decoration: none; margin: 2px; }
        .doc-chip:hover { background: #bae6fd; }
        .avatar-thumb { width: 42px; height: 42px; border-radius: 50%; object-fit: cover; border: 2px solid #e2e8f0; }
        .avatar-placeholder { width: 42px; height: 42px; border-radius: 50%; background: #0284c7; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 16px; }
    </style>
</head>
<body>

<div class="page-wrap">
    <div class="top-bar">
        <a href="Dashboard/admin.php" class="btn-back">
            <i class='bx bx-left-arrow-alt'></i> Back to Admin Dashboard
        </a>
        <div style="font-size: 13px; color: #64748b;">
            Nilayam Heights Society &bull; Resident Verification Portal
        </div>
    </div>

    <?php if (!empty($action_msg)): ?>
        <div style="background: #dcfce7; border: 1px solid #bbf7d0; color: #15803d; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-size: 13.5px; font-weight: 500; display: flex; align-items: center; gap: 8px;">
            <i class='bx bx-check-circle' style="font-size: 18px;"></i> <?php echo htmlspecialchars($action_msg); ?>
        </div>
    <?php endif; ?>

    <div class="card-main">
        <div class="card-header">
            <div>
                <h2>Society Resident KYC Submissions</h2>
                <p>Verify flat ownership documents, Aadhaar cards, and resident identity records</p>
            </div>
            <div>
                <a href="pg_students.php" style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                    <i class='bx bxs-institution'></i> View PG Students &amp; KYC &rarr;
                </a>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Resident / Photo</th>
                        <th>Flat &amp; Residency</th>
                        <th>Contact &amp; Guardian</th>
                        <th>Address</th>
                        <th>Uploaded Documents</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($kycs_query) == 0): ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: #64748b;">
                                <i class='bx bx-id-card' style="font-size: 36px; color: #cbd5e1; display: block; margin-bottom: 8px;"></i>
                                No resident KYC submissions on file yet.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php while ($r = mysqli_fetch_assoc($kycs_query)): ?>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <?php if (!empty($r['profile_pic']) && file_exists(__DIR__ . "/../uploads/society_kyc/" . $r['profile_pic'])): ?>
                                            <a href="../uploads/society_kyc/<?php echo htmlspecialchars($r['profile_pic']); ?>" target="_blank">
                                                <img src="../uploads/society_kyc/<?php echo htmlspecialchars($r['profile_pic']); ?>" alt="Photo" class="avatar-thumb">
                                            </a>
                                        <?php else: ?>
                                            <div class="avatar-placeholder">
                                                <?php echo strtoupper(substr($r['full_name'] ?: $r['member_username'], 0, 1)); ?>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <strong style="color: #0f172a; font-size: 14px; display: block;"><?php echo htmlspecialchars($r['full_name'] ?: $r['member_username']); ?></strong>
                                            <span style="font-size: 11.5px; color: #64748b;"><?php echo htmlspecialchars($r['member_email']); ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight: 700; color: #0284c7;"><?php echo htmlspecialchars($r['flat_no'] ?: 'Not Specified'); ?></div>
                                    <span style="font-size: 11px; background: #e0f2fe; color: #0369a1; padding: 2px 6px; border-radius: 4px; font-weight: 600;">
                                        <?php echo htmlspecialchars($r['resident_type'] ?: 'Owner'); ?>
                                    </span>
                                </td>
                                <td>
                                    <div style="font-size: 12.5px; font-weight: 600; color: #334155;"><i class='bx bx-phone' style="color: #0284c7;"></i> <?php echo htmlspecialchars($r['emergency_phone'] ?: $r['member_phone']); ?></div>
                                    <?php if (!empty($r['father_or_spouse'])): ?>
                                        <div style="font-size: 11px; color: #64748b;">Guardian/Spouse: <?php echo htmlspecialchars($r['father_or_spouse']); ?></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div style="font-size: 11.5px; color: #475569; max-width: 220px; line-height: 1.4;">
                                        <?php echo htmlspecialchars($r['permanent_address']); ?>
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; flex-direction: column; gap: 4px;">
                                        <?php if (!empty($r['id_proof_front'])): ?>
                                            <a href="../uploads/society_kyc/<?php echo htmlspecialchars($r['id_proof_front']); ?>" target="_blank" class="doc-chip">
                                                <i class='bx bx-id-card'></i> ID Front
                                            </a>
                                        <?php endif; ?>
                                        <?php if (!empty($r['id_proof_back'])): ?>
                                            <a href="../uploads/society_kyc/<?php echo htmlspecialchars($r['id_proof_back']); ?>" target="_blank" class="doc-chip">
                                                <i class='bx bx-id-card'></i> ID Back
                                            </a>
                                        <?php endif; ?>
                                        <?php if (!empty($r['address_proof_front'])): ?>
                                            <a href="../uploads/society_kyc/<?php echo htmlspecialchars($r['address_proof_front']); ?>" target="_blank" class="doc-chip">
                                                <i class='bx bx-file'></i> Ownership/Lease Front
                                            </a>
                                        <?php endif; ?>
                                        <?php if (!empty($r['address_proof_back'])): ?>
                                            <a href="../uploads/society_kyc/<?php echo htmlspecialchars($r['address_proof_back']); ?>" target="_blank" class="doc-chip">
                                                <i class='bx bx-file'></i> Ownership/Lease Back
                                            </a>
                                        <?php endif; ?>
                                        <?php if (empty($r['id_proof_front']) && empty($r['address_proof_front'])): ?>
                                            <span style="font-size: 11px; color: #94a3b8; font-style: italic;">No docs uploaded</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($r['status'] === 'Verified'): ?>
                                        <span class="status-pill status-verified"><i class='bx bx-check-shield'></i> Verified</span>
                                    <?php elseif ($r['status'] === 'Rejected'): ?>
                                        <span class="status-pill status-rejected"><i class='bx bx-x-circle'></i> Rejected</span>
                                    <?php else: ?>
                                        <span class="status-pill status-pending"><i class='bx bx-time-five'></i> Pending</span>
                                    <?php endif; ?>
                                    <?php if (!empty($r['reviewed_by'])): ?>
                                        <div style="font-size: 10px; color: #94a3b8; margin-top: 4px;">By: <?php echo htmlspecialchars($r['reviewed_by']); ?></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 6px;">
                                        <form method="POST" action="society_kyc.php" style="display: inline;">
                                            <input type="hidden" name="update_kyc_status" value="1">
                                            <input type="hidden" name="kyc_id" value="<?php echo $r['id']; ?>">
                                            <input type="hidden" name="status" value="Verified">
                                            <button type="submit" class="btn-action btn-verify" title="Verify KYC">
                                                <i class='bx bx-check'></i> Verify
                                            </button>
                                        </form>
                                        <form method="POST" action="society_kyc.php" style="display: inline;">
                                            <input type="hidden" name="update_kyc_status" value="1">
                                            <input type="hidden" name="kyc_id" value="<?php echo $r['id']; ?>">
                                            <input type="hidden" name="status" value="Rejected">
                                            <button type="submit" class="btn-action btn-reject" title="Reject KYC" onclick="return confirm('Are you sure you want to reject this KYC submission?');">
                                                <i class='bx bx-x'></i> Reject
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
