<?php

declare( strict_types = 1 );

namespace App\Support;

use GuzzleHttp\Client;
use Override;
use Taavi\LaravelSocialiteMediawiki\Socialite\One\WikiSocialiteServer;

/**
 * OAuth1 server for MediaWiki that sends its requests through the web proxy.
 */
class ProxiedMediaWikiServer extends WikiSocialiteServer
{
	#[Override]
	public function createHttpClient(): Client
	{
		return new Client( WebProxy::options() );
	}
}
