<?php

declare(strict_types=1);

namespace App\Entity\Data;

class SearchData
{
    /** @var int */
    public $page = 1;

    public string $keywords = '';

    public array $categories = [];
}
