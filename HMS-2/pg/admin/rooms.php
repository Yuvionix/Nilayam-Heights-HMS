<?php
$page_title = "Rooms & Beds";
require_once("header.php");

$rooms_q = mysqli_query($con, "SELECT r.*, 
                               (SELECT COUNT(*) FROM pg_beds WHERE room_id = r.id) as bed_count,
                               (SELECT COUNT(*) FROM pg_beds WHERE room_id = r.id AND status = 'occupied') as occupied_count
                               FROM pg_rooms r ORDER BY r.room_number ASC");
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 14px;">
    <div>
        <h2 style="font-size: 20px; font-weight: 700; color: #0f172a;">Rooms & Sharing Configuration</h2>
        <p style="font-size: 13px; color: #64748b; margin-top: 2px;">Manage Single, Double, and Triple occupancy rooms & see joined students across all floors.</p>
    </div>
    <a href="dashboard.php" style="background: #0099e5; color: #fff; padding: 9px 18px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
        <i class='bx bx-grid-alt'></i> View Occupancy Matrix
    </a>
</div>

<div class="pg-table-card" style="background:#fff; border-radius:10px; border:1px solid #e2e8f0; overflow-x:auto;">
    <table style="width:100%; border-collapse:collapse; text-align:left; min-width: 850px;">
        <thead>
            <tr style="background:#f8fafc; border-bottom:1.5px solid #e2e8f0;">
                <th style="padding:14px 18px; font-size:12px; color:#64748b;">Room No</th>
                <th style="padding:14px 18px; font-size:12px; color:#64748b;">Floor</th>
                <th style="padding:14px 18px; font-size:12px; color:#64748b;">Occupancy Type</th>
                <th style="padding:14px 18px; font-size:12px; color:#64748b;">Joined Resident Students</th>
                <th style="padding:14px 18px; font-size:12px; color:#64748b;">Pricing / Terms</th>
                <th style="padding:14px 18px; font-size:12px; color:#64748b; text-align:right;">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($r = mysqli_fetch_assoc($rooms_q)): 
                $rm_id = (int)$r['id'];
                $beds_in_rm = mysqli_query($con, "SELECT b.bed_label, b.status, s.id as stu_id, s.name as stu_name, s.phone 
                                                  FROM pg_beds b 
                                                  LEFT JOIN pg_students s ON b.student_id = s.id 
                                                  WHERE b.room_id = $rm_id 
                                                  ORDER BY b.bed_label ASC");
            ?>
                <tr style="border-bottom:1px solid #f1f5f9;">
                    <td style="padding:14px 18px; font-weight:700; color:#0f172a; font-size:14px;">
                        <i class='bx bx-door-open' style="color: #0099e5; font-size: 16px;"></i> <?php echo htmlspecialchars($r['room_number']); ?>
                    </td>
                    <td style="padding:14px 18px; color:#475569; font-size:13px;">
                        <?php echo htmlspecialchars($r['floor']); ?>
                    </td>
                    <td style="padding:14px 18px;">
                        <span style="background:<?php echo $r['occupancy_type'] === 'single' ? '#fef3c7; color:#b45309;' : ($r['occupancy_type'] === 'double' ? '#e0f2fe; color:#0369a1;' : '#f3e8ff; color:#7e22ce;'); ?>; padding:4px 12px; border-radius:20px; font-size:11px; font-weight:700;">
                            <?php echo strtoupper($r['occupancy_type']); ?> OCCUPANCY
                        </span>
                    </td>
                    <td style="padding:14px 18px;">
                        <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                            <?php while ($bd = mysqli_fetch_assoc($beds_in_rm)): ?>
                                <?php if ($bd['status'] === 'occupied' && !empty($bd['stu_name'])): ?>
                                    <span style="background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; padding: 4px 10px; border-radius: 12px; font-size: 11.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;" title="Phone: <?php echo htmlspecialchars($bd['phone']); ?>">
                                        <i class='bx bx-user-check' style="color: #0284c7;"></i> <?php echo htmlspecialchars($bd['bed_label']); ?>: <b><?php echo htmlspecialchars($bd['stu_name']); ?></b>
                                    </span>
                                <?php else: ?>
                                    <span style="background: #f1f5f9; color: #64748b; border: 1px dashed #cbd5e1; padding: 4px 10px; border-radius: 12px; font-size: 11px;">
                                        <i class='bx bx-bed'></i> <?php echo htmlspecialchars($bd['bed_label']); ?>: Vacant
                                    </span>
                                <?php endif; ?>
                            <?php endwhile; ?>
                        </div>
                    </td>
                    <td style="padding:14px 18px;">
                        <span style="color: #0369a1; font-weight: 600; font-size: 12px; background: #e0f2fe; padding: 4px 10px; border-radius: 12px;">
                            <i class='bx bx-user-voice'></i> Owner Defined
                        </span>
                    </td>
                    <td style="padding:14px 18px; text-align:right;">
                        <?php if ($r['occupied_count'] === $r['bed_count']): ?>
                            <span style="background:#fee2e2; color:#b91c1c; font-size:11px; font-weight:700; padding:4px 10px; border-radius:20px;">FULL</span>
                        <?php else: ?>
                            <span style="background:#dcfce7; color:#15803d; font-size:11px; font-weight:700; padding:4px 10px; border-radius:20px;"><?php echo ($r['bed_count'] - $r['occupied_count']); ?> VACANT</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require_once("footer.php"); ?>
