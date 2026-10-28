<?php
$page_title = "PG Overview";
require_once("header.php");

// Handle Student Admission form submission
$msg = "";
$msg_type = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admit_student'])) {
    $name = trim(isset($_POST['name']) ? $_POST['name'] : '');
    $email = trim(isset($_POST['email']) ? $_POST['email'] : '');
    $phone = trim(isset($_POST['phone']) ? $_POST['phone'] : '');
    $college = trim(isset($_POST['college']) ? $_POST['college'] : 'AS College');
    $bed_id = (int)(isset($_POST['bed_id']) ? $_POST['bed_id'] : 0);
    $rent_amount = (float)(isset($_POST['rent_amount']) ? $_POST['rent_amount'] : 12000);
    $deposit_amount = (float)(isset($_POST['deposit_amount']) ? $_POST['deposit_amount'] : 12000);

    if (!empty($name) && !empty($email) && !empty($phone) && $bed_id > 0) {
        // Fetch room_id from bed
        $q_b = mysqli_query($con, "SELECT room_id FROM pg_beds WHERE id = $bed_id AND status = 'vacant' LIMIT 1");
        if ($bed_row = mysqli_fetch_assoc($q_b)) {
            $room_id = (int)$bed_row['room_id'];
            $username = strtolower(explode('@', $email)[0]) . rand(10, 99);
            $hashed = password_hash('password', PASSWORD_DEFAULT);

            $ins = mysqli_prepare($con, "INSERT INTO pg_students (name, username, email, password, phone, college, room_id, bed_id, rent_amount, deposit_amount, joined_date, status, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURDATE(), 'active', 'Yuvraj (Warden)')");
            if ($ins) {
                mysqli_stmt_bind_param($ins, "ssssssiidd", $name, $username, $email, $hashed, $phone, $college, $room_id, $bed_id, $rent_amount, $deposit_amount);
                if (mysqli_stmt_execute($ins)) {
                    $new_student_id = mysqli_insert_id($con);
                    // Mark bed as occupied
                    mysqli_query($con, "UPDATE pg_beds SET student_id = $new_student_id, status = 'occupied' WHERE id = $bed_id");
                    $msg = "Student $name admitted successfully! Assigned Bed ID #$bed_id. Default login password is 'password'.";
                    $msg_type = "success";
                } else {
                    $msg = "Error creating student profile: email may already exist.";
                    $msg_type = "danger";
                }
                mysqli_stmt_close($ins);
            }
        } else {
            $msg = "Selected bed is no longer vacant. Please select another bed.";
            $msg_type = "danger";
        }
    } else {
        $msg = "Please fill all required student details and select a bed.";
        $msg_type = "danger";
    }
}

// Compute Metrics
$total_rooms = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as cnt FROM pg_rooms"))['cnt'];
$total_beds = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as cnt FROM pg_beds"))['cnt'];
$occupied_beds = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as cnt FROM pg_beds WHERE status = 'occupied'"))['cnt'];
$vacant_beds = $total_beds - $occupied_beds;
$total_revenue = mysqli_fetch_assoc(mysqli_query($con, "SELECT SUM(rent_amount) as rev FROM pg_students WHERE status = 'active'"))['rev'];
$pending_comp = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as cnt FROM pg_complaints WHERE status = 'Pending'"))['cnt'];
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h2 style="font-size: 20px; font-weight: 700; color: #0f172a;">Student Boy's PG Overview & Occupancy</h2>
        <p style="font-size: 13px; color: #64748b; margin-top: 2px;">Managing Single, Double, and Triple occupancy student rooms.</p>
    </div>
    <button type="button" onclick="openAdmitModal()" style="background: #0099e5; color: #fff; border: none; padding: 10px 22px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
        <i class='bx bx-user-plus'></i> Admit New Student
    </button>
</div>

<?php if (!empty($msg)): ?>
    <div style="padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-size: 13px; font-weight: 500; <?php echo $msg_type === 'success' ? 'background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0;' : 'background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca;'; ?>">
        <?php echo htmlspecialchars($msg); ?>
    </div>
<?php endif; ?>

