<html>
<head>
    @vite(['resources/css/app.css'])
    <style>
        /* Force background colors to show in PDF */
        body { -webkit-print-color-adjust: exact; }
    </style>
</head>
<body class="bg-white p-10">
    <div class="relative border-b-4pb-6 mb-4">
        <div class="absolute left-[120px] top-[-5px] h-full flex items-center">
            <img src="{{ public_path('images/earist_logo.png') }}" class="w-20 h-20">
        </div>

        <div class="text-center">
            <p class="text-sm">Republic of the Philippines</p>
            <p class="font-bold text-sm">EULOGIO "AMANG" RODRIGUEZ</p>
            <p class="font-bold text-sm">INSTITUTE OF SCIENCE AND TECHNOLOGY</p>
            <p class="text-sm italic">Nagtahan, Sampaloc, Manila</p>
        </div>
    </div>

    <div class="text-center mb-2">
        <p class="text-lg font-bold">WEB DEVELOPMENT SERVICES</p>
    </div>

    <div class="mt-4 space-y-6 mx-20 font-sans">
    <div class="pb-1">
        <p class="text-md font-bold text-black">Control Number:</p>
        <p class="text-md text-black">{{ $item->ctrl_no }}</p>
    </div>

    <table class="w-full border-2 border-black border-collapse text-left">
        <tbody>
            <tr>
                <td class="w-1/3 border border-black p-3 align-top">
                    <span class="block text-md font-bold mb-1">Office / College:</span>
                    <span class="text-md text-black">{{ $item->department }}</span>
                </td>
                <td class="w-1/3 border border-black p-3 align-top">
                    <span class="block text-md font-bold mb-1">Title:</span>
                    <span class="text-md text-black">{{ $item->doc_title }}</span>
                </td>
                <td class="w-1/3 border border-black p-3 align-top">
                    <span class="block text-md font-bold mb-1">Schedule of Posting:</span>
                    <span class="text-md text-black">{{ $item->date_to_be_posted }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="3" class="border border-black p-3 align-top">
                    <span class="block text-md font-bold mb-1">Particulars:</span>
                    <span class="text-md text-black">Posting of {{ $item->particulars }}</span>
                </td>
            </tr>
        </tbody>
    </table>

    <div style="padding-top: 50px;">
        <div class="w-full">
            <p class="text-md font-bold mb-5">Requested By:</p>
            <p class="text-md">{{ $item->personnel }}</p>
        </div>
    </div>
</div>
</body>
</html>