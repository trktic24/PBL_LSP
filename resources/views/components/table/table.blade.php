<div class="border border-gray-900 shadow-md overflow-x-auto">

    <table class="w-full">
        
        <thead class="bg-black text-white">
            {{ $thead ?? ''}}
        </thead>

        <tbody>
            {{ $slot }}
        </tbody>

    </table>
</div>