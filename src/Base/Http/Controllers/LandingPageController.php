<?php

namespace Jpeters8889\Architect\Base\Http\Controllers;

use Inertia\Inertia;

class LandingPageController
{
    public function __invoke()
    {
        return Inertia::render('Index');
    }
}
