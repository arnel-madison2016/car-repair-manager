@livewire('ThemePreview', [
    'primaryColor' => old('primary_color', '#3b82f6'),
    'secondaryColor' => old('secondary_color', '#6366f1'),
    'fontFamily' => old('font_family', 'Roboto, sans-serif'),
    'darkMode' => old('dark_mode', false),
])
