<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Login Verification Code</title>
</head>

<body style="margin:0;padding:0;background:#f8fafc;font-family:Arial,sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 0;background:#f8fafc;">
        <tr>
            <td align="center">

                <table width="600" cellpadding="0" cellspacing="0"
                    style="background:#ffffff;border-radius:10px;padding:40px;">

                    <tr>
                        <td>

                            <h2 style="margin:0;color:#1e293b;">
                                Login Verification
                            </h2>

                            <p style="margin-top:20px;color:#475569;font-size:15px;line-height:24px;">
                                Hello {{ $user->full_name }},
                            </p>

                            <p style="color:#475569;font-size:15px;line-height:24px;">
                                Use the verification code below to complete your login.
                            </p>

                            <div
                                style="margin:35px 0;padding:18px;background:#eef2ff;border-radius:8px;text-align:center;">

                                <span style="font-size:34px;font-weight:bold;letter-spacing:10px;color:#4338ca;">

                                    {{ $otp }}

                                </span>

                            </div>

                            <p style="color:#475569;">
                                This OTP is valid for
                                <strong>{{ $expiryMinutes }} minutes</strong>.
                            </p>

                            <p style="color:#64748b;">
                                If you did not request this login, you can safely ignore this email.
                            </p>

                            <hr style="margin:35px 0;border:none;border-top:1px solid #e2e8f0;">

                            <p style="font-size:13px;color:#94a3b8;">
                                This is an automated email. Please do not reply.
                            </p>

                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
