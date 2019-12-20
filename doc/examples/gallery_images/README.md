### Gallery images query field
A field that lists the images of a gallery.

#### Content type configuration
The following assumes a "gallery" content type with an "images" query field.
The actual images are sub-items of the gallery.

##### Query type
AppBundle:Children

##### Returned type
Image

##### Parameters
```yaml
parent_location_id: '@=mainLocation.id'
included_content_type_identifier: '@=returnedType'
```

#### Layout customization
```yaml
ezpublish:
    systems:
        site:
            content_view:
                # Customize the layout around all the images
                content_query_field:
                    gallery_images:
                        match:
                            Identifier\ContentType: gallery
                            Identifier\FieldDefinition: images
                        template: "content/view/content_query_field/gallery_images.html.twig"
                # Customize the layout of each image
                line:
                    images:
                        match:
                            Identifier\ContentType: image
                        template: "content/view/line/images.html.twig"
```
