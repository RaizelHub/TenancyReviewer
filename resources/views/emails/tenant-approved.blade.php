<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Application Approved – {{ config('app.name') }}</title>
</head>
<body style="margin:0;padding:0;background-color:#f0f4ff;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">

  <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f0f4ff;padding:40px 0;">
    <tr>
      <td align="center">
        <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 30px rgba(68,115,190,0.12);">

          <!-- HEADER -->
          <tr>
            <td style="background:linear-gradient(135deg,#1a7a4a 0%,#22a06b 60%,#4ade80 100%);padding:48px 40px 40px;text-align:center;">
              <div style="display:inline-block;background:rgba(255,255,255,0.15);border-radius:50%;padding:16px;margin-bottom:16px;">
                <span style="font-size:40px;line-height:1;">&#x1F389;</span>
              </div>
              <h1 style="margin:0;color:#ffffff;font-size:28px;font-weight:700;letter-spacing:-0.5px;">Application Approved!</h1>
              <p style="margin:10px 0 0;color:rgba(255,255,255,0.85);font-size:15px;">Welcome to {{ config('app.name') }} – you're all set!</p>
            </td>
          </tr>

          <!-- BODY -->
          <tr>
            <td style="padding:40px 40px 24px;">
              <p style="margin:0 0 20px;font-size:16px;color:#374151;line-height:1.7;">
                Hello <strong style="color:#1a7a4a;">{{ $application->full_name }}</strong>,
              </p>
              <p style="margin:0 0 20px;font-size:15px;color:#6b7280;line-height:1.8;">
                Great news! Your application for <strong style="color:#1a7a4a;">{{ $application->company_name }}</strong> has been <strong>approved</strong>.
                Your tenant workspace is now live and ready to use.
              </p>

              <!-- CREDENTIALS CARD -->
              <table width="100%" cellpadding="0" cellspacing="0" style="background:#f0fdf4;border:2px solid #bbf7d0;border-radius:12px;margin:24px 0;">
                <tr>
                  <td style="padding:24px 28px;">
                    <p style="margin:0 0 14px;font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#16a34a;">Login Credentials</p>
                    <table width="100%" cellpadding="0" cellspacing="0">
                      <tr>
                        <td style="padding:8px 0;border-bottom:1px solid #dcfce7;font-size:14px;color:#6b7280;width:40%;">Email</td>
                        <td style="padding:8px 0;border-bottom:1px solid #dcfce7;font-size:14px;color:#111827;font-weight:600;">{{ $application->email }}</td>
                      </tr>
                      <tr>
                        <td style="padding:8px 0;border-bottom:1px solid #dcfce7;font-size:14px;color:#6b7280;">Password</td>
                        <td style="padding:8px 0;border-bottom:1px solid #dcfce7;font-size:14px;font-family:monospace;color:#111827;font-weight:700;letter-spacing:2px;">{{ $password }}</td>
                      </tr>
                      <tr>
                        <td style="padding:8px 0;font-size:14px;color:#6b7280;">Your Domain</td>
                        <td style="padding:8px 0;font-size:14px;color:#16a34a;font-weight:700;">{{ $domain }}</td>
                      </tr>
                    </table>
                    <p style="margin:14px 0 0;font-size:12px;color:#d97706;background:#fffbeb;border-radius:6px;padding:8px 12px;">
                      ⚠️ Please change your password after your first login for security.
                    </p>
                  </td>
                </tr>
              </table>

              <!-- CTA BUTTON -->
              <div style="text-align:center;margin:28px 0;">
                <a href="http://{{ $domain }}" style="display:inline-block;background:linear-gradient(135deg,#1a7a4a,#22a06b);color:#ffffff;text-decoration:none;padding:14px 36px;border-radius:50px;font-size:15px;font-weight:700;">
                  Access Your Workspace &rarr;
                </a>
              </div>

              <!-- PLAN -->
              <table width="100%" cellpadding="0" cellspacing="0" style="background:#f5f8ff;border:1px solid #dde8ff;border-radius:12px;">
                <tr>
                  <td style="padding:18px 24px;">
                    <p style="margin:0;font-size:14px;color:#6b7280;">Active Plan: <strong style="color:#4473be;">{{ $application->subscription_plan }}</strong></p>
                  </td>
                </tr>
              </table>

              <p style="margin:24px 0 0;font-size:15px;color:#6b7280;line-height:1.8;">
                If you have any questions, please don't hesitate to reach out. We're here to help you succeed!
              </p>
            </td>
          </tr>

          <!-- DIVIDER -->
          <tr><td style="padding:0 40px;"><hr style="border:none;border-top:1px solid #e8efff;margin:0;"/></td></tr>

          <!-- FOOTER -->
          <tr>
            <td style="padding:28px 40px 36px;text-align:center;">
              <p style="margin:0 0 6px;font-size:13px;color:#9ca3af;">Need help? Contact us at</p>
              <a href="mailto:{{ config('mail.from.address') }}" style="color:#22a06b;font-size:13px;text-decoration:none;font-weight:600;">{{ config('mail.from.address') }}</a>
              <p style="margin:20px 0 0;font-size:12px;color:#d1d5db;">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
