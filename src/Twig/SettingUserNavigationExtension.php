<?php

namespace App\Twig;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class SettingUserNavigationExtension extends AbstractExtension
{
    private Request $request;

    public function __construct(RequestStack $requestStack)
    {
        if ($requestStack->getCurrentRequest())
        {
            $this->request = $requestStack->getCurrentRequest();
        }
    }

    public function getFilters()
    {
        return [new TwigFilter('navItemUser', [$this, 'navItemUserFilter'], ['is_safe' => ['html']])];
    }

    public function navItemUserFilter($content, $path, $icon=false): string
    {
        $icon_html = "";

        if ($icon)
        {
            $icon_html = "
                <i class='bi $icon'></i>
            ";
        }

        $current_path = $this->request->getRequestUri();
        $class = "list-group-item list-group-item-action";
        if ($current_path == $path)
        {
            $class = $class . " active";
        }

        return "
            <a href=\"$path\" class=\"$class\">
                $icon_html $content
            </a>
        ";
    }
}
