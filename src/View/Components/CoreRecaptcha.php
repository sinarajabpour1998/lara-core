<?php

namespace Sinarajabpour1998\LaraCore\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CoreRecaptcha extends Component
{
    /**
     * The type of menu, modules or manager
     * @var
     */
    public $hasError;

    public function __construct(bool $hasError)
    {
        $this->hasError = $hasError;
    }

    public function render()
    {
        return view('vendor.LaraCore.recaptcha.core-recaptcha');
    }
}
