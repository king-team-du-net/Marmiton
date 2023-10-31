<?php

declare(strict_types=1);

namespace App\Controller\Dashboard\Admin\Field;

use App\Entity\Enum\StatusEnum;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Contracts\Translation\TranslatableInterface;

final class StatusNameField implements FieldInterface
{
    use FieldTrait;

    /**
     * @param TranslatableInterface|string|false|null $label
     */
    public static function new(string $propertyName, $label = null): self
    {
        return (new self())
            // ->setFormType(EnumType::class)
            // ->setFormTypeOption('class', StatusEnum::class)
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setTemplatePath('dashboard/admin/field/status-name.html.twig')
        ;
    }
}
