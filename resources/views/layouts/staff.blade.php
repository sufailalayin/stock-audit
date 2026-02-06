<!DOCTYPE html>
<html>
<head>
    <title>Stock Audit – Staff</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            margin: 0;
            font-family: system-ui, Arial;
            background: #f4f6f8;
        }

        header {
            background: #0f766e;
            color: #fff;
            padding: 12px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header form button {
            background: #dc2626;
            border: none;
            color: #fff;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
        }

        .container {
            max-width: 480px;   /* ✅ FIXED */
            margin: auto;
            padding: 14px;
        }

        .card {
            background: #fff;
            border-radius: 10px;
            padding: 14px;
            margin-bottom: 12px;
            box-shadow: 0 2px 6px rgba(0,0,0,.08);
        }

        .grid-2 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .btn {
            display: block;
            padding: 12px;
            background: #0f766e;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            border: none;
            width: 100%;
            font-size: 16px;
            margin-top: 10px;
            text-align: center;
        }

        input {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-top: 6px;
        }
    </style>
</head>
<body>

<header>
    <div>📦 Stock Audit – Staff</div>

    {{-- LOGOUT FIX --}}
    <form method="POST" action="/logout">
        @csrf
        <button>Logout</button>
    </form>
</header>

<div class="container">
    @yield('content')
</div>

@yield('scripts')

</body>
</html>