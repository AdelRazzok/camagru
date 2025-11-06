<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Comment On Your Image</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
            font-family: 'Helvetica Neue', Arial, sans-serif;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(90deg, #0079b9, #00a6f4);
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
            color: #ffffff;
        }

        .content {
            padding: 30px;
            line-height: 1.6;
        }

        .content h2 {
            font-size: 22px;
            margin-top: 0;
        }

        .button {
            display: inline-block;
            margin: 20px 0;
            padding: 15px 25px;
            background-color: #00a6f4;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }

        .footer {
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #777;
            background-color: #f7f9fc;
        }

        a {
            color: #00a6f4;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>New Comment On Your Image</h1>
        </div>
        <div class="content">
            <h2>Hey <?= htmlspecialchars($username) ?>,</h2>
            <p>You have a new comment on your image:</p>
            <blockquote>
                <p><?= nl2br(htmlspecialchars($commentContent)) ?></p>
            </blockquote>
            <p>To view the image, click the button below:</p>
            <p style="text-align: center;">
                <a href="http://localhost:8000/" class="button">Go to Camagru</a>
            </p>
            <p>If the button doesn't work, no worries, just copy and paste this link into your browser:</p>
            <p style="word-break: break-all;">
                <a href="http://localhost:8000/">http://localhost:8000/</a>
            </p>
            <p>If you don't want to receive these emails, you can unsubscribe at any time on your account settings page.</p>
        </div>
        <div class="footer">
            <p>© <?= date('Y') ?> Camagru. All rights reserved.</p>
            <p>This is an automated email, please don't reply.</p>
        </div>
    </div>
</body>

</html>
