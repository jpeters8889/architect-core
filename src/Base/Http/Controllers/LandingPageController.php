<?php

namespace Jpeters8889\Architect\Base\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class LandingPageController
{
    public function __invoke(): Response
    {
        return Inertia::render('Index');
    }
}
