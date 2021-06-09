<?php

namespace App\Representation;

use App\Entity\Brand;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Comment;

abstract class AbstractRepresentation
{
    /**
     * @var mixed
     */
    public $data;

    /**
     * @var array<string, int|string>
     */
    public $meta;

    /**
     * Undocumented function
     *
     * @param iterable<Brand|Product|Category|Comment> $data
     * @param integer $page
     * @param integer $numberOfPages
     * @param integer $numberOfItemsPerPage
     */
    public function __construct($data, int $page, int $numberOfPages, int $numberOfItemsPerPage)
    {
        $this->data = $data;
        $this->addMeta('page', $page);
        $this->addMeta('total_pages', $numberOfPages);
        $this->addMeta('items_per_page', $numberOfItemsPerPage);
        $this->addMeta('current_items', count( (array) $data));
        $this->addMeta('delivered_at', (new \DateTime('now', new \DateTimeZone('Europe/Paris')))->format('Y/m/d H:i:s'));
    }

    /**
     * @param string $name
     * @param integer|string $value
     * @return void
     */
    public function addMeta(string $name, $value): void
    {
        if (isset($this->meta[$name])) {
            throw new \LogicException(sprintf('This meta already exists. You are trying to override this meta, use the setMeta method instead for the %s meta.', $name));
        }

        $this->setMeta($name, $value);
    }

    /**
     * @param string $name
     * @param integer|string $value
     * @return void
     */
    public function setMeta(string $name, $value): void
    {
        $this->meta[$name] = $value;
    }
}
