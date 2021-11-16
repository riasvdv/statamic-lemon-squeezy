<?php

namespace Rias\StatamicLemonSqueezy;

use Rias\StatamicLemonSqueezy\Fieldtypes\LemonSqueezy;
use Statamic\Facades\CP\Nav as NavFacade;
use Statamic\CP\Navigation\Nav;
use Statamic\Providers\AddonServiceProvider;
use Statamic\Statamic;

class LemonSqueezyServiceProvider extends AddonServiceProvider
{
    protected $routes = [
        'cp' => __DIR__ . '/../routes/cp.php',
    ];

    protected $scripts = [
        __DIR__ . '/../dist/js/addon.js',
    ];

    protected $viewNamespace = 'lemon-squeezy';

    protected $fieldtypes = [
        LemonSqueezy::class,
    ];

    public function register()
    {
        $this->registerAddonConfig();
    }

    public function boot()
    {
        parent::boot();

        Statamic::booted(function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/api.php');

            NavFacade::extend(function (Nav $nav) {
                $nav->item('Lemon Squeezy')
                    ->icon('<svg width="20px" height="18px" viewBox="0 0 128 174" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M43.2867 106.795L90.2155 128.375C96.0319 131.051 100.138 135.542 102.355 140.694C107.963 153.739 100.298 167.081 88.2658 171.879C76.2316 176.676 63.4061 173.589 57.5744 160.023L37.1511 112.394C35.5684 108.702 39.5441 105.074 43.2867 106.795" fill="#FFC233"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M46.1054 92.8264L94.548 74.6126C110.648 68.5591 128.235 80.0127 127.998 96.6547C127.994 96.8719 127.99 97.0891 127.984 97.3081C127.636 113.514 110.539 124.406 94.7927 118.673L46.1513 100.965C42.2711 99.5535 42.2425 94.2786 46.1054 92.8264" fill="#FFC233"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M43.39 86.5175L91.0107 66.3914C106.835 59.7029 110.851 39.6283 98.4575 28.0294C98.2951 27.8766 98.1326 27.7257 97.9682 27.5747C85.8174 16.3567 65.7305 20.3065 58.8131 35.0822L37.4436 80.7328C35.7386 84.3733 39.592 88.1225 43.39 86.5175" fill="#FFC233"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M31.1356 78.5642L48.4491 31.3456C50.5956 25.4909 50.198 19.5202 47.9789 14.3686C42.3594 1.32848 27.1408 -2.8808 15.1104 1.92481C3.08191 6.73226 -3.71504 17.6502 2.12812 31.2112L22.6853 78.7888C24.2794 82.4753 29.7556 82.3299 31.1356 78.5642" fill="#FFC233"/>
                        </svg>')
                    ->route('lemon-squeezy')
                    ->section('Tools');
            });
        });
    }

    protected function registerAddonConfig()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/lemon-squeezy.php', 'statamic.lemon-squeezy');

        $this->publishes([
            __DIR__.'/../config/lemon-squeezy.php' => config_path('statamic/lemon-squeezy.php'),
        ], 'lemon-squeezy-config');

        return $this;
    }
}
