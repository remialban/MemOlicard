<?php

namespace App\Service\TypeSense;

use App\Entity\User;
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

    public function getSchema()
    {
        $schema = [];
        $schema[User::class] = [
            "name" => "users",
            "fields" => [
                ['name' => 'id', 'type' => 'string'],
                ['name' => 'username', 'type' => 'string'],
                ['name' => 'biography', 'type' => 'auto'],
                ['name' => 'firstName', 'type' => 'string'],
                ['name' => 'lastName', 'type' => 'string'],
            ]
        ];
        return $schema;
    }

    public function updateSchema()
    {
        $collections = $this->getClient()->collections;
        foreach ($this->getSchema() as $key => $schema) {
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
            $users[] = $this->objectToArray($user);
        }
        $collection = $this->getClient()->collections['users'];
        $response = $collection->documents->import($users, ['action' => 'create']);
    }

    public function objectToArray($object)
    {
        $fields = $this->getSchema()[get_class($object)]['fields'];
        $array = [];
        foreach ($fields as $field) {
            $name = $field['name'];
            if ($name == "id")
            {
                $array['id'] = strval($object->getId());
            } else {
                $array[$name] = $object->{"get" . ucfirst($name)}();
            }
        }
        return $array;
    }

    public function update($object)
    {
        $document = $this->objectToArray($object);
        $collectionName = $this->getSchema()[get_class($object)]["name"];
        return $this->getClient()->collections[$collectionName]->documents->upsert($document);          
    }

    public function delete($object)
    {
        $collectionName = $this->getSchema()[get_class($object)]["name"];
        return $this->getClient()->collections[$collectionName]->documents[$object->getId()]->delete();
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
            $searchParameters['query_by'] = "firstName, lastName, username";
        }
        return $this->getClient()->collections[$type]->documents->search($searchParameters);
    }
}
