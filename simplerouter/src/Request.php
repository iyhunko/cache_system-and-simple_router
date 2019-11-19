<?php

namespace IvanHunko\SimpleRouter;

/**
 * Class Request
 * @package IvanHunko\SimpleRouter
 */
class Request
{
    /**
     * @var array
     */
    public $inputs = [
        'get_inputs' => [],
        'post_inputs' => [],
        'cli_inputs' => [],
        'stdin_inputs' => [],
    ];

    /**
     *
     */
    public function collectInputs()
    {
        $this->inputs = [
            'get_inputs' => $this->getGetInputs(),
            'post_inputs' => $this->getPostInputs(),
            'cli_inputs' => $this->getCliInputs(),
            'stdin_inputs' => $this->getStdinInputs(),
        ];
    }

    /**
     * @return array
     */
    private function getGetInputs(): array
    {
        return $_GET;
    }

    /**
     * read post as json from php://input or if empty try to read $_POST
     *
     * @return array
     */
    private function getPostInputs(): array
    {
        $postParams = $this->getPostJson();
        if (empty($postParams)) {
            $postParams = $_POST;
        }

        return $postParams;
    }

    /**
     * $_SERVER['argv'] - Contains an array of all the arguments passed to the script when running from the command line.
     *
     * @return array
     */
    private function getCliInputs(): array
    {
        $cliInputs = [];
        if (isset($_SERVER['argv'])) {
            foreach ($_SERVER['argv'] as $argument) {
                if (preg_match('/--([^=]+)=(.*)/', $argument, $reg)) {
                    $cliInputs[$reg[1]] = $reg[2];
                } elseif (preg_match('/-([a-zA-Z0-9])/', $argument, $reg)) {
                    $cliInputs[$reg[1]] = 'true';
                }
            }
        }

        return $cliInputs;
    }

    /**
     * @return array
     */
    private function getStdinInputs(): array
    {
        $fp = fopen("php://stdin", "r");
        $line = rtrim(fgets($fp));
        $result = json_decode($line, 1);

        return $result ? $result : [];
    }

    /**
     * @return array
     */
    private function getPostJson(): array
    {
        $json_params = file_get_contents("php://input");
        if (strlen($json_params) > 0 && $this->isValidJSON($json_params)) {
            $decoded_params = json_decode($json_params, 1);
        }

        return (isset($decoded_params) && $decoded_params) ? $decoded_params : [];
    }

    /**
     * @param $str
     * @return bool
     */
    private function isValidJSON($str): bool
    {
        json_decode($str);

        return json_last_error() === JSON_ERROR_NONE;
    }
}
