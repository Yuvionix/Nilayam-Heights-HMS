<?php
require_once(__DIR__ . "/../Includes/session.php");
require_pg_role('admin', '../login.php');

$active_page = basename($_SERVER['PHP_SELF'], ".php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'PG Management'; ?> | Yadav PG for Stay Warden Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body {
            background-color: #f8fafc;
            min-height: 100vh;
            color: #1e293b;
        }
        .admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 250px;
            background: #0f172a;
            z-index: 1000;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 12px rgba(0,0,0,0.1);
        }
        .admin-logo {
            padding: 22px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #ffffff;
            font-size: 18px;
            font-weight: 700;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            text-decoration: none;
        }
        .admin-logo i {
            font-size: 24px;
            color: #38bdf8;
        }
        .admin-nav-list {
            list-style: none;
            padding: 16px 12px;
            flex: 1;
            overflow-y: auto;
        }
        .admin-nav-item {
            margin-bottom: 4px;
        }
        .admin-nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 16px;
            color: #94a3b8;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        .admin-nav-link:hover, .admin-nav-link.active {
            background: rgba(99, 102, 241, 0.15);
            color: #ffffff;
        }
        .admin-nav-link.active i {
            color: #38bdf8;
        }
        .admin-nav-link i {
            font-size: 18px;
            min-width: 22px;
        }
        .admin-header {
            position: fixed;
            top: 0;
            left: 250px;
            right: 0;
            height: 64px;
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            z-index: 900;
        }
        .admin-main-content {
            margin-left: 250px;
            padding: 88px 32px 32px 32px;
        }
        .admin-stat-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(15,23,42,0.03);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .badge-occupancy {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }
    </style>
</head>
<body>

    <aside class="admin-sidebar">
        <a href="dashboard.php" class="admin-logo">
            <i class='bx bxs-institution' style='color: #38bdf8;'></i>
            <span>YADAV PG FOR STAY</span>
        </a>

        <ul class="admin-nav-list">
            <li class="admin-nav-item">
                <a href="dashboard.php" class="admin-nav-link <?php echo ($active_page === 'dashboard') ? 'active' : ''; ?>">
                    <i class='bx bx-grid-alt'></i> PG Overview
                </a>
            </li>
            <li class="admin-nav-item">
                <a href="rooms.php" class="admin-nav-link <?php echo ($active_page === 'rooms') ? 'active' : ''; ?>">
                    <i class='bx bx-bed'></i> Rooms & Beds Matrix
                </a>
            </li>
            <li class="admin-nav-item">
                <a href="students.php" class="admin-nav-link <?php echo ($active_page === 'students') ? 'active' : ''; ?>">
                    <i class='bx bx-user-pin'></i> Student Residents
                </a>
            </li>
            <li class="admin-nav-item">
                <a href="complaints_mgmt.php" class="admin-nav-link <?php echo ($active_page === 'complaints_mgmt') ? 'active' : ''; ?>">
                    <i class='bx bx-message-square-error'></i> Maintenance Complaints
                </a>
            </li>
            <li class="admin-nav-item">
                <a href="vacate_mgmt.php" class="admin-nav-link <?php echo ($active_page === 'vacate_mgmt') ? 'active' : ''; ?>">
                    <i class='bx bx-calendar-x'></i> Vacate Notices
                </a>
            </li>
            <li class="admin-nav-item">
                <a href="announcements.php" class="admin-nav-link <?php echo ($active_page === 'announcements') ? 'active' : ''; ?>">
                    <i class='bx bx-broadcast'></i> Broadcast Notice
                </a>
            </li>
        </ul>

        <div style="padding: 16px; border-top: 1px solid rgba(255,255,255,0.08);">
            <a href="../../admin/Dashboard/admin.php" class="admin-nav-link" style="color: #38bdf8;">
                <i class='bx bx-buildings'></i> Housing Society Portal
            </a>
            <a href="../logout.php" class="admin-nav-link" style="color: #ef4444; margin-top: 4px;">
                <i class='bx bx-log-out'></i> Log Out
            </a>
        </div>
    </aside>

    <header class="admin-header">
        <div style="display: flex; align-items: center; gap: 14px;">
            <span style="font-weight: 700; color: #0f172a; font-size: 16px;">Yadav PG for Stay &bull; Boy's Student Living Desk</span>
            <span style="background: linear-gradient(135deg, #fef3c7, #fde68a); color: #92400e; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; display: inline-flex; align-items: center; gap: 5px; border: 1px solid #fcd34d; box-shadow: 0 2px 6px rgba(245, 158, 11, 0.15);">
                <i class='bx bxs-sun' style='color:#f59e0b;'></i> जय श्री कृष्णा
            </span>
        </div>

        <div style="display: flex; align-items: center; gap: 14px;">
            <div style="text-align: right;">
                <div style="font-size: 13px; font-weight: 700; color: #0f172a;">Yuvraj (Warden / Admin)</div>
                <div style="font-size: 11px; color: #38bdf8; font-weight: 600;">Full Access</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 50%; background: #0099e5; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                Y
            </div>
        </div>
    </header>

    <main class="admin-main-content">
