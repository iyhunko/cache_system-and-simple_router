<?php

namespace IvanHunko\SimpleRouter;

use IvanHunko\SimpleRouter\Request;

class IndexController
{
    /**
     * @param \IvanHunko\SimpleRouter\Request $request
     * @return array
     */
    public function index(Request $request): array
    {
        $inputs = $request->inputs;
        $result = [];
        foreach ($inputs as $key => $items) {
            $result = array_merge($result, $items);
        }
        ksort($result);

        return $result;
    }

}
