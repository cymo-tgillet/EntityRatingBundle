## Entity Rating bundle

![Rating example](http://screensha.re/6FFd5/cpLRe0YZQv.png)

#### Installation

The easiest way is with [Composer](https://getcomposer.org/) package manager
```json
"require": {
    "cymo/entity-rating-bundle": "^1.0"
}
```

Register the bundle:

```php
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

Import routes:
```yaml
# app/config/routing.yml
cymo_entity_rating:
    resource: "@CymoEntityRatingBundle/Resources/config/routing.yml"
    prefix:   /
```
#### Configuration

Extending the abstract entity to create your own
```php
// src/Acme/AppBundle/Entity/EntityRate.php

namespace Acme\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Cymo\Bundle\EntityRatingBundle\Entity\EntityRate as BaseEntityRate;

/**
 * EntityRate
 * @ORM\Table(name="entity_rate")
 * @ORM\Entity(repositoryClass="Cymo\Bundle\EntityRatingBundle\Repository\EntityRateRepository")
 */
class EntityRate extends BaseEntityRate
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @return mixed
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
}

```
Add the annotation to your entity class:
```php
# src/Acme/AppBundle/Entity/Post.php

use Cymo\Bundle\EntityRatingBundle\Annotation\Rated;
...

/**
 * Post
 * @ORM\Table(name="post")
 * ...
 * @Rated(min=1, max=5, step=1)
 * ...
 */
```

Configure the bundle:
```yaml
# app/config/config.yml
cymo_entity_rating:
     # Your EntityRate namespace, persisted in the DB (according to the namespace of the entity previously created)
     entity_rating_class: Acme\AppBundle\Entity\EntityRate
     # If you decide to extend the default manager, put the service name here
     entity_rating_manager_service: acme.entity_rating.manager
     # Maximum number of rate allowed by IP for a given entity
     rate_by_ip_limitation: 10
     # Map the alias used in frontend and the corresponding class
     map_type_to_class:
       post: Acme\AppBundle\Entity\Post
       # If you need several rated entity, just add them here 
       article: Acme\AppBundle\Entity\Article 

# In order to print the Rating field, pass the template of the field to twig       
twig:
    form_themes:
        - "CymoEntityRatingBundle:form:fields.html.twig"
```

Generate the rating form in the controller:
```php
$entityRatingManager = $this->get('cymo.entity_rating_bundle.manager');
$ratingForm          = $entityRatingManager->generateForm(Post::RATING_ALIAS, $post->getId());
$globalRateData      = $entityRatingManager->getGlobalRateData($post->getId(), Post::RATING_ALIAS);

/** @var EntityRate $rate */
if ($rate = $entityRatingManager->getUserCurrentRate($post->getId(), Post::RATING_ALIAS)) {
    $ratingForm->get('rate')->setData($rate->getRate());
}

return $this->render(
    '@AcmeApp/Blog/show.html.twig',
    [
        'ratingForm'       => $ratingForm->createView(),
        'globalRateData'   => $globalRateData,
    ]
);
```
Display the form in the view:
```twig
{% include 'CymoEntityRatingBundle::ratingWidget.html.twig' with {'form':ratingForm, 'globalRateData':globalRateData} only %}
```

#### Importing the assets

You can import them directly:

JS
```twig
<script src="{{ asset('bundles/cymoentityrating/js/entityRating.js') }}"></script>
```

CSS
```twig
<link rel="stylesheet" href="{{ asset('bundles/cymoentityrating/css/entityRating.css') }}" type="text/css" media="screen">
```

Or use a task manager (gulp/grunt...) to minify/concat/uglify them before serving them.

#### Init the JS

```javascript
new EntityRating({
    form             : $('.entityrating-form'),
    radioButtonClass : '.star-rating-item',
    successCallback  : function (response) {
        var ratingWidgetSelector = $('.entity-rating-widget-wrapper[data-entity-rating-id="' + response.entityRatingId + '"]');
        ratingWidgetSelector.find('.entity-rating-rate-container').slideDown();
        ratingWidgetSelector.find('*[itemprop="ratingCount"]').text(response.rateData.rateCount);
        ratingWidgetSelector.find('*[itemprop="ratingValue"]').text(response.rateData.averageRate);
    },
    errorCallback    : function (response) {
        // Do something in case of error
    }
});

```

### Advanced usage 

#### Adding a relationship to the Rate entity

Example: **saving the logged user in the rate Entity**

1. Add the user field to the entity

```php
 /**
  * @var User
  * @ORM\ManyToOne(targetEntity="Acme\AppBundle\Entity\User")
  * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
  */
 protected $user;
    
 /**
  * @return User
  */
 public function getUser(): User
 {
   return $this->user;
 }

 /**
  * @param User $user
  */
 public function setUser(User $user)
 {
     $this->user = $user;
 }
```

2. Extend the default manager to handle the User

```php

namespace Acme\AppBundle\Manager;

use Cymo\Bundle\EntityRatingBundle\Entity\EntityRateInterface;
use Cymo\Bundle\EntityRatingBundle\Factory\EntityRatingFormFactory;
use Cymo\Bundle\EntityRatingBundle\Manager\EntityRatingManager as BaseEntityRatingManager;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class EntityRatingManager extends BaseEntityRatingManager
{

    private $user = null;

    public function __construct(
        AnnotationReader $annotationReader,
        EntityRatingFormFactory $formFactory,
        Container $container,
        EventDispatcherInterface $eventDispatcher)
    {
        parent::__construct($annotationReader, $formFactory, $container, $eventDispatcher);

        /** @var TokenInterface $token */
        $token = $this->container->get('security.token_storage')->getToken();
        if ($token !== null && is_object($token->getUser())) {
            $this->user = $token->getUser();
        }
    }

    public function getUserCurrentRate($entityId, $entityType, $ignoreFields = [])
    {
        if ($this->user) {
            return $this->entityRateRepository->findOneBy(
                [
                    'entityId'   => $entityId,
                    'entityType' => $entityType,
                    'user'       => $this->user,
                ]
            );
        } else {
            return parent::getUserCurrentRate($entityId, $entityType, ['user']);
        }
    }

    /**
     * @param \Acme\AppBundle\Entity\EntityRate|EntityRateInterface $rate
     * @param $entityId
     * @param $entityType
     * @param $rateValue
     *
     * @return \Acme\AppBundle\Entity\EntityRate|EntityRateInterface
     */
    protected function hydrateEntity(EntityRateInterface $rate, $entityId, $entityType, $rateValue)
    {
        if ($this->user) {
            $rate->setUser($this->user);
        }
        parent::hydrateEntity($rate, $entityId, $entityType, $rateValue);

        return $rate;
    }
}
```

3. Define a new manager service to use in your controller
```yaml
acme.entity_rating.manager:
    class: Acme\AppBundle\Manager\EntityRatingManager
    parent: cymo.entity_rating_bundle.manager
```

#### Events

The bundle dispatches events when a rate is : 
- Created : `cymo.entity_rating.rate_created`
- Updated : `cymo.entity_rating.rate_updated`
