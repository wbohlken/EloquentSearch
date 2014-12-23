<?php
namespace Nutshell\EloquentSearch\Traits;

use Illuminate\Support\Facades\Input as RequestInput;

trait ExpectedInputTrait
{
    public static function expectedInput($variableName = "expectedInput")
    {
        $inputData = RequestInput::all();
        if (!isset(self::$$variableName)) {
            throw new \Exception("Parent class must have a static \$$variableName array.");
        }

        $expectedInput = [];
        foreach (self::$$variableName as $key) {
            $expectedInput[$key] = isset($inputData[$key]) ? $inputData[$key] : "";
        }
        return $expectedInput;
    }
}
