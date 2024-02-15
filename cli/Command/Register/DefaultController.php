<?php

namespace Phparch\SpaceTradersCLI\Command\Register;

use Minicli\Command\CommandController;

class DefaultController extends CommandController
{
    public function handle(): void
    {
        $ch = curl_init();

        $data = json_encode([
            'symbol' => 'PHP_ARCHIE',
            'faction' => 'COSMIC'
        ]);
		curl_setopt(
            $ch, \CURLOPT_URL,
            'https://api.spacetraders.io/v2/register'
        );
		curl_setopt($ch, \CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
		curl_setopt($ch, \CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, \CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($ch);
		$this->success($response);
    }
}