# Content query field pagination support

Being able to paginate through query field results is a hard requirement in all APIs:

- PHP
- Twig
- REST
- GraphQL

The entry point has to be the `QueryFieldService`, since it used by all of these.

## GraphQL

```
{
  content {
    gallery(id: $id) {
      title
      images(first: 10) {
        edges {
          node {
            name
            image {
              uri
            }
          }
        }
      }
    }
  }
}
```

## REST

`offset` and `limit` parameters passed to the resource.

## PHP

```php
$queryFieldService->loadFieldData(
  $content, 
  $fieldDefIdentifier
);
```

### Pagination object

We should add an object that abstracts how pagination is requested.

```
class PageNumberPageRequest
{
  public $pageNumber;
}

class CursorPageRequest
{
  public $cursor;
}
```

These objects get passed to `QueryFieldService::loadField()` . They are then mapped to whatever is usable behind the scene for pagination.

## Twig

```twig
{{ ez_render_field(content, 'images') }}
```

The page (or offset/limit arguments) come from links within the results pager. It means that the template that iterates on results should be the one that uses the pager to iterate. The pagination code should therefore be in the controller, not in the template itself.

A parameter to enable pagination, or set the page size, may be relevant from the caller. Paging options could also be added to the field definition (enable/disable, set the page size...)

### PagerFanta ?

We probably want to use PagerFanta for twig based pagination. Do we need an adapter ? On what ? The controller will call `loadFielData()`, passing page info. This is what we need to create a `PagerFanta` adapter for.

Requirements for an adapter:

- fetch a slice of results (`loadFieldData()` with pagination arguments)
  (rename `loadFieldData()` to `loadResults()`)
- count total results (`countResults()`)