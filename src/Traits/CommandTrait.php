<?php

namespace Bluora\LaravelDatasets\Traits;

trait CommandTrait
{
    public function splash($text)
    {
        $this->line('');
        $this->line("       _                          _   ___       _                _      ");
        $this->line("      | |   __ _ _ _ __ ___ _____| | |   \ __ _| |_ __ _ ___ ___| |_ ___");
        $this->line("      | |__/ _` | '_/ _` \ V / -_) | | |) / _` |  _/ _` (_-</ -_)  _(_-<");
        $this->line("      |____\__,_|_| \__,_|\_/\___|_| |___/\__,_|\__\__,_/__/\___|\__/__/");
        $this->line('');
        $this->line("                                                          By H&H|Digital");
        $this->line('');
        $this->line($text.'.');
        $this->line('');
    }
}
