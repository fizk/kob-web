<?php
namespace App\Service;

use Elasticsearch\Client;

class Search
{
    /** @var \Elasticsearch\Client */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function search(string $query, $language = 'is')
    {
        $field = $language === 'is' ? 'body_is' : 'body_en';

        $result = $this->client->search([
            'index' => 'kob_entry',
            'body' => [
                "from" => 0,
                "size" => 20,
                'highlight' => [
                    'pre_tags' => ['<em class="highlight">'],
                    'post_tags' => ['</em>'],
                    'fields' => [
                        'text' => new \stdClass(),
                        'title' => new \stdClass(),
                        $field => new \stdClass(),
                        'authors.name' => new \stdClass(),
                    ],
                    'require_field_match' => false
                ],
                "query" => [
                    "bool" => [
                        "must" => [
                            [
                                "bool" => [
                                    "should" => [
                                        [
                                            "match" => [
                                                "title" => [
                                                    "query" => $query,
                                                    "boost" => 5
                                                ]
                                            ]
                                        ],
                                        [
                                            "match" => [
                                                $field => [
                                                    "query" => $query,
                                                ]
                                            ]
                                        ],
                                        [
                                            "match" => [
                                                "author.name" => [
                                                    "query" => $query,
                                                    "boost" => 10
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]);
//print_r($result['hits']['hits']);
//die();
        $response = array_map(function ($item) {
            return array_merge($item['_source'], ['_score' => $item['_score']]);
        }, $result['hits']['hits']);

//        print_r($result); die();

        for ($i = 0; $i < count($result['hits']['hits']); $i++) {
            if (!array_key_exists('highlight', $result['hits']['hits'][$i])) {
                $response[$i]['body'] = null;
                continue;
            }
            $highlight = $result['hits']['hits'][$i]['highlight'];
            if (array_key_exists('title', $highlight) || array_key_exists('authors', $highlight)) {
                $response[$i]['body'] = null;
                continue;
            }
            if (array_key_exists($field, $highlight)) {
                $response[$i]['body'] = array_reduce($highlight[$field], function ($carry , $item) {
                    return $carry . ' ... ' . $item;
                }, '') ;
            } else {
                $response[$i]['body'] = null;
            }
        }

        return [
            'results' => $response,
            'count' => $result['hits']['total']['value'],
            'query' => $query,
        ];
    }

    public function save($item): bool
    {
        try {
            $this->client->update([
                'index' => 'kob_entry',
                'id' => $item->id,
                'body' => ['doc'  => (array) $item],
            ]);
            return true;
        } catch (\Exception $e) {
            try {
                $this->client->index([
                    'index' => 'kob_entry',
                    'id' => $item->id,
                    'body' => (array) $item,
                ]);
                return true;
            } catch (\Exception $e) {
                echo $e->getMessage() . PHP_EOL;
                return false;
            }
        }
    }

}
