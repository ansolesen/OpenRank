<?php
namespace Dresing\OpenRank;
use App\Services\MethodCache;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

/**
 * Interacts with the OpenRank API
 */
class OpenRank
{
    public $client;
    public $crawler;
    private $apiKey;
    private $minutesToCacheFor;
    private $cacheRepository;


    /**
     * Initializes an instance of OpenRank
     *
     * @param [string] $apiKey [The API key to interact with.]
     */
    function __construct(Client $client, $apiKey, $cacheRepository, $minutesToCacheFor = 1450)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
        $this->cacheRepository = $cacheRepository;
        $this->minutesToCacheFor =$minutesToCacheFor;
    }

    /**
     * Given a single domain will calculate the appropriate OpenRank score.
     * If given an array of domains, it will do bulk requests and always return an associative array with scores.
     * @return [float] [description]
     */
    public function getRank($domain)
    {
        $bulk = is_array($domain);
        $domain = $bulk ? $domain : [$domain];

       $out = $this->cacheRepository->remember(implode($domain), $this->minutesToCacheFor, function () use($domain, $bulk) {
            $response = $this->get('', [
                'd' => implode('|',$domain)
            ]);
            $data = collect($this->getData($response))->map(function($site) {
                return $site->openrank;
            });
            return $data->count() == 1 && !$bulk ? $data->first() : $data->toArray();
        });
       return is_array($out) ? $out : (int) $out;
    }

    /**
     * Perform GET request
     * @param  [string] $endpoint [endpoint to hit]
     * @param  array  $query    [The request query]
     * @return [Response]           [Guzzle Response object]
     */
    public function get($endpoint, array $query) : Response
    {
        $query['key'] = $this->apiKey;
        return $this->client->request('GET', $endpoint, [
            'query' => $query
        ]);
    }

    /**
     * [Convert a guzzle response into a data object]
     * @param  Response $response [description]
     * @return [obj]
     */
    private function getData(Response $response)
    {
        return (array) json_decode($response->getBody()->getContents())->data;
    }
}