<!-- Stat KPI Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; margin-bottom: 28px;">
    <div class="admin-stat-card">
        <div>
            <div style="font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase;">Total Rooms</div>
            <div style="font-size: 26px; font-weight: 700; color: #0f172a; margin-top: 4px;"><?php echo $total_rooms; ?> Rooms</div>
            <div style="font-size: 11px; color: #64748b;">Across 4 Floors</div>
        </div>
        <div style="width: 44px; height: 44px; border-radius: 10px; background: #f0fdf4; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 22px;">
            <i class='bx bx-building'></i>
        </div>
    </div>

    <div class="admin-stat-card">
        <div>
            <div style="font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase;">Occupied Beds</div>
            <div style="font-size: 26px; font-weight: 700; color: #0284c7; margin-top: 4px;"><?php echo $occupied_beds; ?> / <?php echo $total_beds; ?></div>
            <div style="font-size: 11px; color: #0284c7; font-weight: 600;"><?php echo round(($occupied_beds / $total_beds) * 100); ?>% Occupancy Rate</div>
        </div>
        <div style="width: 44px; height: 44px; border-radius: 10px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 22px;">
            <i class='bx bx-user-check'></i>
        </div>
    </div>

    <div class="admin-stat-card">
        <div>
            <div style="font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase;">Available Vacant Beds</div>
            <div style="font-size: 26px; font-weight: 700; color: #16a34a; margin-top: 4px;"><?php echo $vacant_beds; ?> Vacant</div>
            <div style="font-size: 11px; color: #16a34a; font-weight: 600;">Ready for Immediate Move-in</div>
        </div>
        <div style="width: 44px; height: 44px; border-radius: 10px; background: #dcfce7; color: #15803d; display: flex; align-items: center; justify-content: center; font-size: 22px;">
            <i class='bx bx-bed'></i>
        </div>
    </div>

    <div class="admin-stat-card">
        <div>
            <div style="font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase;">Total Active Collections</div>
            <div style="font-size: 26px; font-weight: 700; color: #059669; margin-top: 4px;">₹<?php echo number_format($total_revenue ?? 35000); ?></div>
            <div style="font-size: 11px; color: #64748b;">Active Resident Inflow (Custom)</div>
        </div>
        <div style="width: 44px; height: 44px; border-radius: 10px; background: #ecfdf5; color: #059669; display: flex; align-items: center; justify-content: center; font-size: 22px;">
            <i class='bx bx-rupee'></i>
        </div>
    </div>
</div>

