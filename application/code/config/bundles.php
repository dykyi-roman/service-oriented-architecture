<?php

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle::class => ['all' => true],
    Sentry\SentryBundle\SentryBundle::class => ['all' => true],
    Symfony\Bundle\MonologBundle\MonologBundle::class => ['all' => true],
    Symfony\Bundle\TwigBundle\TwigBundle::class => ['all' => true],
    SimpleBus\SymfonyBridge\SimpleBusCommandBusBundle::class => ['all' => true],
    Symplify\ConsoleColorDiff\ConsoleColorDiffBundle::class => ['dev' => true, 'test' => true],
    Symplify\ParameterNameGuard\ParameterNameGuardBundle::class => ['dev' => true, 'test' => true],
    Symplify\CodingStandard\SymplifyCodingStandardBundle::class => ['dev' => true, 'test' => true],
    Symplify\EasyCodingStandard\EasyCodingStandardBundle::class => ['dev' => true, 'test' => true],
];
