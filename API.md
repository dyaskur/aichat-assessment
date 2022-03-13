## Check the Eligibility

We can use this API to check the eligibility of a customer for a given promotion.

* Check validity of the promotion code
* Check the eligibility of a customer for a given promotion.
* If qualified, lockdown a voucher for 10 minutes to this customer.
  **URL** : `/api/voucher/check`

**Method** : `POST`

**Auth required** : Yes (can bypass by add `customer_email` in body)

**POST Body**(JSON or Form Data) :

* `code` : _string_ - Voucher code, Required
* `customer_email` : _string_ - Customer Email, Optional (to bypass auth)

Example Request 1:

```json
{
  "code": "anniversary",
  "customer_email": "eligible@customer.com"
}
```

Example Response 1:

```json
{
  "success": true,
  "message": "You eligible to claim voucher code by validate your photo",
  "data": null
}
```

Example Request 2:

```json
{
  "code": "anniversary",
  "customer_email": "poor@customer.com"
}
```

Example Response 2:

```json
{
  "success": false,
  "message": "You are not eligible for this promotion",
  "data": null
}
```
Example Request 3:

```json
{
  "code": "anniversary",
  "customer_email": "unlucky@customer.com"
}
```

Example Response 3:

```json
{
  "success": false,
  "message": "No available promotion codes",
  "data": null
}
```

## Claim the voucher code

We can use this API to claim the voucher code by upload photo to validate customer.

* Check validity of the promotion code
* Call the image recognition API to validate the photo submission qualification.
  (Please faking this process for now, you do not need to create the image recognition API)
* If the image recognition result return is true and the submission within 10 minutes, allocate the locked voucher to the customer and
  return the voucher code.
* If the result return is false or submission exceeds 10 minutes, remove the lock down and this voucher will become available to the next
  customer to grab.

**URL** : `/api/voucher/claim`

**Method** : `POST`

**Auth required** : Yes (can bypass by add `customer_email` in body)

**POST Body**(Multipart Form Data) :

* `code` : _string_ - Voucher code, Required
* `photo` : _file_ - Photo file that need to be validated, Required
* `customer_email` : _string_ - Customer Email, Optional (to bypass auth)

Example Request 1:

| Form Key       | Form Value            |
|----------------|-----------------------|
| customer_email | eligible@customer.com |
| photo          | valid_photo.jpg       |
| code          | anniversary       |

Example Response 1:

```json
{
    "status": true,
    "message": "Congratulations, you have claimed your voucher code",
    "data": {
        "code": "anniv9lL9sFI0BE9eo6CfvN8x"
    }
}
```

Example Request 2:

| Form Key       | Form Value        |
|----------------|-------------------|
| customer_email | poor@customer.com |
| photo          | valid_photo.jpg   |
| code          | anniversary       |

Example Response 2:

```json
{
    "success": false,
    "message": "Invalid/expired locked code, feel free to redeem the code (again)",
    "data": null
}
```

Example Request 3:

| Form Key       | Form Value             |
|----------------|------------------------|
| customer_email | eligible3@customer.com |
| photo          | invalid_image.jpg      |
| code          | anniversary            |

Example Response 3:

```json
{
    "success": false,
    "message": "Your photo validation is invalid, the booked code is released to other customers. but you can book it again",
    "data": null
}
```
