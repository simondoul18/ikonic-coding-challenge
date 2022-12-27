<?php

namespace App\View\Components;

use Illuminate\View\Component;

class NetworkConnections extends Component
{
    public $showSuggestion = true;
    public $showRequest = false;
    public $showReceivedRequest = false;
    public $showConnections = false;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.network-connections');
    }

    public function showTab($tab){
        $this->showSuggestion = false;
        $this->showRequest = false;
        $this->showReceivedRequest = false;
        $this->showConnections = false;

        $this->$tab = true;
    }
}
