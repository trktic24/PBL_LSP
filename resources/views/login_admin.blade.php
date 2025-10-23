<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Admin LSP Polines</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
            height: 100vh;
            width: 100%;
            background: linear-gradient(135deg, #e6ebf1, #dce4f7);
        }

        /* 🌟 HEADER PUTIH */
        header {
            width: 100%;
            height: 80px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 10;
        }

        header img {
            height: 70px;
        }

        /* 🔹 BAGIAN KONTEN UTAMA (di bawah header) */
        .content {
            flex: 1;
            display: flex;
            margin-top: 80px; /* supaya tidak tertutup header */
            height: calc(100vh - 80px);
        }

        /* Kolom kiri: form login */
        .left {
            width: 50%;
            padding: 200px;
            background: linear-gradient(to bottom right, #f5f8ff, #eaf0ff);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .left h2 {
            font-size: 32px;
            font-weight: 700;
            color: #222;
            margin-bottom: 10px;
        }

        .left p {
            font-size: 14px;
            color: #666;
            margin-bottom: 35px;
        }

        label {
            font-weight: 600;
            color: #333;
            display: block;
            margin-bottom: 6px;
        }

        input {
            width: 500px;
            max-width: 100%; /* agar tetap responsif */
            padding: 12px 16px;
            border: 1px solid #ccc;
            border-radius: 10px;
            margin-bottom: 22px;
            outline: none;
            transition: 0.3s;
        }

        input:focus {
            border-color: #4a6cf7;
            box-shadow: 0 0 6px rgba(74,108,247,0.3);
        }

        .forgot {
            font-size: 13px;
            color: #555;
            margin-bottom: 30px;
        }

        .forgot a {
            color: #4a6cf7;
            text-decoration: none;
            font-weight: 600;
        }

        button {
            width: 500px;
            max-width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-weight: bold;
            color: #222;
            background: linear-gradient(to right, #b4e1ff, #d7f89c);
            box-shadow:
                inset 2px 2px 5px rgba(255, 255, 255, 0.6),  /* inner highlight atas kiri */
                inset -3px -3px 6px rgba(0, 0, 0, 0.15),   /* inner shadow bawah kanan */
                0 4px 10px rgba(0, 0, 0, 0.1);            /* outer soft shadow */
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: linear-gradient(to right, #a6d5ff, #c9f588);
        }

        .footer-text {
            text-align: right;
            font-size: 13px;
            color: #444;
            margin-top: 25px;
        }

        .footer-text span {
            font-weight: 700;
        }

        /* Kolom kanan: foto gedung */
        .right {
            width: 50%;
            background-image: url('{{ asset("images/gedung_gkt.jpg") }}');
            background-size: cover;
            background-position: center;
        }

        /* Responsif */
        @media (max-width: 900px) {
            .content {
                flex-direction: column;
            }

            .left, .right {
                width: 100%;
                height: 50%;
            }

            .left {
                padding: 60px 30px;
            }

            header img {
                height: 50px;
            }
        }
    </style>
</head>
<body>
    <!-- HEADER PUTIH -->
    <header>
        <img src="{{ asset('images/logo_lsp.jpg') }}" alt="LSP Polines">
    </header>

    <!-- KONTEN UTAMA -->
    <div class="content">
        <div class="left">
            <h2>Kamu Admin LSP Polines?</h2>
            <p>Enter your username and password to access your account.</p>

            <form action="{{ url('/login') }}" method="POST">
                @csrf
                <label>Username</label>
                <input type="text" name="username" placeholder="Username" required>

                <label>Password</label>
                <input type="password" name="password" placeholder="Password" required>

                <div class="forgot">
                    Forgot Your <a href="#">Password?</a>
                </div>

                <button type="submit">LOGIN</button>
            </form>

            @if(session('error'))
                 <p style="color:red; margin-top: 10px;">{{ session('error') }}</p>
            @endif

            <div class="footer-text">
                Jadi kamu <span>Super Admin LSP Polines?</span>
            </div>
        </div>

        <div class="right"></div>
    </div>
</body>
</html>
