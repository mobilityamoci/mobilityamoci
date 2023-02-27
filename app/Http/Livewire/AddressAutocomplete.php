<?php

namespace App\Http\Livewire;


use GuzzleHttp\Client;
use Livewire\Component;

class AddressAutocomplete extends Component
{

    public string $searchTerm;
    public $results;


    public function render()
    {
        return view('livewire.address-autocomplete');
    }


    public function fetchResults()
    {
        $client = new Client([
            'base_uri' => 'https://api.nomination.com/',
        ]);

        $response = $client->request('GET','autocomplete', [
            'query' => [
                'searchTerm' => $this->searchTerm
                ]
        ]);

        $this->results = json_decode($response->getBody());
    }
}
