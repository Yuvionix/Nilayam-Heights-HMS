<?php
$page_title = "Payments & Receipts";
require_once("header.php");

$q_pay = mysqli_query($con, "SELECT * FROM pg_payments WHERE student_id = $current_student_id ORDER BY id DESC");
?>

<div class="page-title-banner">
    <div>
        <h2>PAYMENTS & DIGITAL RECEIPTS</h2>
        <p style="font-size: 13px; color: #64748b; margin-top: 2px;">Payment history, security deposit record, and digital receipts.</p>
    </div>
    <div style="background: #e0f2fe; color: #0369a1; padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 600;">
        <i class='bx bx-info-circle'></i> Stay Charges as Agreed with Owners Binod Yadav & Sumit Yadav
    </div>
</div>

<div class="pg-table-card">
    <table class="pg-table">
        <thead>
            <tr>
                <th>Billing Period</th>
                <th>Amount Paid</th>
                <th>Payment Date</th>
                <th>Payment Method</th>
                <th>Transaction Ref</th>
                <th style="text-align: right;">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($q_pay) === 0): ?>
                <tr>
                    <td colspan="6" class="no-data-cell">No payment history found.</td>
                </tr>
            <?php else: ?>
                <?php while ($p = mysqli_fetch_assoc($q_pay)): ?>
                    <tr>
                        <td style="font-weight: 600; color: #0f172a;">
                            <?php echo htmlspecialchars($p['month_for']); ?>
                        </td>
                        <td style="font-weight: 700; color: #059669;">
                            ₹<?php echo number_format($p['amount']); ?>
                        </td>
                        <td style="color: #64748b; white-space: nowrap;">
                            <?php echo date("d/m/Y h:i A", strtotime($p['payment_date'])); ?>
                        </td>
                        <td>
                            <span style="display: inline-flex; align-items: center; gap: 4px; font-size: 12px; color: #475569;">
                                <i class='bx bx-check-circle' style="color: #10b981;"></i> <?php echo htmlspecialchars($p['payment_method']); ?>
                            </span>
                        </td>
                        <td style="font-family: monospace; font-size: 12px; color: #64748b;">
                            <?php echo htmlspecialchars($p['transaction_ref']); ?>
                        </td>
                        <td style="text-align: right;">
                            <span style="background: #dcfce7; color: #15803d; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700;">
                                <?php echo htmlspecialchars($p['status']); ?>
                            </span>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div style="margin-top: 24px; background: #ffffff; border-radius: 8px; border: 1px solid #e2e8f0; padding: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
    <div>
        <h4 style="font-size: 14px; font-weight: 700; color: #0f172a;">Security Deposit On File</h4>
        <p style="font-size: 12px; color: #64748b; margin-top: 2px;">Refundable security deposit maintained with Owners Binod Yadav & Sumit Yadav (Settled as per agreed terms upon move-in).</p>
    </div>
    <button type="button" class="btn-create-action" onclick="alert('For UPI Payments, please scan the QR Code at the reception or pay to UPI ID: yadavpg@upi and share the screenshot with Owners Binod Yadav & Sumit Yadav.')">
        <i class='bx bx-qr-scan'></i> Pay via UPI
    </button>
</div>

<?php require_once("footer.php"); ?>
