<?php
require_once("../Includes/config.php");
require_once("../Includes/session.php");
require_role('admin', '../login.php');

// Handle KYC verification or bed assignment from Society Admin
$action_msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign_room_bed'])) {
    $student_id = (int)$_POST['student_id'];
    $bed_id = (int)$_POST['bed_id'];

    $q_bed = mysqli_query($con, "SELECT b.id, b.room_id, r.room_number FROM pg_beds b JOIN pg_rooms r ON b.room_id = r.id WHERE b.id = $bed_id AND b.is_occupied = 0 LIMIT 1");
    if ($bed = mysqli_fetch_assoc($q_bed)) {
        $room_id = (int)$bed['room_id'];
        mysqli_query($con, "UPDATE pg_beds SET is_occupied = 1, current_student_id = $student_id WHERE id = $bed_id");
        mysqli_query($con, "UPDATE pg_students SET room_id = $room_id, bed_id = $bed_id WHERE id = $student_id");
        mysqli_query($con, "UPDATE pg_rooms SET occupied_beds = (SELECT COUNT(*) FROM pg_beds WHERE room_id = $room_id AND is_occupied = 1) WHERE id = $room_id");
        $action_msg = "Bed successfully allocated to student in Room " . htmlspecialchars($bed['room_number']) . "!";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_kyc_status'])) {
    $student_id = (int)$_POST['student_id'];
    $new_status = in_array($_POST['status'], array('Verified', 'Rejected', 'Pending')) ? $_POST['status'] : 'Pending';
    mysqli_query($con, "UPDATE pg_kyc SET status = '$new_status' WHERE student_id = $student_id");
    $action_msg = "Student KYC status updated to " . strtoupper($new_status) . "!";
}

