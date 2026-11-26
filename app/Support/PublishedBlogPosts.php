<?php

namespace App\Support;

use Illuminate\Support\Collection;

class PublishedBlogPosts
{
    public function __construct(private TourCatalog $catalog) {}

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function all(): Collection
    {
        return $this->catalog->posts();
    }
}
