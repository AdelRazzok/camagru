<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset your password</title>
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
      <h1>Forgot your password?</h1>
    </div>
    <div class="content">
      <h2>Hey <?= htmlspecialchars($username) ?>,</h2>
      <p>We're so sorry to hear that you've forgotten your password. To reset it, please click the button below:</p>
      <p style="text-align: center;">
        <a href="<?= htmlspecialchars($resetLink) ?>" class="button">Reset My Password</a>
      </p>
      <p>If the button doesn't work, no worries, just copy and paste this link into your browser:</p>
      <p style="word-break: break-all;">
        <a href="<?= htmlspecialchars($resetLink) ?>"><?= htmlspecialchars($resetLink) ?></a>
      </p>
      <p>This link will expire in <strong>24 hours</strong>, so be sure to use it soon!</p>
      <p>If you didn't ask for a password reset, feel free to ignore this email.</p>
    </div>
    <div class="footer">
      <p>© <?= date('Y') ?> Camagru. All rights reserved.</p>
      <p>This is an automated email, please don't reply.</p>
    </div>
  </div>
</body>

</html>