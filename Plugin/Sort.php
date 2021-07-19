<?php

namespace Appstractsoftware\MagentoAdapter\Plugin;

class Sort
{
    private function skipIsSalable($var)
    {
        return array_keys($var)[0] !== 'is_salable';
    }

    public function afterGetSort($subject, array $result)
    {
        return array_values(array_filter($result, array($this, 'skipIsSalable')));
    }
}
