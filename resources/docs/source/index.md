---
title: API Reference

language_tabs:
- bash
- javascript
- php

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Info

Welcome to the Shipment API reference.
[Get Postman Collection](http://localhost:8000/docs/collection.json)

<!-- END_INFO -->

#Shipment Microservice


<!-- START_afd89bcfaa57f6a3f44dd3b895007613 -->
## api/v1/lunar_time
> Example request:

```bash
curl -X POST \
    "http://localhost:8000/api/v1/lunar_time" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Api-Version: v1" \
    -d '{"utc_date":"2021-10-10 10:21:33"}'

```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/lunar_time"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Api-Version": "v1",
};

let body = {
    "utc_date": "2021-10-10 10:21:33"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->post(
    'http://localhost:8000/api/v1/lunar_time',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Api-Version' => 'v1',
        ],
        'json' => [
            'utc_date' => '2021-10-10 10:21:33',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status_code": 200,
    "status": "success",
    "lunar_datetime": "54-11-01 ∇ 13:22:44 LST. The day name is CERNAN"
}
```
> Example response (422):

```json
{
    "utc_date": [
        "UTC datetime is required"
    ]
}
```
> Example response (422):

```json
{
    "utc_date": [
        "Valid UTC (y-m-d h:m:s) date required. Example (2021-01-05 12:10:34)"
    ]
}
```

### HTTP Request
`POST api/v1/lunar_time`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `utc_date` | datetime |  required  | A valid UTC datetime. Example (2021-01-05 12:10:34)
    
<!-- END_afd89bcfaa57f6a3f44dd3b895007613 -->