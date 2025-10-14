<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form Submission</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .content {
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }

        .field {
            margin-bottom: 15px;
        }

        .field label {
            font-weight: bold;
            color: #495057;
        }

        .field-value {
            margin-top: 5px;
            padding: 8px;
            background-color: #f8f9fa;
            border-radius: 3px;
        }

        .message-box {
            background-color: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #007bff;
            margin: 15px 0;
        }

        .footer {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>🎫 New Contact Form Submission</h2>
        <p>You have received a new message through the UndiIn contact form.</p>
    </div>

    <div class="content">
        <div class="field">
            <label>📧 From:</label>
            <div class="field-value">
                <strong>{{ $data['name'] }}</strong> &lt;{{ $data['email'] }}&gt;
            </div>
        </div>

        <div class="field">
            <label>📝 Subject:</label>
            <div class="field-value">
                {{ $data['subject'] }}
                @if ($data['subject'] === 'account_blocked')
                    <span
                        style="background-color: #dc3545; color: white; padding: 2px 6px; border-radius: 3px; font-size: 11px; margin-left: 8px;">
                        BLOCKED ACCOUNT
                    </span>
                @endif
            </div>
        </div>

        <div class="field">
            <label>💬 Message:</label>
            <div class="message-box">
                {!! nl2br(e($data['message'])) !!}
            </div>
        </div>

        <div class="field">
            <label>⏰ Submitted:</label>
            <div class="field-value">
                {{ now()->format('F j, Y \a\t g:i A') }} ({{ now()->format('T') }})
            </div>
        </div>
    </div>

    <div class="footer">
        <p><strong>Quick Actions:</strong></p>
        <ul>
            <li>Reply directly to this email to respond to the user</li>
            @if ($data['subject'] === 'account_blocked')
                <li>Check user account status in admin panel</li>
                <li>Review block reason and consider unblocking if appropriate</li>
            @endif
            <li>Contact user via alternative methods if needed</li>
        </ul>

        <hr style="margin: 15px 0;">
        <p>
            <small>
                This message was sent from the UndiIn contact form.<br>
                Do not reply to this email if it was automatically generated.
            </small>
        </p>
    </div>
</body>

</html>
