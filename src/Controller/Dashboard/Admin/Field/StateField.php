<?php

declare(strict_types=1);

namespace App\Controller\Dashboard\Admin\Field;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Contracts\Translation\TranslatableInterface;

final class StateField implements FieldInterface
{
    use FieldTrait;

    /**
     * @param TranslatableInterface|string|false|null $label
     */
    public static function new(string $propertyName, $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setTemplatePath('dashboard/admin/field/state.html.twig')
        ;
    }

    public function setWorkflowName(string $workflowName): self
    {
        $this->setCustomOption('workflow', $workflowName);

        return $this;
    }
}
