@php
    $background = $darkMode ? '#1f2937' : '#ffffff';
    $textColor = $darkMode ? '#f9fafb' : '#111827';
@endphp

<div class="p-4 border rounded-md shadow-sm mt-4" style="background: {{ $background }}; font-family: {{ $fontFamily }}; color: {{ $textColor }};">
    
    <h3 style="color: {{ $primaryColor }};" class="text-xl font-bold">
        Texte primaire avec la police {{ $primaryColor }}
    </h3>

    <p style="color: {{ $secondaryColor }};">
        Texte secondaire avec la police {{ $fontFamily }}
    </p>

    <button class="mt-2 px-4 py-2 rounded" style="background: {{ $primaryColor }}; color: white;">
        Bouton primaire
    </button>
</div>