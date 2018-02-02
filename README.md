## Entities Rating bundle

#### Installation

The easiest way is with [Composer](https://getcomposer.org/) package manager
``` json
"require": {
    "cymo/entity-rating-bundle": "dev-master"
}
```

Configure kernel:

``` php
// app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
        // ...
        new Cymo\Bundle\EntityRatingBundle\CymoEntityRatingBundle(),
        // ...
    );
}
```

Configure routes:
``` yaml
# app/config/routing.yml
cymo_entity_rating:
    resource: "@CymoEntityRatingBundle/Resources/config/routing.yml"
    prefix:   /
```
#### Add the annotation to your class
```php
# Example : Acme\BlogBundle\Entity\Post
/**
 * @Rated(min=1, max=5, step=1)
 */
```

#### Configuration

```yaml
# app/config/config.yml
cymo_entity_rating:
     # Your EntityRate entity namespace, persisted in the DB
     entity_rating_class: Blogtrotting\AdventureBundle\Entity\EntityRate
     # If you decide to extend the default manager, put the service name here
     entity_rating_manager_service: blogtrotting.entity_rating.manager
     # Maximum number of rate allowed by IP
     rate_by_ip_limitation: 10
     # Map the alias used in frontend and the corresponding class
     map_type_to_class:
       post: Acme\BlogBundle\Entity\Post
       article: Acme\BlogBundle\Entity\Article
```
### Events

The bundle dispatches events when a rate is : 
- Created : `cymo.entity_rating.rate_created`
- Updated : `cymo.entity_rating.rate_updated`