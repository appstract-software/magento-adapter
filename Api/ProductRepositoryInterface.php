<?php

namespace Appstractsoftware\MagentoAdapter\Api;

interface ProductsRepositoryInterface
{
  public function getList($limit = 10);
}
