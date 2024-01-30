## HTTP Request as Criteria source

Let's see a brief example of how to implement a `CriteriaSource` along with **Laravel** HTTP Request.

First, create a [Form Request](https://laravel.com/docs/10.x/validation#creating-form-requests) to implement
the `CriteriaSource` interface.

```bash
php artisan make:request SearchUserRequest
```

Next, implement the interface:

```php
namespace LaravelMade\Http\Requests;

use ComplexHeart\Domain\Criteria\Contracts\CriteriaSource;
use Illuminate\Foundation\Http\FormRequest;

class SearchUserRequest extends FormRequest implements CriteriaSource
{
    public function filterGroups(): array
    {
        // get the filter groups from the request.
        // you can also return N groups of filters (OR).
        return [$this->input('filters', [])];
    }

    public function orderType(): string
    {
        return $this->input('order', 'none');
    }

    public function orderBy(): string
    {
        return $this->input('orderBy', '');
    }

    public function pageLimit(): int
    {
        return $this->input('limit', 25);
    }

    public function pageOffset(): int
    {
        return $this->input('offset', 0);
    }

    public function pageNumber(): int
    {
        return $this->input('page', 0);
    }
}
```

Done, now you only need to call the `fromSource` method of the `Criteria` object.

```php
Route::get('users', function (SearchUserRequest $request): JsonResponse {
    $criteria = Criteria::fromSource($request);
    
    // use criteria to fetch the users.
});
```

Additionally, you can add rules to the `FormRequest` object to ensure the `Criteria` is properly instantiated. If the
`Criteria` object cannot be instantiated a `CriteriaError` will be thrown.

```php
Route::get('users', function (SearchUserRequest $request): JsonResponse {
    try {
        $criteria = Criteria::fromSource($request);
    } catch (CriteriaError $e) {
        // handle the Criteria error
    }
    
    // use criteria to fetch the users.
});
```
