<?php

namespace PhpArsenal\SymfonyRequestParamBagger;

use Symfony\Component\HttpFoundation\Request;

class RequestParamBagger
{
    public static function build(Request $request, array $defaultParams = [], array $paramTypes = []): array
    {
        $params = array_replace_recursive(
            $request->request->all(),
            $request->query->all(),
            $request->attributes->all(),
        );
        
        return static::buildFromArray($params, $defaultParams, $paramTypes);
    }
    
    public static function buildFromArray(array $params, array $defaultParams = [], array $paramTypes = []): array {
        static::setDefaultValues($params, $defaultParams);

        static::unsetPrivate($params);

        return static::cast($params, $paramTypes);
    }

    private static function setDefaultValues(array &$params, array $defaultParams): void
    {
        if (count($defaultParams) === 1 && isset($defaultParams['_children'])) {
            $defaultChildParams = $defaultParams['_children'];
            foreach($params as &$paramChildValue) {
                if (is_array($paramChildValue)) {
                    static::setDefaultValues($paramChildValue, $defaultChildParams);
                }
            }
        }

        foreach ($defaultParams as $paramKey => $defaultParam) {
            if(!isset($params[$paramKey]) && $paramKey) {
                $params[$paramKey] = $defaultParam;
            }
            else if(is_array($params[$paramKey]) && is_array($defaultParam) && !isset($defaultParam['_children'])) {
                static::setDefaultValues($params[$paramKey], $defaultParam);
            }
            else if(is_array($params[$paramKey]) && is_array($defaultParam) && isset($defaultParam['_children'])) {
                $defaultChildParams = $defaultParam['_children'];
                foreach($params[$paramKey] as &$paramChildValue) {
                    static::setDefaultValues($paramChildValue, $defaultChildParams);
                }
            }
        }
    }

    private static function unsetPrivate(array &$params): void
    {
        $params = array_filter($params, function ($key) {
            return !str_starts_with($key, '_'); // is not private
        }, ARRAY_FILTER_USE_KEY);

        foreach($params as &$param) {
            if(is_array($param)) {
                static::unsetPrivate($param);
            }
        }
    }

    private static function cast(array &$params, array $paramTypes): array
    {
        if (count($paramTypes) === 1 && isset($paramTypes['_children'])) {
            $defaultChildTypes = $paramTypes['_children'];
            foreach($params as &$paramChildValue) {
                if (is_array($paramChildValue)) {
                    static::cast($paramChildValue, $defaultChildTypes);
                }
            }
        }

        foreach ($params as $paramKey => &$paramValue) {
            $paramType = $paramTypes[$paramKey] ?? null;

            if (!$paramType) {
                continue;
            } elseif (is_array($paramValue) && is_array($paramType) && isset($paramType['_children'])) {
                $paramChildType = $paramType['_children'];
                foreach($paramValue as &$paramChildValue) {
                    static::cast($paramChildValue, $paramChildType);
                }
                continue;
            } elseif (is_array($paramValue) && is_array($paramType)) {
                static::cast($paramValue, $paramType);
                continue;
            }

            if ($paramValue === null) {
                continue;
            } elseif (is_array($paramValue) && $paramType !== 'array') {
                throw new \Exception(sprintf('Param `%s` expected to be `%s` but `array` was given.', $paramKey, $paramType));
            } elseif ('string' === $paramType) {
                $paramValue = (string)$paramValue;
            } elseif (in_array($paramType, ['int', 'integer'])) {
                $paramValue = (int)$paramValue;
            } elseif ('float' === $paramType) {
                $paramValue = (float)str_replace(',', '.', $paramValue);
            } elseif (in_array($paramType, ['bool', 'boolean'])) {
                $paramValue = $paramValue === 'true' || $paramValue === '1' || $paramValue === 1 || $paramValue === true;
            }
        }

        return $params;
    }
}
