<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Certificate — {{ $certificate->certificate_number }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #e2e8f0; color: #0f172a; }
        .page {
            width: 1123px;
            max-width: 100%;
            min-height: 794px;
            margin: 40px auto;
            padding: 56px;
            background: #ffffff;
            box-shadow: 0 20px 60px rgba(15, 23, 42, 0.15);
            position: relative;
            overflow: hidden;
        }
        .border-frame {
            position: absolute;
            inset: 18px;
            border: 2px solid #cbd5e1;
            outline: 6px solid #fff;
            outline-offset: -8px;
            border-radius: 8px;
            pointer-events: none;
        }
        .corner { position: absolute; width: 56px; height: 56px; }
        .corner-tl { top: 30px; left: 30px; border-top: 3px solid #2563eb; border-left: 3px solid #2563eb; border-top-left-radius: 10px; }
        .corner-tr { top: 30px; right: 30px; border-top: 3px solid #2563eb; border-right: 3px solid #2563eb; border-top-right-radius: 10px; }
        .corner-bl { bottom: 30px; left: 30px; border-bottom: 3px solid #2563eb; border-left: 3px solid #2563eb; border-bottom-left-radius: 10px; }
        .corner-br { bottom: 30px; right: 30px; border-bottom: 3px solid #2563eb; border-right: 3px solid #2563eb; border-bottom-right-radius: 10px; }
        .content { position: relative; display: flex; flex-direction: column; align-items: center; text-align: center; height: 100%; }
        .logo { width: 72px; height: 72px; border-radius: 18px; background: linear-gradient(135deg, #2563eb, #1e3a8a); display: flex; align-items: center; justify-content: center; margin: 0 auto; }
        .logo svg { width: 36px; height: 36px; color: #fff; }
        .institute { margin-top: 14px; font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 20px; letter-spacing: 0.02em; color: #1e3a8a; }
        .cert-title { margin-top: 34px; font-family: 'Playfair Display', serif; font-weight: 700; font-size: 44px; color: #0f172a; }
        .cert-title span { color: #2563eb; }
        .statement { margin-top: 22px; font-size: 15px; line-height: 1.8; color: #475569; max-width: 640px; }
        .statement strong { color: #1e293b; font-weight: 600; }
        .recipient {
            margin-top: 34px;
            font-family: 'Playfair Display', serif;
            font-size: 38px;
            font-weight: 700;
            color: #2563eb;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 6px;
            display: inline-block;
            min-width: 320px;
            letter-spacing: 0.01em;
        }
        .course-name { margin-top: 10px; font-size: 17px; font-weight: 600; color: #334155; }
        .details { margin-top: 36px; display: flex; gap: 56px; align-items: flex-end; justify-content: center; }
        .detail { font-size: 12px; color: #64748b; text-transform: uppercase; letter-spacing: 0.08em; }
        .detail .value { margin-top: 6px; font-size: 15px; font-weight: 600; color: #0f172a; text-transform: none; letter-spacing: 0; }
        .signature { margin-top: 44px; display: flex; flex-direction: column; align-items: center; }
        .sig-line { width: 220px; border-top: 1.5px solid #94a3b8; }
        .sig-label { margin-top: 8px; font-size: 13px; color: #475569; }
        .verification { margin-top: 44px; display: flex; align-items: center; gap: 10px; background: #f1f5f9; padding: 10px 20px; border-radius: 999px; font-size: 13px; color: #334155; }
        .verification strong { font-family: 'SF Mono', ui-monospace, monospace; letter-spacing: 0.12em; color: #1e40af; }
        .print-btn {
            position: fixed; top: 24px; right: 24px; z-index: 50;
            background: #2563eb; color: #fff; border: 0; padding: 12px 22px; border-radius: 12px;
            font-family: 'Inter', sans-serif; font-size: 14px; font-weight: 600; cursor: pointer;
            box-shadow: 0 8px 24px rgba(37, 99, 235, 0.35);
        }
        @media print {
            body { background: #fff; }
            .page { margin: 0; width: 100%; min-height: auto; box-shadow: none; padding: 40px; }
            .print-btn { display: none; }
            @page { size: A4 landscape; margin: 0; }
        }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">Download PDF</button>

    <div class="page">
        <div class="border-frame"></div>
        <div class="corner corner-tl"></div>
        <div class="corner corner-tr"></div>
        <div class="corner corner-bl"></div>
        <div class="corner corner-br"></div>

        <div class="content">
            <div class="logo">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                </svg>
            </div>
            <div class="institute">{{ config('app.name') }}</div>

            <div class="cert-title">Certificate of <span>Completion</span></div>
            <p class="statement">
                This is to certify that <strong>{{ $certificate->student->name }}</strong> has
                successfully completed the course
            </p>

            <div class="recipient">{{ $certificate->course->title }}</div>
            <p class="course-name">with all required assessments and practical assignments.</p>

            <div class="details">
                <div class="detail">
                    Certificate No.
                    <div class="value">{{ $certificate->certificate_number }}</div>
                </div>
                <div class="detail">
                    Issue Date
                    <div class="value">{{ $certificate->issue_date->format('d F Y') }}</div>
                </div>
                <div class="detail">
                    Signature
                    <div class="value" style="font-family:'Playfair Display',serif;font-style:italic;font-size:22px;color:#1e40af;">M. Rahman</div>
                </div>
            </div>

            <div class="signature">
                <div class="sig-line"></div>
                <div class="sig-label">Authorized Signatory — Head of Academics</div>
            </div>

            <div class="verification">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#2563eb"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
                Verify at <strong>{{ url('/certificate/' . $certificate->certificate_number) }}</strong>
            </div>
        </div>
    </div>
</body>
</html>