<?php

namespace App\Controller\Dashboard\Admin\Field;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Contracts\Translation\TranslatableInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;

final class VichImageField implements FieldInterface
{
    use FieldTrait;

    /**
     * @param TranslatableInterface|string|false|null $label
     */
    public static function new(string $propertyName, $label = 'Image'): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setTemplatePath('dashboard/admin/field/vich_image.html.twig')
            ->setFormType(VichImageType::class)
            ->addCssClass('field-vich-image')
        ;
    }
}
