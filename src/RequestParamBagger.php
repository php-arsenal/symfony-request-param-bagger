<?php

namespace PhpArsenal\SymfonyRequestParamBagger;

use Symfony\Component\HttpFoundation\Request;

class RequestParamBagger
{
    public static function build(Request $request, array $defaultParams = [], array $paramTypes = []): array
    {
        $params = array_replace_recursive(
            $defaultParams,
            $request->request->all(),
            $request->query->all(),
            $request->attributes->all(),
        );

        $params = array_filter($params, function ($key) {
            return !str_starts_with($key, '_'); // is not private
        }, ARRAY_FILTER_USE_KEY);

        return static::cast($params, $paramTypes);
    }

    private static function cast(array &$params, array $paramTypes): array
    {
        foreach ($params as $paramKey => &$paramValue) {
            $paramType = $paramTypes[$paramKey] ?? null;

            if (!$paramType) {
                continue;
            } elseif (is_array($paramValue) && is_array($paramType)) {
                static::cast($paramValue, $paramType);
                continue;
            }

            if ($paramValue === null) {
                continue;
            } elseif (is_array($paramValue) && 'array' !== $paramType) {
                throw new \Exception(sprintf('Param `%s` expected to be `%s` but `array` was given.', $paramKey, $paramType));
            } elseif ('string' === $paramType) {
                $paramValue = (string)$paramValue;
            } elseif (in_array($paramType, ['int', 'integer'])) {
                $paramValue = (int)$paramValue;
            } elseif ('float' === $paramType) {
                $paramValue = (float)$paramValue;
            } elseif (in_array($paramType, ['bool', 'boolean'])) {
                $paramValue = (bool)$paramValue;
            }
        }

        return $params;
    }
}
