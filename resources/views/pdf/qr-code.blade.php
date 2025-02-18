<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Customer Service Feedback Form QR Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        .page-wrapper {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
        }
        .container {
            width: 100%;
            text-align: center;
        }
        .qr-wrapper {
            margin: 30px auto;
            padding: 20px;
            width: 300px;
            background: #fff;
        }
        .qr-code {
            width: 250px;
            height: 250px;
            margin: 0 auto;
            display: block;
        }
        .title {
            font-size: 24px;
            margin: 0 0 30px 0;
            color: #333;
            font-weight: bold;
        }
        .instructions {
            font-size: 16px;
            color: #666;
            margin: 20px 0 0 0;
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <div class="container">
            <h1 class="title">Customer Service Feedback Form QR Code</h1>
            <div class="qr-wrapper">
                <img src="data:image/svg+xml;base64,{{ str_replace('data:image/svg+xml;base64,', '', $qrCodeData) }}" class="qr-code" alt="QR Code">
            </div>
            <p class="instructions">Scan this QR code to access the feedback form</p>
        </div>
    </div>
</body>
</html>