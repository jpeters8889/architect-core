<?php

namespace Jpeters8889\Architect\Tests\AppClasses;

use Jpeters8889\Architect\Modules\Blueprints\AbstractBlueprint;
use Jpeters8889\Architect\Tests\AppClasses\Models\Blog;

class BlogBlueprint extends AbstractBlueprint
{
    public function group(): string
    {
        return 'Blogs';
    }

    protected function model(): string
    {
        return Blog::class;
    }
}
