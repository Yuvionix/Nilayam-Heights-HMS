<?php
$page_title = "Announcements";
require_once("header.php");

$q_ann = mysqli_query($con, "SELECT * FROM pg_announcements ORDER BY id DESC");
?>

<div class="page-title-banner">
    <h2>PG ANNOUNCEMENTS & NOTICES</h2>
</div>

<div style="display: flex; flex-direction: column; gap: 16px;">
    <?php if (mysqli_num_rows($q_ann) === 0): ?>
        <div class="pg-table-card" style="padding: 40px; text-align: center; color: #64748b;">
            <i class='bx bx-bell-off' style="font-size: 40px; color: #94a3b8; margin-bottom: 8px;"></i>
            <p>No active announcements posted yet.</p>
        </div>
    <?php else: ?>
        <?php while ($ann = mysqli_fetch_assoc($q_ann)): ?>
            <div style="background: #ffffff; border-radius: 10px; border: 1px solid #e2e8f0; padding: 22px 26px; box-shadow: 0 2px 6px rgba(0,0,0,0.02); position: relative; overflow: hidden;">
                <?php if ($ann['importance'] === 'urgent'): ?>
                    <div style="position: absolute; left: 0; top: 0; bottom: 0; width: 4px; background: #ef4444;"></div>
                <?php else: ?>
                    <div style="position: absolute; left: 0; top: 0; bottom: 0; width: 4px; background: #0099e5;"></div>
                <?php endif; ?>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <div style="font-size: 12px; color: #64748b; font-weight: 500; display: flex; align-items: center; gap: 6px;">
                        <i class='bx bx-calendar'></i> <?php echo date("l, d M Y - h:i A", strtotime($ann['created_at'])); ?>
                    </div>
                    <?php if ($ann['importance'] === 'urgent'): ?>
                        <span style="background: #fee2e2; color: #b91c1c; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 20px;">
                            <i class='bx bx-error-circle'></i> URGENT
                        </span>
                    <?php else: ?>
                        <span style="background: #e0f2fe; color: #0369a1; font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px;">
                            NOTICE
                        </span>
                    <?php endif; ?>
                </div>

                <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">
                    <?php echo htmlspecialchars($ann['title']); ?>
                </h3>

                <p style="font-size: 13px; color: #475569; line-height: 1.6; margin: 0;">
                    <?php echo nl2br(htmlspecialchars($ann['content'])); ?>
                </p>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

<?php require_once("footer.php"); ?>
