<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('btn')]
final class Btn
{
  public $type = 'primary';
  public $text = null;
}
