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
            <p class="text-md font-bold">Control Number:</span>
            <p class="text-md">{{ $item->ctrl_no }}</p>
        </div>

          <div class="pb-1">
            <p class="text-md font-bold">Office / College:</span>
            <p class="text-md">{{ $item->department }}</p>
        </div>

         <div class="pb-1">
            <p class="text-md font-bold">Title:</span>
            <p class="text-md">{{ $item->doc_title }}</p>
        </div>

        <div class="pb-1">
            <p class="text-md font-bold">Particulars:</span>
            <p class="text-md">Posting of <span>{{ $item->particulars }}</span></p>
        </div>

        <div class="pb-1">
            <p class="text-md font-bold">Schedule of Posting:</span>
            <p class="text-md">{{ $item->date_to_be_posted }}</p>
        </div>

         <div class="pb-1">
            <p class="text-md font-bold">Requested By:</span>
            <p class="text-md">{{ $item->personnel }}</p>
        </div>

    </div>
</body>
</html>