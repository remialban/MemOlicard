<?php

namespace App\Service\TypeSense;

use Typesense\Client;
use App\Repository\UserRepository;

class TypeSense {
    private string $apiKey;
    private string $host;
    private string $port;
    private string $protocol;
    private UserRepository $userRepository;

    public function __construct(
        string $apiKey,
        string $host,
        string $port,
        string $protocol,
        UserRepository $userRepository)
    {
        $this->apiKey = $apiKey;
        $this->host = $host;
        $this->port = $port;
        $this->protocol = $protocol;
        $this->userRepository = $userRepository;
    }

    public function getClient()
    {
        return new Client([
            'api_key'         => $this->apiKey,
            'nodes'           => [
              [
                'host'     => $this->host,
                'port'     => $this->port,
                'protocol' => $this->protocol,
              ],
            ],
            'connection_timeout_seconds' => 2,
        ]);
    }

    public function updateSchema()
    {
        $collections = $this->getClient()->collections;

        $schemas = [
            [
                "name" => "users",
                "fields" => [
                    ['name' => 'id', 'type' => 'string'],
                    ['name' => 'username', 'type' => 'string'],
                    ['name' => 'biography', 'type' => 'auto'],
                    ['name' => 'firstName', 'type' => 'string'],
                    ['name' => 'lastName', 'type' => 'string'],
                ]
            ]
        ];

        foreach ($schemas as $schema) {
            try {
                $collections[$schema['name']]->delete();
            } catch (\Exception $exception) {}
            $collections->create($schema);
        }
    }

    public function populate()
    {
        $this->updateSchema();
        $users=[];
        foreach ($this->userRepository->findAll() as $user)
        {
            $users[] = [
                "id" => strval($user->getId()),
                "firstName" => $user->getFirstName(),
                "lastName" => $user->getLastName(),
                "username" => $user->getUsername(),
                "biography" => $user->getBiography(),

            ];
        }
        $collection = $this->getClient()->collections['users'];
        $collection->documents->import($users, ['action' => 'create']);
    }

    public function search(string $type, string $query, int $perPage, int $currentPage)
    {
        $searchParameters = [
            'q'         => $query,
            'per_page' => $perPage,
            'page' => $currentPage,
        ];
        if ($type == "users")
        {
            $searchParameters['query_by'] = "firstName, lastName, username, biography";
        }
        return $this->getClient()->collections[$type]->documents->search($searchParameters);
    }
}
