# Symfony Request Param Bagger

Collects sent parameters from Request and returns them in an array. 

Features:
* assign default values
* cast to a specific type
* returns an array

## Usage 

Request example
```json 
{
    "type": "gold",
    "size": "20",
    "amount": "1.23"
}
```

Parse example
```php 
<?php 
    use PhpArsenal\SymfonyRequestParamBagger\RequestParamBagger;

    // ...
    
    #[Route('/api', methods: ['POST'], format: 'json')]
    public function postSomething(
        Request $request
    ): JsonResponse {
        $params = RequestParamBagger::build($request, [
            'type' => null,
            'size' => null,
            'amount' => null,
        ], [
            'type' => 'string',
            'size' => 'int',
            'amount' => 'float',
        ]);
        
        var_dump($params);
        
        // ...
```

Output
```text
array(3) {
  ["type"]=>
  string(4) "gold"
  ["size"]=>
  int(20)
  ["amount"]=>
  float(1.23)
}
```