// Fetch all PG students with their KYC documents, photos, and room/bed allocations
$q_students = mysqli_query($con, "
    SELECT s.*, 
           r.room_number, r.floor, r.occupancy_type,
           b.bed_label,
           k.id as kyc_id, k.father_name, k.father_mobile, k.mother_name, k.mother_mobile, k.address as kyc_address,
           k.profile_pic, k.address_proof_front, k.address_proof_back, k.id_proof_front, k.id_proof_back,
           k.status as kyc_status, k.submitted_at as kyc_date
    FROM pg_students s
    LEFT JOIN pg_rooms r ON s.room_id = r.id
    LEFT JOIN pg_beds b ON s.bed_id = b.id
    LEFT JOIN pg_kyc k ON s.id = k.student_id
    ORDER BY s.id DESC
");

// Fetch vacant beds for allocation dropdown
$q_vacant = mysqli_query($con, "
    SELECT b.id, b.bed_label, r.room_number, r.floor, r.occupancy_type
    FROM pg_beds b
    JOIN pg_rooms r ON b.room_id = r.id
    WHERE b.is_occupied = 0
    ORDER BY r.floor, r.room_number, b.bed_label
");
$vacant_beds = array();
while ($vb = mysqli_fetch_assoc($q_vacant)) {
    $vacant_beds[] = $vb;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PG Students & KYC Document Registry | Admin Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
        body { background: #f8fafc; color: #1e293b; min-height: 100vh; padding: 24px; }
        .page-wrap { max-width: 1400px; margin: 0 auto; }
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .btn-back { display: inline-flex; align-items: center; gap: 6px; text-decoration: none; color: #0284c7; font-weight: 600; font-size: 14px; }
        .btn-back:hover { text-decoration: underline; }
        .card-main { background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 16px rgba(0,0,0,0.03); overflow: hidden; }
        .card-header { padding: 24px 30px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px; }
        .card-header h2 { font-size: 20px; font-weight: 700; color: #0f172a; }
        .card-header p { font-size: 13px; color: #64748b; margin-top: 2px; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { background: #f8fafc; padding: 14px 16px; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #e2e8f0; }
        td { padding: 14px 16px; border-bottom: 1px solid #f1f5f9; font-size: 13px; vertical-align: middle; }
        tr:hover td { background: #fbfdff; }
        .student-photo-img { width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 2.5px solid #0284c7; box-shadow: 0 2px 8px rgba(2, 132, 199, 0.2); }
        .student-photo-avatar { width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 18px; border: 2.5px solid #e2e8f0; }
        .status-pill { padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; }
        .status-verified { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .status-pending { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
        .status-rejected { background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; }
        .doc-badge { display: inline-flex; align-items: center; gap: 4px; background: #e0f2fe; color: #0369a1; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; text-decoration: none; margin: 2px; }
        .doc-badge:hover { background: #bae6fd; }
        .btn-sm { padding: 5px 10px; border-radius: 5px; font-size: 11.5px; font-weight: 600; cursor: pointer; border: none; display: inline-flex; align-items: center; gap: 4px; }
        .btn-green { background: #10b981; color: #fff; }
        .btn-green:hover { background: #059669; }
        .btn-red { background: #ef4444; color: #fff; }
        .btn-red:hover { background: #dc2626; }
    </style>
</head>
<body>

<div class="page-wrap">
    <div class="top-bar">
        <div style="display: flex; align-items: center; gap: 16px;">
            <a href="Dashboard/admin.php" class="btn-back">
                <i class='bx bx-left-arrow-alt'></i> Back to Society Dashboard
            </a>
            <a href="../pg/admin/dashboard.php" class="btn-back" style="color: #059669;">
                <i class='bx bxs-institution'></i> Open PG Warden Dashboard &rarr;
            </a>
        </div>
        <div style="font-size: 13px; color: #64748b;">
            Yadav PG for Stay (Yadav.in) &bull; Owners: Binod Yadav &amp; Sumit Yadav
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
                <h2>Registered PG Students &amp; KYC Verification</h2>
                <p>Real-time view of student admission profiles, uploaded identity documents, and bed allocations</p>
            </div>
            <div>
                <a href="society_kyc.php" style="background: #e0f2fe; border: 1px solid #bae6fd; color: #0369a1; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                    <i class='bx bx-buildings'></i> View Society Resident KYC &rarr;
                </a>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Student &amp; Photo</th>
                        <th>Room &amp; Bed</th>
                        <th>Contact / College</th>
                        <th>Guardian Details</th>
                        <th>Uploaded KYC Documents</th>
                        <th>KYC Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($q_students) == 0): ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: #64748b;">
                                No student records registered yet.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php while ($s = mysqli_fetch_assoc($q_students)): ?>
                            <?php 
                                // Determine student photo url
                                $stu_photo = null;
                                if (!empty($s['photo']) && file_exists(__DIR__ . "/../pg/uploads/" . $s['photo'])) {
                                    $stu_photo = "../pg/uploads/" . $s['photo'];
                                } elseif (!empty($s['profile_pic']) && file_exists(__DIR__ . "/../pg/uploads/" . $s['profile_pic'])) {
                                    $stu_photo = "../pg/uploads/" . $s['profile_pic'];
                                } elseif (!empty($s['photo']) && file_exists(__DIR__ . "/../pg/assets/" . $s['photo']) && $s['photo'] !== 'yuvraj.png') {
                                    $stu_photo = "../pg/assets/" . $s['photo'];
                                }
                            ?>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <?php if (!empty($stu_photo)): ?>
                                            <a href="<?php echo htmlspecialchars($stu_photo); ?>" target="_blank" title="View Full Photo">
                                                <img src="<?php echo htmlspecialchars($stu_photo); ?>" alt="<?php echo htmlspecialchars($s['name']); ?>" class="student-photo-img">
                                            </a>
                                        <?php else: ?>
                                            <div class="student-photo-avatar">
                                                <?php echo strtoupper(substr($s['name'], 0, 1)); ?>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <strong style="color: #0f172a; font-size: 14px; display: block;"><?php echo htmlspecialchars($s['name']); ?></strong>
                                            <span style="font-size: 11.5px; color: #64748b;"><?php echo htmlspecialchars($s['email']); ?></span>
                                            <div style="font-size: 10.5px; color: #0284c7;">Registered: <?php echo date("d M Y", strtotime($s['joined_date'])); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php if (!empty($s['room_number'])): ?>
                                        <div style="font-weight: 700; color: #0284c7; font-size: 14px;">
                                            <?php echo htmlspecialchars($s['room_number']); ?> (<?php echo htmlspecialchars($s['bed_label'] ?? 'Bed'); ?>)
                                        </div>
                                        <div style="font-size: 11px; color: #64748b;"><?php echo ucfirst($s['occupancy_type'] ?? 'double'); ?> Sharing &bull; <?php echo htmlspecialchars($s['floor'] ?? ''); ?></div>
                                    <?php else: ?>
                                        <span style="background: #fef3c7; color: #b45309; padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                            <i class='bx bx-time-five'></i> Pending Allocation
                                        </span>
                                        <?php if (!empty($vacant_beds)): ?>
                                            <form method="POST" action="pg_students.php" style="margin-top: 6px; display: flex; gap: 4px;">
                                                <input type="hidden" name="assign_room_bed" value="1">
                                                <input type="hidden" name="student_id" value="<?php echo $s['id']; ?>">
                                                <select name="bed_id" required style="padding: 3px 6px; font-size: 11px; border: 1px solid #cbd5e1; border-radius: 4px; background: #fff; max-width: 140px;">
                                                    <option value="">-- Assign Bed --</option>
                                                    <?php foreach ($vacant_beds as $vba): ?>
                                                        <option value="<?php echo $vba['id']; ?>">
                                                            <?php echo htmlspecialchars($vba['room_number']); ?> (<?php echo htmlspecialchars($vba['bed_label']); ?>)
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <button type="submit" class="btn-sm btn-green" style="padding: 3px 6px; font-size: 10.5px;">Assign</button>
                                            </form>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div style="font-size: 12.5px; font-weight: 600; color: #334155;"><i class='bx bx-phone' style="color: #0284c7;"></i> <?php echo htmlspecialchars($s['phone']); ?></div>
                                    <div style="font-size: 11.5px; color: #64748b;"><?php echo htmlspecialchars($s['college']); ?></div>
                                    <div style="font-size: 11px; color: #94a3b8;"><i class='bx bx-map-pin'></i> <?php echo htmlspecialchars($s['location']); ?></div>
                                </td>
                                <td>
                                    <?php if (!empty($s['father_name'])): ?>
                                        <div style="font-weight: 600; color: #1e293b; font-size: 13px;"><?php echo htmlspecialchars($s['father_name']); ?></div>
                                        <div style="font-size: 11px; color: #64748b;"><i class='bx bx-phone'></i> <?php echo htmlspecialchars($s['father_mobile']); ?></div>
                                        <?php if (!empty($s['mother_name'])): ?>
                                            <div style="font-size: 11px; color: #94a3b8; margin-top: 2px;">Mother: <?php echo htmlspecialchars($s['mother_name']); ?></div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span style="font-size: 12px; color: #94a3b8; font-style: italic;">KYC Not Submitted</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div style="display: flex; flex-direction: column; gap: 4px;">
                                        <?php if (!empty($s['id_proof_front'])): ?>
                                            <a href="../pg/uploads/<?php echo htmlspecialchars($s['id_proof_front']); ?>" target="_blank" class="doc-badge">
                                                <i class='bx bx-id-card'></i> ID Front
                                            </a>
                                        <?php endif; ?>
                                        <?php if (!empty($s['id_proof_back'])): ?>
                                            <a href="../pg/uploads/<?php echo htmlspecialchars($s['id_proof_back']); ?>" target="_blank" class="doc-badge">
                                                <i class='bx bx-id-card'></i> ID Back
                                            </a>
                                        <?php endif; ?>
                                        <?php if (!empty($s['address_proof_front'])): ?>
                                            <a href="../pg/uploads/<?php echo htmlspecialchars($s['address_proof_front']); ?>" target="_blank" class="doc-badge">
                                                <i class='bx bx-file'></i> Address Front
                                            </a>
                                        <?php endif; ?>
                                        <?php if (!empty($s['address_proof_back'])): ?>
                                            <a href="../pg/uploads/<?php echo htmlspecialchars($s['address_proof_back']); ?>" target="_blank" class="doc-badge">
                                                <i class='bx bx-file'></i> Address Back
                                            </a>
                                        <?php endif; ?>
                                        <?php if (empty($s['id_proof_front']) && empty($s['address_proof_front'])): ?>
                                            <span style="font-size: 11px; color: #94a3b8; font-style: italic;">No docs uploaded</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <?php 
                                        $k_st = $s['kyc_status'] ?? 'Pending';
                                        $pill_cls = ($k_st === 'Verified') ? 'status-verified' : (($k_st === 'Rejected') ? 'status-rejected' : 'status-pending');
                                    ?>
                                    <span class="status-pill <?php echo $pill_cls; ?>">
                                        <i class='bx <?php echo ($k_st === 'Verified') ? 'bx-check-shield' : 'bx-time-five'; ?>'></i>
                                        <?php echo strtoupper($k_st); ?>
                                    </span>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 4px;">
                                        <form method="POST" action="pg_students.php" style="display: inline;">
                                            <input type="hidden" name="update_kyc_status" value="1">
                                            <input type="hidden" name="student_id" value="<?php echo $s['id']; ?>">
                                            <input type="hidden" name="status" value="Verified">
                                            <button type="submit" class="btn-sm btn-green" title="Approve KYC">
                                                <i class='bx bx-check'></i> Verify
                                            </button>
                                        </form>
                                        <form method="POST" action="pg_students.php" style="display: inline;">
                                            <input type="hidden" name="update_kyc_status" value="1">
                                            <input type="hidden" name="student_id" value="<?php echo $s['id']; ?>">
                                            <input type="hidden" name="status" value="Rejected">
                                            <button type="submit" class="btn-sm btn-red" title="Reject KYC" onclick="return confirm('Reject KYC for this student?');">
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
