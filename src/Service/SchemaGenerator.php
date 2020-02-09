<?php

namespace Genesis\BehatApiSpec\Service;

use ReflectionClass;

class SchemaGenerator
{
    public static function scaffoldSchema(string $body): array
    {
        $response = json_decode($body, true);

        $schema = [];
        foreach ($response as $property => $value) {
            $schema[$property] = ['type' => gettype($value)];
            switch (gettype($value)) {
                case 'array':
                    if (is_string(key($value))) {
                        $schema[$property]['type'] = 'object';
                    }
                    $schema[$property]['schema'] = self::scaffoldSchema(json_encode($value));
                    break;
            }
        }

        return $schema;
    }

    public static function suggestSchema(string $endpoint, array $schema, int $statusCode): string
    {
        $tab = 1;

        $getSchemaMethod = self::tab($tab) . 'public static function getSchema(): array' . PHP_EOL;
        $getSchemaMethod .= self::tab($tab) . '{' . PHP_EOL;
        $getSchemaMethod .= self::tab($tab+1) . 'return [' . PHP_EOL;
        $getSchemaMethod .= self::tab($tab+2) . $statusCode . ' => [' . PHP_EOL;
        $getSchemaMethod .= self::tab($tab+3) . '\'headers\' => [],' . PHP_EOL;
        $getSchemaMethod .= self::tab($tab+3) . '\'body\' => [' . PHP_EOL;
        $getSchemaMethod .= self::getSchemaPropertiesAsString($schema, 5);
        $getSchemaMethod .= self::tab($tab+3) . '],' . PHP_EOL;
        $getSchemaMethod .= self::tab($tab+2) . '],' . PHP_EOL;
        $getSchemaMethod .= self::tab($tab+1) . '];' . PHP_EOL;
        $getSchemaMethod .= self::tab($tab) . '}';

        return $getSchemaMethod;
    }

    public static function appendSchemaToEndpointSpec(string $apiSpec, string $schema): void
    {
        $file = self::getFilename($apiSpec);
        $contents = file_get_contents($file);
        $contents = preg_replace('/(.*)}/su', '${1}' . PHP_EOL . $schema . PHP_EOL . '}', $contents);

        file_put_contents($file, $contents);
    }

    private static function tab($count): string
    {
        return str_repeat(' ', $count*4);
    }

    private static function getSchemaPropertiesAsString(array $schema, int $tab): string
    {
        $getSchemaMethod = '';
        foreach ($schema as $property => $value) {
            if ($value['type'] === 'object' || $value['type'] === 'array') {
                $getSchemaMethod .= self::tab($tab) . "'$property' => [" . PHP_EOL;
                $getSchemaMethod .= self::tab($tab+1) . sprintf("'type' => self::TYPE_%s,", strtoupper($value['type'])) . PHP_EOL;
                $getSchemaMethod .= self::tab($tab+1) . "'optional' => false," . PHP_EOL;
                $getSchemaMethod .= self::tab($tab+1) . "'schema' => [" . PHP_EOL;
                $getSchemaMethod .= self::getSchemaPropertiesAsString($value['schema'], $tab+2);
                $getSchemaMethod .= self::tab($tab+1) . '],' . PHP_EOL;
                $getSchemaMethod .= self::tab($tab) . '],' . PHP_EOL;
            } else {
                $getSchemaMethod .= self::tab($tab) . "'$property' => [" . PHP_EOL;
                $getSchemaMethod .= self::tab($tab+1) . sprintf("'type' => self::TYPE_%s,", strtoupper($value['type'])) . PHP_EOL;
                $getSchemaMethod .= self::tab($tab+1) . "'optional' => false," . PHP_EOL;

                switch ($value['type']) {
                    case 'string':
                        $getSchemaMethod .= self::tab($tab+1) . "'pattern' => null," . PHP_EOL;
                        break;
                    case 'integer':
                        $getSchemaMethod .= self::tab($tab+1) . "'min' => null," . PHP_EOL;
                        $getSchemaMethod .= self::tab($tab+1) . "'max' => null," . PHP_EOL;
                        break;
                }

                $getSchemaMethod .= self::tab($tab) . '],' . PHP_EOL;
            }
        }

        return $getSchemaMethod;
    }

    private static function getFilename(string $class): string
    {
        return (new ReflectionClass($class))->getFileName();
    }
}
