<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Filament\Facades\Filament;
use Illuminate\Support\HtmlString;
use App\Models\ThemeSetting;

class FilamentThemeServiceProvider extends ServiceProvider {
    /**
     * Register services.
     */
    
    public function register(): void {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void {
        
        Filament::serving(function () {
            
            $theme = ThemeSetting::latest()->first();

            if ($theme) {
                Filament::registerRenderHook(
                    'head.end',
                    fn (): HtmlString => new HtmlString("
                        <style>
                            :root {
                                --primary-color: {$theme->primary_color};
                                --secondary-color: {$theme->secondary_color};
                                --font-family: {$theme->font_family};
                            }

                            body {
                                font-family: var(--font-family);
                            }

                            .fi-btn {
                                background-color: var(--primary-color);
                                color: white;
                            }

                            .fi-sidebar {
                                background-color: var(--secondary-color);
                            }
                        </style>
                    ")
                );

                // Appliquer dark mode
                config(['filament.dark_mode' => $theme->dark_mode]);
            }
        });
    }
}
