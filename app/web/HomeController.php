<?php

declare(strict_types=1);

namespace App\web;

use Tempest\Http\Get;

use function Tempest\view;

use Tempest\View\View;

final readonly class HomeController
{
    #[Get('/')]
    public function __invoke(): View
    {
        return view('web/home.view.php');
    }
}
