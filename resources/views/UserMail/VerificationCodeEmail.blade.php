<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f7;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            text-align: center;
        }

        h1 {
            color: #333333;
        }

        p {
            color: #555555;
            font-size: 16px;
        }

        .code {
            display: inline-block;
            margin: 20px 0;
            padding: 15px 25px;
            font-size: 24px;
            letter-spacing: 5px;
            background-color: #f0f0f0;
            border-radius: 8px;
            color: #111111;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #888888;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Verification Code</h1>
        <p>Use the code below to verify your email for <strong>E_Store</strong>:</p>
        <div class="code">{{ $code }}</div>
        <p class="footer">If you did not request this code, please ignore this email.</p>
    </div>
</body>

</html>