<?php

namespace Domain\Model\Lunch;

use Domain\Exception\RestaurantMenuFileNotFoundException;
use Domain\Exception\RestaurantMenuParsingException;

class Restaurant
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $fullName;

    /**
     * @var MenuItem[]
     */
    private $menu;

    /**
     * @param string $name
     * @param string $fullName
     * @param string $fullName
     */
    public function __construct(string $name, string $fullName)
    {
        $this->name = $name;
        $this->fullName = $fullName;
        $this->loadMenu();
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getFullName() : string
    {
        return $this->fullName;
    }

    /**
     * @return MenuItem[]
     */
    public function getMenu() : array
    {
        return $this->menu;
    }

    private function loadMenu()
    {
        $menuFilePath = __DIR__ . 'Data/' . $this->name . '.json';

        if(!file_exists($menuFilePath)) {
            throw new RestaurantMenuFileNotFoundException($menuFilePath);
        }

        $menuAsJson = file_get_contents($menuFilePath);
        $menuAsJson = json_decode($menuAsJson, true);
        if($menuAsJson === false) {
            throw new RestaurantMenuParsingException($menuFilePath);
        }

        foreach($menuAsJson as $menuItem) {
            $this->menu[] = new MenuItem(
                $menuItem['position'],
                $menuItem['name'],
                new MenuItemPrice($menuItem['price']['zl'], $menuItem['price']['gr'])
            );
        }
    }
}
