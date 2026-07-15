<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Application Update – {{ config('app.name') }}</title>
</head>
<body style="margin:0;padding:0;background-color:#f9f9fb;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">

  <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f9f9fb;padding:40px 0;">
    <tr>
      <td align="center">
        <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 30px rgba(0,0,0,0.08);">

          <!-- HEADER -->
          <tr>
            <td style="background:linear-gradient(135deg,#374151 0%,#4b5563 60%,#6b7280 100%);padding:48px 40px 40px;text-align:center;">
              <div style="display:inline-block;background:rgba(255,255,255,0.12);border-radius:50%;padding:16px;margin-bottom:16px;">
                <span style="font-size:40px;line-height:1;">&#x1F4DD;</span>
              </div>
              <h1 style="margin:0;color:#ffffff;font-size:28px;font-weight:700;letter-spacing:-0.5px;">Application Update</h1>
              <p style="margin:10px 0 0;color:rgba(255,255,255,0.75);font-size:15px;">An update regarding your subscription request</p>
            </td>
          </tr>

          <!-- BODY -->
          <tr>
            <td style="padding:40px 40px 24px;">
              <p style="margin:0 0 20px;font-size:16px;color:#374151;line-height:1.7;">
                Hello <strong>{{ $application->full_name }}</strong>,
              </p>
              <p style="margin:0 0 20px;font-size:15px;color:#6b7280;line-height:1.8;">
                Thank you for your interest in <strong>{{ config('app.name') }}</strong>. We've carefully reviewed your application for
                <strong>{{ $application->company_name }}</strong> and unfortunately, we are unable to approve it at this time.
              </p>

              <!-- REASON CARD -->
              <table width="100%" cellpadding="0" cellspacing="0" style="background:#fafafa;border:1px solid #e5e7eb;border-left:4px solid #9ca3af;border-radius:8px;margin:24px 0;">
                <tr>
                  <td style="padding:20px 24px;">
                    <p style="margin:0 0 10px;font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#6b7280;">Reason for Decision</p>
                    <p style="margin:0;font-size:14px;color:#374151;line-height:1.7;">{{ $application->notes ?? 'No specific reason provided.' }}</p>
                  </td>
                </tr>
              </table>

              <p style="margin:0 0 20px;font-size:15px;color:#6b7280;line-height:1.8;">
                If you believe there's been an error or would like to provide additional information, please reply to this email. We'd love to hear from you.
              </p>

              <!-- RE-APPLY NOTE -->
              <table width="100%" cellpadding="0" cellspacing="0" style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:12px;">
                <tr>
                  <td style="padding:18px 24px;">
                    <p style="margin:0;font-size:14px;color:#1e40af;line-height:1.7;">
                      &#x1F4A1; You're welcome to <strong>re-apply</strong> after addressing the issue above. We look forward to potentially working with you in the future.
                    </p>
                  </td>
                </tr>
              </table>

            </td>
          </tr>

          <!-- DIVIDER -->
          <tr><td style="padding:0 40px;"><hr style="border:none;border-top:1px solid #f3f4f6;margin:0;"/></td></tr>

          <!-- FOOTER -->
          <tr>
            <td style="padding:28px 40px 36px;text-align:center;">
              <p style="margin:0 0 6px;font-size:13px;color:#9ca3af;">Have questions? Contact our support team at</p>
              <a href="mailto:{{ config('mail.from.address') }}" style="color:#4473be;font-size:13px;text-decoration:none;font-weight:600;">{{ config('mail.from.address') }}</a>
              <p style="margin:20px 0 0;font-size:12px;color:#d1d5db;">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
