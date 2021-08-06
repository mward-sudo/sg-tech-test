<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Names\Parser;

class NameParser extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_single_name_parsing()
    {
        $namesAndResponses = [
            ['input' => 'Mr John Smith', 'response' => [['title' => 'Mr', 'first_name' => 'John', 'initial' => null, 'last_name' => 'Smith']]],
            ['input' => 'Mrs Jane Smith', 'response' => [['title' => 'Mrs', 'first_name' => 'Jane', 'initial' => null, 'last_name' => 'Smith']]],
            ['input' => 'Mister John Doe', 'response' => [['title' => 'Mister', 'first_name' => 'John', 'initial' => null, 'last_name' => 'Doe',]]],
            ['input' => 'Mr M Mackie', 'response' => [['title' => 'Mr', 'first_name' => null, 'initial' => 'M', 'last_name' => "Mackie",]]],
            ['input' => 'Mrs Faye Hughes-Eastwood', 'response' => [['title' => 'Mrs', 'first_name' => 'Faye', 'initial' => null, 'last_name' => 'Hughes-Eastwood',]]],
            ['input' => 'Mr F. Fredrickson', 'response' => [['title' => 'Mr', 'first_name' => null, 'initial' => 'F', 'last_name' => 'Fredrickson',]]]
        ];

        for ($i = 0; $i < count($namesAndResponses); $i++) {
            $parser = new Parser($namesAndResponses[$i]['input']);
            $parserResults = $parser->parse();

            $this->assertEquals($namesAndResponses[$i]['response'], $parserResults, $i);
        }
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_multiple_name_parsing()
    {
        $namesAndResponses = [
            ['input' => 'Mr and Mrs Smith', 'response' => [
                ['title' => 'Mrs', 'first_name' => null, 'initial' => null, 'last_name' => 'Smith',],
                ['title' => 'Mr', 'first_name' => null, 'initial' => null, 'last_name' => 'Smith',],
            ]],
            ['input' => 'Mr Tom Staff and Mr John Doe', 'response' => [
                ['title' => 'Mr', 'first_name' => "Tom", 'initial' => null, 'last_name' => "Staff",],
                ['title' => 'Mr', 'first_name' => 'John', 'initial' => null, 'last_name' => 'Doe',],
            ]],
            ['input' => 'Dr & Mrs Joe Bloggs', 'response' => [
                ['title' => 'Mrs', 'first_name' => 'Joe', 'initial' => null, 'last_name' => 'Bloggs',],
                ['title' => 'Dr', 'first_name' => 'Joe', 'initial' => null, 'last_name' => 'Bloggs',]
            ]]
        ];

        for ($i = 0; $i < count($namesAndResponses); $i++) {
            $parser = new Parser($namesAndResponses[$i]['input']);
            $parserResults = $parser->parse();

            $this->assertEquals($namesAndResponses[$i]['response'], $parserResults, $i);
        }
    }
}
