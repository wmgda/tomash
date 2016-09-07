<?php

namespace Domain\UseCase\Lunch\InitializeOrder;

use Domain\Model\Lunch\Restaurant;

interface Responder
{
    /**
     * @param Restaurant $menu
     */
    public function orderInitializedSuccessfully(Restaurant $restaurant);

    /**
     * @param \Exception $e
     */
    public function orderInitializationFailed(\Exception $e);
}
