<?php
namespace App\Traits;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;

trait HIBP
{
    use Http;

    protected string $url = 'https://haveibeenpwned.com/api/v3';

    public function pwned(string $email)
    {
        return Cache::remember(__METHOD__.":{$email}", now()->addWeek(), function () use ($email) {
            $breach = $this->getService('breachedaccount', $email);
            $paste = $this->getService('pasteaccount', $email);

            sleep(4); // Slow down to avoid the HIBP rate limiter

            return $breach || $paste;
        });
    }

    protected function getService(string $service, string $email): bool
    {
        return $this->http()
            ->withHeaders(['hibp-api-key' => config('services.hibp.key')])
            ->get($this->url.'/'.$service.'/'.urlencode($email))
            ->onError($this->onError())
            ->successful();
    }

    protected function onError()
    {
        return fn (Response $response) => $response->status() === 404 || $response->throw();
    }
}
