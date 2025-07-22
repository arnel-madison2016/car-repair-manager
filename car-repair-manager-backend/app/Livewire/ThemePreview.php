<?php

namespace App\Livewire;

use Livewire\Component;

class ThemePreview extends Component {
    public string $primaryColor = '#3b82f6';
    public string $secondaryColor = '#6366f1';
    public string $fontFamily = 'Roboto, sans-serif';
    public bool $darkMode = false;
    public function render() {

        return view('livewire.theme-preview');
    }
}
