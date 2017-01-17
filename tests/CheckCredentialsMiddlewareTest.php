<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Http\Middleware\CheckMeliCredentials;
use App\Entities\Credential;
use Illuminate\Http\Request;

class CheckCredentialsMiddlewareTest extends TestCase
{

    public function testShouldKnowLiftCommand()
    {


    }
}
