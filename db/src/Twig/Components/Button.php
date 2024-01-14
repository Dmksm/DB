<?php
declare(strict_types=1);
namespace App\Twig\Components;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Button
{
    public string $type = 'submit';
    public string $message;
}