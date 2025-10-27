<?php

namespace App\Controller\Competition;

use Symfony\Component\HttpFoundation\Request;
use App\Shared\Infrastructure\Validation\JsonSchemaValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Aggregate\Competition\Application\RegisterPlayerToCompetition\RegisterPlayerToCompetitionCommand;
use App\Aggregate\Competition\Application\RegisterPlayerToCompetition\RegisterPlayerToCompetitionHandler;

class CompetitionRegisterPlayerController extends AbstractController
{
    public function __construct(
        private readonly RegisterPlayerToCompetitionHandler $registerPlayerToCompetitionHandler,
        private readonly JsonSchemaValidator $jsonSchemaValidator,
    ) {}

    public function __invoke(Request $request)
    {
        // Recuperamos los datos del request
        $body = json_decode($request->getContent(), true);
        $dataObject = json_decode(json_encode($body));

        // // Validamos el request contra el esquema JSON
        $this->jsonSchemaValidator->__invoke(
            $dataObject,
            $this->schema()
        );

        $this->registerPlayerToCompetitionHandler->__invoke(
            new RegisterPlayerToCompetitionCommand(
                $body['competitionId'],
                $body['playerRederatedCode'],
            )
        );
    }

    private function schema(): string
    {
        $json = <<<'JSON'
        {
            "$schema": "http://json-schema.org/draft-07/schema#",
            "title": "Register Player to Competition",
            "type": "object",
            "properties": {
                "competitionId": {
                    "type": "string"
                },
                "playerFederatedCode": {
                    "type": "string"
                }
            },
            "required": [
                "competitionId",
                "playerFederatedCode"
            ],
            "additionalProperties": false
        }
        JSON;

        return $json;
    }
}
