<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 13px; margin: 0; padding: 20px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; } /* New utility for the date */
        .font-bold { font-weight: bold; }
        .italic { font-style: italic; }
        
        /* Centered Header Layout */
        .header-wrapper {
            width: 100%;
            margin-bottom: 1px;
        }
        
        .header-table {
            margin: 0 auto; 
            border-collapse: collapse;
        }

        .logo-left { 
            width: 85px; 
            height: 85px; 
            margin-right: 25px; 
        }

        .logo-right { 
            width: 85px; 
            height: 85px; 
            margin-left: 25px; 
        }

        .header-text {
            text-align: center;
            line-height: 1.2;
        }

        /* Content Styles */
        .content-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .content-table td { border: 2px solid black; padding: 12px; vertical-align: top; }
        
        .title-section { margin-top: 1px; margin-bottom: 5px; }
        
        .date-section { 
            margin-bottom: 5px; 
            font-size: 14px; 
            padding-right: 60px; 
        }
        
        .container-padding { padding: 0 60px; }
        .block { display: block; }
    </style>
</head>
<body>

    <div class="header-wrapper">
        <table class="header-table">
            <tr>
                <td style="vertical-align: middle;">
                    <img src="{{ public_path('images/earist_logo.png') }}" class="logo-left">
                </td>
                <td style="vertical-align: middle;" class="header-text">
                    <span style="font-size: 14px;" class="block">Republic of the Philippines</span>
                    <span style="font-size: 16px;" class="font-bold block">EULOGIO "AMANG" RODRIGUEZ</span>
                    <span style="font-size: 16px;" class="font-bold block">INSTITUTE OF SCIENCE AND TECHNOLOGY</span>
                    <span style="font-size: 14px;" class="italic block">Nagtahan, Sampaloc, Manila</span>
                </td>
                <td style="vertical-align: middle;">
                    <img src="{{ public_path('images/bp_logo.png') }}" class="logo-right">
                </td>
            </tr>
        </table>
    </div>

    <div class="text-center title-section">
        <p style="font-size: 18px;" class="font-bold">WEB DEVELOPMENT SERVICES</p>
    </div>
    
    <div class="text-right date-section">
        <p>{{ now()->format('F j, Y') }}</p>
    </div>

    <div class="container-padding">
        <div style="margin-bottom: 15px;">
            <span class="font-bold block">Control Number:</span>
            <span>{{ $item->ctrl_no }}</span>
        </div>

        <table class="content-table">
            <tbody>
                <tr>
                    <td width="33%">
                        <span class="font-bold block">Office / College:</span>
                        <span>{{ $item->department }}</span>
                    </td>
                    <td width="33%">
                        <span class="font-bold block">Title:</span>
                        <span>{{ $item->doc_title }}</span>
                    </td>
                    <td width="33%">
                        <span class="font-bold block">Schedule of Posting:</span>
                        <span>{{ $item->date_to_be_posted }}</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <span class="font-bold block">Particulars:</span>
                        <span>Posting of {{ $item->particulars }}</span>
                    </td>
                </tr>
            </tbody>
        </table>

        <div style="margin-top: 10px;">
            <p style="margin-bottom: 20px;">Requested By:</p>
            <p class="font-bold text-md" style="text-transform: uppercase; margin-bottom: 0;">
                {{ $item->personnel }}
            </p>
            <p class="text-md italic" style="margin-top: 0;">
                {{ $item->position }}<br>
                {{ $item->department }}
            </p>
        </div>
    </div>

</body>
</html>