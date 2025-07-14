<?php

namespace App\Http\Controllers;

use App\Models\GmodServers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use DateTimeImmutable;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\JwtFacade;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

use function var_dump;
class GmodServersController extends Controller
{
    public function retrieve(){

        $key = InMemory::base64Encoded(
            'hiG8DlOKvtih6AxlZn5XKImZ06yu8I3mkOzaJrEuW8yAv8Jnkw330uMt8AEqQ5LB'
        );

        $token = (new JwtFacade())->issue(
            new Sha256(),
            $key,
            static fn (
                Builder $builder,
                DateTimeImmutable $issuedAt
            ): Builder => $builder
                ->issuedBy('https://api.my-awesome-app.io')
                ->permittedFor('https://client-app.io')
                ->expiresAt($issuedAt->modify('+10 minutes'))
        );
        $areas = GmodServers::all();

        $output = [];

        foreach ($areas as $area) {
            $area_data = array(
                "name" => $area->name,
                "api_key" => $area->api_key,
            );;
            $output[] = $area_data;
        }

        return $token->toString();
    }

    public function register(Request $request)
    {
        $server = GModServer::create([
            'name' => $request->input('name'),
            'ip' => $request->ip(),
            'api_key' => Str::random(64), // Generate the API key here
        ]);

        return response()->json([
            'message' => 'Server registered',
            'api_key' => $server->api_key,
        ]);
    }
}
