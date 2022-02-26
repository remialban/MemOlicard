<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Set\ValueObject\LevelSetList;
use Symfony\Component\Routing\Annotation\Route;
use Rector\Php74\Rector\Property\TypedPropertyRector;
use Rector\DowngradePhp80\ValueObject\DowngradeAttributeToAnnotation;
use Rector\DowngradePhp80\Rector\Class_\DowngradeAttributeToAnnotationRector;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    // get parameters
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PATHS, [
        __DIR__ . '/src'
    ]);

    // Define what rule sets will be applied
    //$containerConfigurator->import(LevelSetList::UP_TO_PHP_72);

    // get services (needed for register a single rule)
    $services = $containerConfigurator->services();
    $services->set(DowngradeAttributeToAnnotationRector::class)
    ->configure([new DowngradeAttributeToAnnotation(Route::class)]);
    $services->set(ClassPropertyAssignToConstructorPromotionRector::class);
;

    // register a single rule
    // $services->set(TypedPropertyRector::class);
};