<!-- Room & Bed Allocation Matrix -->
<div style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 24px; margin-bottom: 28px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
        <h3 style="font-size: 16px; font-weight: 700; color: #0f172a;">Room & Bed Occupancy Matrix (Single, Double & Triple Sharing)</h3>
        <div style="display: flex; gap: 12px; font-size: 12px;">
            <span style="display: inline-flex; align-items: center; gap: 4px; color: #15803d;"><span style="width: 10px; height: 10px; border-radius: 50%; background: #10b981;"></span> Vacant Bed</span>
            <span style="display: inline-flex; align-items: center; gap: 4px; color: #0284c7;"><span style="width: 10px; height: 10px; border-radius: 50%; background: #0099e5;"></span> Occupied Bed</span>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 16px;">
        <?php
        $rooms_q = mysqli_query($con, "SELECT * FROM pg_rooms ORDER BY room_number ASC");
        while ($rm = mysqli_fetch_assoc($rooms_q)):
            $rm_id = $rm['id'];
            $beds_q = mysqli_query($con, "SELECT b.*, s.name as student_name, s.phone as student_phone 
                                          FROM pg_beds b 
                                          LEFT JOIN pg_students s ON b.student_id = s.id 
                                          WHERE b.room_id = $rm_id ORDER BY b.bed_label ASC");
        ?>
        <div style="border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 16px; background: #f8fafc;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <div>
                    <strong style="font-size: 15px; color: #0f172a;"><?php echo htmlspecialchars($rm['room_number']); ?></strong>
                    <span style="font-size: 11px; color: #64748b; margin-left: 6px;"><?php echo htmlspecialchars($rm['floor']); ?></span>
                </div>
                <span class="badge-occupancy" style="background: <?php echo $rm['occupancy_type'] === 'single' ? '#fef3c7; color: #b45309;' : ($rm['occupancy_type'] === 'double' ? '#e0f2fe; color: #0369a1;' : '#f3e8ff; color: #7e22ce;'); ?>">
                    <?php echo ucfirst($rm['occupancy_type']); ?> Sharing
                </span>
            </div>

            <!-- Beds Inside Room -->
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <?php while ($bed = mysqli_fetch_assoc($beds_q)): ?>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 12px; background: #ffffff; border-radius: 6px; border: 1px solid #e2e8f0;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <i class='bx bx-bed' style="font-size: 18px; color: <?php echo $bed['status'] === 'occupied' ? '#0099e5' : '#10b981'; ?>;"></i>
                            <span style="font-size: 12px; font-weight: 600; color: #334155;"><?php echo htmlspecialchars($bed['bed_label']); ?></span>
                        </div>

                        <?php if ($bed['status'] === 'occupied'): ?>
                            <div style="text-align: right;">
                                <div style="font-size: 12px; font-weight: 700; color: #0284c7;"><?php echo htmlspecialchars($bed['student_name']); ?></div>
                                <div style="font-size: 10px; color: #64748b;"><i class='bx bx-phone'></i> <?php echo htmlspecialchars($bed['student_phone']); ?></div>
                            </div>
                        <?php else: ?>
                            <button type="button" onclick="admitToBed(<?php echo $bed['id']; ?>, '<?php echo htmlspecialchars($rm['room_number']); ?> - <?php echo htmlspecialchars($bed['bed_label']); ?>', <?php echo $rm['monthly_rent']; ?>)" style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; cursor: pointer;">
                                + Assign Bed
                            </button>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Modal: Admit Student -->
<div id="admitModal" style="display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.6); backdrop-filter: blur(4px); z-index: 2000; align-items: center; justify-content: center;">
    <div style="background: #ffffff; width: 92%; max-width: 500px; border-radius: 14px; overflow: hidden; box-shadow: 0 25px 50px rgba(0,0,0,0.25);">
        <div style="padding: 16px 22px; background: #0099e5; color: #fff; display: flex; justify-content: space-between; align-items: center;">
            <h4 style="margin: 0; font-size: 16px; font-weight: 600;"><i class='bx bx-user-plus'></i> Admit Student to PG</h4>
            <button type="button" onclick="closeAdmitModal()" style="background: none; border: none; color: #fff; font-size: 24px; cursor: pointer;">&times;</button>
        </div>
        <form method="POST" action="dashboard.php" style="padding: 22px;">
            <div style="margin-bottom: 14px;">
                <label style="font-size: 12px; font-weight: 600; color: #334155;">Student Full Name *</label>
                <input type="text" name="name" placeholder="e.g. Rahul Sharma" required style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1.5px solid #cbd5e1; font-size: 13px; margin-top: 4px;">
            </div>

            <div style="margin-bottom: 14px;">
                <label style="font-size: 12px; font-weight: 600; color: #334155;">Email Address *</label>
                <input type="email" name="email" placeholder="e.g. rahul.sharma@gmail.com" required style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1.5px solid #cbd5e1; font-size: 13px; margin-top: 4px;">
            </div>

            <div style="margin-bottom: 14px;">
                <label style="font-size: 12px; font-weight: 600; color: #334155;">Mobile Phone Number *</label>
                <input type="tel" name="phone" placeholder="e.g. 9835123456" required style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1.5px solid #cbd5e1; font-size: 13px; margin-top: 4px;">
            </div>

            <div style="margin-bottom: 14px;">
                <label style="font-size: 12px; font-weight: 600; color: #334155;">College / Coaching Name</label>
                <input type="text" name="college" placeholder="e.g. AS College / Allen Career" value="AS College Deoghar" style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1.5px solid #cbd5e1; font-size: 13px; margin-top: 4px;">
            </div>

            <div style="margin-bottom: 14px;">
                <label style="font-size: 12px; font-weight: 600; color: #334155;">Select Vacant Bed *</label>
                <select name="bed_id" id="modalBedSelect" required style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1.5px solid #cbd5e1; font-size: 13px; margin-top: 4px;">
                    <?php
                    $vac_q = mysqli_query($con, "SELECT b.id, b.bed_label, r.room_number, r.floor, r.occupancy_type 
                                                FROM pg_beds b 
                                                JOIN pg_rooms r ON b.room_id = r.id 
                                                WHERE b.status = 'vacant' 
                                                ORDER BY r.room_number ASC, b.bed_label ASC");
                    while ($v = mysqli_fetch_assoc($vac_q)):
                    ?>
                        <option value="<?php echo $v['id']; ?>">
                            <?php echo htmlspecialchars($v['room_number']); ?> (<?php echo htmlspecialchars($v['bed_label']); ?>) &bull; <?php echo ucfirst($v['occupancy_type']); ?> Sharing &bull; <?php echo htmlspecialchars($v['floor']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px;">
                <div>
                    <label style="font-size: 12px; font-weight: 600; color: #334155;">Agreed Rent / Month (₹)</label>
                    <input type="number" id="modalRentAmount" name="rent_amount" placeholder="Owner custom rate" required style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1.5px solid #cbd5e1; font-size: 13px; margin-top: 4px;">
                </div>
                <div>
                    <label style="font-size: 12px; font-weight: 600; color: #334155;">Agreed Security Deposit (₹)</label>
                    <input type="number" id="modalDepositAmount" name="deposit_amount" placeholder="Owner deposit term" required style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1.5px solid #cbd5e1; font-size: 13px; margin-top: 4px;">
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeAdmitModal()" style="padding: 10px 18px; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; font-size: 13px; font-weight: 600; cursor: pointer;">Cancel</button>
                <button type="submit" name="admit_student" style="padding: 10px 22px; border-radius: 8px; border: none; background: #0099e5; color: #fff; font-size: 13px; font-weight: 600; cursor: pointer;">
                    Confirm Admission
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAdmitModal() {
        document.getElementById('admitModal').style.display = 'flex';
    }
    function closeAdmitModal() {
        document.getElementById('admitModal').style.display = 'none';
    }
    function admitToBed(bedId, label, rent) {
        document.getElementById('admitModal').style.display = 'flex';
        const sel = document.getElementById('modalBedSelect');
        sel.value = bedId;
        document.getElementById('modalRentAmount').value = rent;
        document.getElementById('modalDepositAmount').value = rent;
    }
</script>

<?php require_once("footer.php"); ?>
