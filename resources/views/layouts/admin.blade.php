[11:43 am, 12/01/2026] HASTRON: }
[11:54 am, 12/01/2026] HASTRON: <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin Dashboard</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f3f4f6;
        }

        /* ================= TOP BAR ================= */
        .topbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 50px;
            background: #111827;
            color: #fff;
            display: flex;
            align-items: center;
            padding: 0 15px;
            z-index: 1200;
        }

        .topbar .menu-btn {
            font-size: 22px;
            background: none;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        .topbar .title {
            margin-left: 15px;
            font-size: 18px;
            font-weight: bold;
        }

        /* ================= SIDEBAR ================= */
        .sidebar {
            position: fixed;
            top: 50px; /* below topbar */
            left: 0;
            width: 220px;
            height: calc(100vh - 50px);
            background: #1f2937;
            color: #fff;
            padding: 15px;
            transition: transform 0.3s ease;
            z-index: 1100;
        }

        .sidebar.closed {
            transform: translateX(-100%);
        }

        .sidebar a {
            display: block;
            color: #fff;
            text-decoration: none;
            padding: 8px 10px;
            border-radius: 4px;
            margin-bottom: 5px;
        }

        .sidebar a:hover {
            background: #374151;
        }

        .logout-btn {
            margin-top: 20px;
            width: 100%;
            background: #dc2626;
            border: none;
            color: #fff;
            padding: 8px;
            border-radius: 4px;
            cursor: pointer;
        }

        /* ================= CONTENT ================= */
        .content {
            margin-top: 50px;
            margin-left: 220px;
            padding: 20px;
            transition: margin-left 0.3s ease;
        }

        .content.full {
            margin-left: 0;
        }

        /* ================= TABLE STYLES ================= */
        table {
            border-collapse: collapse;
            background: #fff;
        }

        th {
            background: #e5e7eb;
        }

        tr.matched td {
            background-color: #166534 !important;
            color: #fff;
            font-weight: 600;
        }

        tr.mismatch td {
            background-color: #7f1d1d !important;
            color: #fff;
            font-weight: 600;
        }

        /* ================= MOBILE ================= */
        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

<!-- ================= TOP BAR ================= -->
<div class="topbar">
    <button class="menu-btn" onclick="toggleSidebar()">☰</button>
    <span class="title">Admin Panel</span>
</div>

<!-- ================= SIDEBAR ================= -->
<div class="sidebar closed" id="sidebar">
    <a href="/admin">Dashboard</a>
    
    <a href="{{ route('admin.stocks.upload') }}" class="nav-link">
    Upload Stock
</a>
    <a href="/admin/branches">Stock Audit</a>

    <form method="POST" action="/logout">
        @csrf
        <button class="logout-btn">Logout</button>
    </form>
    <li>
    <a href="{{ route('admin.users.index') }}">
        👤 Users
    </a>
</li>
</div>

<!-- ================= CONTENT ================= -->
<div class="content full" id="content">
    @yield('content')
</div>

<!-- ================= SCRIPT ================= -->
<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');

    sidebar.classList.toggle('closed');
    content.classList.toggle('full');
}

// AUTO CLOSE ON MOBILE LOAD
window.addEventListener('load', function () {
    if (window.innerWidth < 992) {
        document.getElementById('sidebar').classList.add('closed');
        document.getElementById('content').classList.add('full');
    } else {
        document.getElementById('sidebar').classList.remove('closed');
        document.getElementById('content').classList.remove('full');
    }
});
</script>

</body>
</html>