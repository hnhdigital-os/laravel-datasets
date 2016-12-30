<?php

namespace Bluora\LaravelDatasets\Traits;

trait CommandTrait
{
    public function splash($text)
    {
        $this->line('');
        $this->line('    ___        _                     _         _  _      __                                 _ ');
        $this->line('   /   \ __ _ | |_  __ _  ___   ___ | |_  ___ | || |    / /   __ _  _ __  __ _ __   __ ___ | |');
        $this->line("  / /\ // _` || __|/ _` |/ __| / _ \| __|/ __|| || |_  / /   / _` || '__|/ _` |\ \ / // _ \| |");
        $this->line(' / /_//| (_| || |_| (_| |\__ \|  __/| |_ \__ \|__   _|/ /___| (_| || |  | (_| | \ V /|  __/| |');
        $this->line("/___,'  \__,_| \__|\__,_||___/ \___| \__||___/   |_|  \____/ \__,_||_|   \__,_|  \_/  \___||_|");
        $this->line('');
        $this->line('                                                                                By H&H|Digital');
        $this->line('');
        $this->line($text.'.');
        $this->line('');
    }
}
