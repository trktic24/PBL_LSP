@props(['title'])

<header {{ $attributes->merge(['class' => 'form-header flex flex-col items-start px-4 pt-2 pb-4 border-b-2 border-gray-400']) }}>
    {{-- 
      Ukurannya h-10 di mobile, 
      dan h-12 di layar medium (md) ke atas
    --}}
    <img src="{{ asset('images/logo_bnsp.png') }}" alt="Logo BNSP" class="h-15 md:h-20 w-auto">
    
    {{-- 
      Margin atas 4 di mobile, 
      dan 8 di layar medium (md) ke atas
    --}}
    <div class="text-center w-full mt-4 md:mt-8">
        {{-- 
          Ini bagian terpenting:
          - Default (Mobile): text-2xl
          - Small (sm): text-3xl
          - Medium (md): text-4xl
        --}}
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-black">{{ $title ?? '' }}</h1>
    </div>
</header>