<?php

namespace App\Twig;

use App\Entity\Subscribe;
use App\Form\SubscribeType;
use Symfony\Component\Form\FormFactoryInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigSubscribeExtension extends AbstractExtension
{
    public function __construct(private readonly FormFactoryInterface $formFactory)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('renderSubscribeForm', [$this, 'renderSubscribeForm'], [
                'is_safe' => ['html'],
                'needs_environment' => true,
            ]),
        ];
    }

    public function renderSubscribeForm(Environment $environment): string
    {
        $subscribe = new Subscribe();
        $form = $this->formFactory->create(SubscribeType::class, $subscribe);

        return $environment->render('global/subscribe.html.twig', compact('form'));
    }
}
