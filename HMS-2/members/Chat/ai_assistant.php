<?php 
  require_once("../../Includes/config.php"); 
  require_once("../../Includes/session.php"); 
  require_role(array('member', 'admin'), '../../login.php');
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Society AI Agent | Resident Portal</title>
    <link rel="shortcut icon" href="../Dashboard/Logo3.jpg">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../../css/master_theme.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .chat-container {
            max-width: 960px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
            border: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            height: calc(100vh - 120px);
            overflow: hidden;
        }
        .chat-header {
            padding: 18px 24px;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .chat-header-info {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .ai-avatar {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #fff;
            box-shadow: 0 4px 14px rgba(139, 92, 246, 0.4);
        }
        .chat-header h3 {
            margin: 0;
            font-size: 17px;
            font-weight: 700;
        }
        .chat-header span {
            font-size: 12px;
            color: #94a3b8;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .online-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #10b981;
            box-shadow: 0 0 8px #10b981;
        }
        .chat-messages {
            flex: 1;
            padding: 24px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 16px;
            background: #f8fafc;
        }
        .msg {
            max-width: 80%;
            padding: 14px 18px;
            border-radius: 16px;
            font-size: 14px;
            line-height: 1.6;
            animation: fadeIn 0.25s ease;
        }
        .msg-ai {
            align-self: flex-start;
            background: #ffffff;
            color: #0f172a;
            border: 1px solid #e2e8f0;
            border-bottom-left-radius: 4px;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);
        }
        .msg-user {
            align-self: flex-end;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: #ffffff;
            border-bottom-right-radius: 4px;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.25);
        }
        .quick-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            padding: 12px 24px;
            background: #ffffff;
            border-top: 1px solid #f1f5f9;
        }
        .chip {
            background: #f1f5f9;
            color: #475569;
            font-size: 12px;
            font-weight: 500;
            padding: 6px 14px;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid #e2e8f0;
        }
        .chip:hover {
            background: #e0e7ff;
            color: #4338ca;
            border-color: #c7d2fe;
            transform: translateY(-1px);
        }
        .chat-input-bar {
            padding: 16px 24px;
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .chat-input-bar input {
            flex: 1;
            padding: 12px 18px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s ease;
            font-family: inherit;
        }
        .chat-input-bar input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
        }
        .send-btn {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            border: none;
            color: #fff;
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.3);
            transition: transform 0.2s ease;
        }
        .send-btn:hover {
            transform: scale(1.05);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo-details">
            <i><img src="logo.svg" alt="logo" width="46" height="46"></i>
            <span class="logo_name">HMS</span>
        </div>
        <ul class="nav-links">
            <li>
                <a href="../Dashboard/member.php">
                    <i class='bx bx-grid-alt'></i>
                    <span class="links_name">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="../Announcement/member_annou.php">
                    <i class='bx bx-bell'></i>
                    <span class="links_name">Announcement</span>
                </a>
            </li>
            <li>
                <a href="../Bill/index.php">
                    <i class='bx bx-pie-chart-alt-2'></i>
                    <span class="links_name">Maintainence Bill</span>
                </a>
            </li>
            <li>
                <a href="../Home_Services/home_ser.php">
                    <i class='bx bxs-user-circle'></i>
                    <span class="links_name">Home Service</span>
                </a>
            </li>
            <li>
                <a href="../Neighbourhood/member_neigh.php">
                    <i class='bx bx-map'></i>
                    <span class="links_name">Neighbourhood</span>
                </a>
            </li>
            <li>
                <a href="../Members/index.php">
                    <i class='bx bx-list-ul'></i>
                    <span class="links_name">Members</span>
                </a>
            </li>
            <li>
                <a href="../Members/staff/index.php">
                    <i class='bx bx-list-ul'></i>
                    <span class="links_name">Staff</span>
                </a>
            </li>
            <li class="ai-agent-nav">
                <a href="ai_assistant.php" class="active">
                    <i class='bx bx-bot'></i>
                    <span class="links_name">Society AI Agent</span>
                    <span class="ai-badge">AI</span>
                </a>
            </li>
            <li>
                <a href="../Event_calender/member_event.php">
                    <i class='bx bx-calendar-event'></i>
                    <span class="links_name">Events</span>
                </a>
            </li>
            <li class="log_out">
                <a href="../../logout.php">
                    <i class='bx bx-log-out'></i>
                    <span class="links_name">Log out</span>
                </a>
            </li>
        </ul>
    </div>

    <section class="home-section">
        <nav>
            <div class="sidebar-button">
                <i class='bx bx-menu sidebarBtn'></i>
                <span class="dashboard">Resident AI Assistant</span>
            </div>
            <div class="profile-details">
                <div class="user-avatar-circle">
                    <?php echo strtoupper(substr(isset($_SESSION["username"]) ? $_SESSION["username"] : 'M', 0, 1)); ?>
                </div>
                <span class="admin_name"><?php echo isset($_SESSION["username"]) ? htmlspecialchars($_SESSION["username"]) : 'Resident'; ?></span>
            </div>
        </nav>

        <div class="home-content" style="padding: 92px 24px 24px 24px;">
            <div class="chat-container">
                <div class="chat-header">
                    <div class="chat-header-info">
                        <div class="ai-avatar"><i class='bx bx-bot'></i></div>
                        <div>
                            <h3>Deoghar Society AI Assistant</h3>
                            <span><div class="online-dot"></div> Ready to assist with Nilayam Heights inquiries</span>
                        </div>
                    </div>
                    <span style="font-size: 12px; background: rgba(255,255,255,0.12); padding: 4px 10px; border-radius: 8px;">Nilayam Heights, Deoghar</span>
                </div>

                <div class="chat-messages" id="chatMessages">
                    <div class="msg msg-ai">
                        👋 <b>Hello resident! I am your Society AI Assistant.</b><br>
                        Ask me anything about society guidelines, your maintenance bills, contacts for the electrician or plumber, gate timings, or nearby facilities in Deoghar!
                    </div>
                </div>

                <div class="quick-chips">
                    <span class="chip" onclick="askPrompt('What is the society address and nearby landmarks?')">📍 Society Address & Location</span>
                    <span class="chip" onclick="askPrompt('How is the maintenance bill calculated and paid?')">💰 Maintenance Bill Info</span>
                    <span class="chip" onclick="askPrompt('What are the gate timings and visitor rules?')">🚪 Gate & Visitor Timings</span>
                    <span class="chip" onclick="askPrompt('Who are the emergency and maintenance staff contacts?')">📞 Staff & Emergency Contacts</span>
                    <span class="chip" onclick="askPrompt('How do I book the community hall?')">🎉 Community Hall Booking</span>
                    <span class="chip" onclick="askPrompt('What is the water supply and solar backup schedule?')">⚡ Water & Solar Backup</span>
                </div>

                <div class="chat-input-bar">
                    <input type="text" id="userInput" placeholder="Ask any question about the society, bills, rules, staff, or location..." onkeypress="if(event.key==='Enter') sendMessage()">
                    <button class="send-btn" onclick="sendMessage()"><i class='bx bx-send'></i></button>
                </div>
            </div>
        </div>
    </section>

    <script>
        let sidebar = document.querySelector(".sidebar");
        let sidebarBtn = document.querySelector(".sidebarBtn");
        if(sidebarBtn) {
            sidebarBtn.onclick = function () {
                sidebar.classList.toggle("active");
                if (sidebar.classList.contains("active")) {
                    sidebarBtn.classList.replace("bx-menu", "bx-menu-alt-right");
                } else
                    sidebarBtn.classList.replace("bx-menu-alt-right", "bx-menu");
            }
        }

        const societyKnowledge = [
            {
                keywords: ['address', 'location', 'where', 'hirna', 'satsang nagar', 'deoghar', 'as college', 'landmark'],
                response: `📍 <b>Society Location & Official Addresses:</b><br><br>
                • <b>Main Residential Complex:</b><br>
                  <i>Nilayam Heights Housing Society, Hirna Road, Satsang Nagar, Deoghar, Jharkhand 814142</i><br><br>
                • <b>Academic & North Wing Gate:</b><br>
                  <i>AS College Rd, Satsang Nagar, Deoghar, Jharkhand 814116</i><br><br>
                • <b>Prominent Nearby Landmarks:</b><br>
                  - A.S. College Deoghar (Walking distance on AS College Rd)<br>
                  - Sri Sri Thakur Anukulchandra Satsang Ashram (500m)<br>
                  - Deoghar Town Police Outpost (Near Satsang Chowk)<br>
                  - AIIMS Deoghar & Sadar Hospital (3.5 km)`
            },
            {
                keywords: ['maintenance', 'bill', 'pay', 'amount', 'calculate', 'due', 'charge', 'invoice'],
                response: `💰 <b>Maintenance Billing Details:</b><br><br>
                • <b>Standard Monthly Breakdown:</b><br>
                  - Flat / Block Base Maintenance: <b>₹2,500/month</b> (includes 24x7 security, lift operation, gardening & common lighting)<br>
                  - Electricity Consumption: <b>₹12 per unit (kWh)</b><br>
                  - Water Supply & Tank Sanitization: <b>₹20 per unit (kL)</b><br><br>
                • <b>Due Date:</b> 10th of every calendar month.<br>
                • <b>How to Pay:</b> Navigate to the <a href="../Bill/index.php" style="color:#4f46e5;font-weight:600;">Maintenance Bill</a> section in your sidebar to view your detailed monthly invoice, reading history, and settlement receipt.`
            },
            {
                keywords: ['gate', 'timing', 'curfew', 'entry', 'visitor', 'guest', 'security', 'delivery'],
                response: `🚪 <b>Gate Timings & Visitor Protocols:</b><br><br>
                • <b>Main Gate (Hirna Road):</b> Open 24/7 with continuous CCTV monitoring and security guards.<br>
                • <b>North Gate (AS College Road):</b> Open 06:00 AM – 11:00 PM daily.<br>
                • <b>Visitors & Delivery Agents:</b> All non-residents must log entry in the digital Visitor Registry at the security desk.<br>
                • <b>Delivery Vehicles:</b> Amazon, Flipkart, Swiggy, and Zomato riders are permitted up to building lobbies after verification.`
            },
            {
                keywords: ['contact', 'staff', 'phone', 'number', 'plumber', 'electrician', 'guard', 'emergency', 'help'],
                response: `📞 <b>Society Staff & Emergency Directory:</b><br><br>
                • <b>Chief Security Guard:</b> Ramesh Kumar — <code>+91 98351 22334</code><br>
                • <b>Society Electrician & Solar:</b> Manoj Yadav — <code>+91 94311 54321</code><br>
                • <b>Master Plumber:</b> Suresh Mandal — <code>+91 98351 98765</code><br>
                • <b>Facility & Housekeeping:</b> Deepak Paswan — <code>+91 70041 87654</code><br>
                • <b>Society Administrator:</b> Yuvraj (<code>yuvrajyadav734@gmail.com</code>)<br><br>
                🚨 <b>City Emergency Hotlines:</b><br>
                - Police Control Room: <b>112</b> / Deoghar PS: <code>+91 6432 222201</code><br>
                - Medical Emergency / Ambulance: <b>108</b> / Sadar Hospital: <code>+91 6432 232750</code><br>
                - Fire Services: <b>101</b>`
            },
            {
                keywords: ['community hall', 'hall', 'book', 'event', 'party', 'celebration'],
                response: `🎉 <b>Community Hall Booking Guidelines:</b><br><br>
                • The Nilayam Community Hall (Capacity: 150 guests) is located on the 1st Floor of the clubhouse.<br>
                • <b>Booking Charge:</b> ₹1,500/day for cleaning and electricity surcharge.<br>
                • <b>Advance Notice:</b> Minimum 3 days before the event date.<br>
                • Please contact Society Administrator <b>Yuvraj</b> or the facility desk to reserve your date.`
            },
            {
                keywords: ['water', 'solar', 'electricity', 'power', 'tank', 'backup', 'generator'],
                response: `⚡ <b>Water Supply & Solar Power Infrastructure:</b><br><br>
                • <b>Water Timings:</b> Continuous pressurized supply from treated underground reservoir. Overhead tanks are filled twice daily (06:00 AM & 05:00 PM).<br>
                • <b>Tank Cleaning:</b> Bi-monthly professional sanitization and chlorination.<br>
                • <b>Solar Rooftop System:</b> 25kW solar array provides renewable power for corridor lighting, water pumps, and dual Otis elevators.<br>
                • <b>Emergency Power:</b> Automatic silent diesel generator switchgear activates within 15 seconds of any grid outage.`
            },
            {
                keywords: ['waste', 'garbage', 'trash', 'dustbin', 'clean'],
                response: `♻️ <b>Waste Management & Cleanliness:</b><br><br>
                • <b>Daily Door-to-Door Pickup:</b> 08:30 AM – 09:30 AM.<br>
                • <b>Segregation Mandate:</b><br>
                  - 🟢 <b>Green Bin:</b> Kitchen and organic wet waste.<br>
                  - 🔵 <b>Blue Bin:</b> Paper, plastic, cardboard, and dry recyclables.<br>
                • Dumping waste in common corridors or lift lobbies is strictly prohibited.`
            },
            {
                keywords: ['developer', 'owner', 'who made', 'yuvraj', 'admin'],
                response: `👨‍💻 <b>System Development & Management:</b><br><br>
                • <b>Lead Developer & Society Administrator:</b> <b>Yuvraj</b><br>
                • <b>Official Contact:</b> <code>yuvrajyadav734@gmail.com</code>`
            }
        ];

        function askPrompt(text) {
            document.getElementById('userInput').value = text;
            sendMessage();
        }

        function sendMessage() {
            const input = document.getElementById('userInput');
            const message = input.value.trim();
            if (!message) return;

            const chatMessages = document.getElementById('chatMessages');

            const userMsgDiv = document.createElement('div');
            userMsgDiv.className = 'msg msg-user';
            userMsgDiv.textContent = message;
            chatMessages.appendChild(userMsgDiv);

            input.value = '';
            chatMessages.scrollTop = chatMessages.scrollHeight;

            setTimeout(() => {
                const lower = message.toLowerCase();
                let matchedResponse = null;

                for (let item of societyKnowledge) {
                    if (item.keywords.some(k => lower.includes(k))) {
                        matchedResponse = item.response;
                        break;
                    }
                }

                if (!matchedResponse) {
                    matchedResponse = `🤖 <b>Thank you for asking!</b><br><br>
                    I am trained specifically on <b>Nilayam Heights Housing Society (Satsang Nagar, Deoghar)</b>.<br>
                    Could you please specify if your query relates to:<br>
                    • Society location on <i>Hirna Road</i> or <i>AS College Road</i><br>
                    • Monthly maintenance bills and payment status<br>
                    • Visitor gate entry and curfew timings<br>
                    • Staff contacts (Electrician Manoj Yadav, Plumber Suresh Mandal, Guard Ramesh Kumar)<br>
                    • Event or Community Hall reservations<br><br>
                    You can also click any of the suggested prompt pills below for an instant answer!`;
                }

                const aiMsgDiv = document.createElement('div');
                aiMsgDiv.className = 'msg msg-ai';
                aiMsgDiv.innerHTML = matchedResponse;
                chatMessages.appendChild(aiMsgDiv);

                chatMessages.scrollTop = chatMessages.scrollHeight;
            }, 350);
        }
    </script>
</body>
</html>
